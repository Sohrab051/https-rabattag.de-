<?php

namespace App\Services\Awin;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * HTTP client for the real Awin Publisher API.
 *
 * Endpoints implemented (per official Awin Publisher API docs):
 *
 *  - POST https://api.awin.com/publisher/{publisherId}/promotions
 *    Fetches promotions/vouchers. Filters supported: advertiserIds, membership,
 *    regionCodes, status (active|expiringSoon|upcoming), type (promotion|voucher|all),
 *    updatedSince, and pagination (page size capped at 200).
 *
 *  - GET https://api.awin.com/publishers/{publisherId}/programmes
 *    Lists the advertiser programmes/relationships (membership/joined status) for
 *    the publisher account.
 *
 * fetchDeals() normalizes each raw promotion record into this internal DTO shape,
 * which is the contract the rest of the app (CategoryMapper, AwinDealSyncer) relies on:
 *
 *  [
 *      'advertiserId'   => string,   // Awin's merchant/advertiser id (advertiser.id)
 *      'advertiserName' => string,   // advertiser.name, when present
 *      'description'    => string,   // promotion description/title
 *      'code'           => ?string,  // voucher.code, genuinely null when absent
 *      'type'           => string,   // mapped to internal deal_type enum: sale|coupon|cashback
 *      'startDate'      => ?string,  // ISO8601, from startDate
 *      'endDate'        => ?string,  // ISO8601, from endDate
 *      'trackingUrl'    => string,   // urlTracking — affiliate tracking link
 *      'imageUrl'       => ?string,
 *      'terms'          => ?string,
 *      'categories'     => array,    // raw category/keyword strings from Awin
 *      'dealId'         => string,   // promotionId — unique id used for upsert dedup
 *  ]
 *
 * Max page size Awin allows for /promotions is 200; requested sizes above that are
 * silently clamped rather than trusted verbatim.
 */
class AwinClient
{
    private const MAX_PAGE_SIZE = 200;

    /**
     * Safety cap on how many pages fetchAllPromotionPages() will walk before giving up,
     * so a misbehaving/endless pagination response can never cause an infinite loop.
     */
    private const MAX_PAGES = 50;

    private readonly ?string $publisherId;

    private readonly ?string $apiToken;

    private readonly string $baseUrl;

    public function __construct(?string $publisherId = null, ?string $apiToken = null, ?string $baseUrl = null)
    {
        $this->publisherId = $publisherId ?? config('awin.publisher_id');
        $this->apiToken = $apiToken ?? config('awin.api_token');
        $this->baseUrl = $baseUrl ?? config('awin.base_url', 'https://api.awin.com');
    }

    /**
     * Fetch and normalize the current promotions/vouchers feed for our publisher account,
     * walking all available pages.
     *
     * Returns an empty array (never throws) when the feed is disabled, credentials are
     * missing, or the request fails for any reason.
     *
     * @param  array<int, string>  $regionCodes  Defaults to Germany for Rabattag.
     * @param  array<int, string>|null  $advertiserIds
     * @return array<int, array<string, mixed>>
     */
    public function fetchDeals(
        array $regionCodes = ['DE'],
        string $type = 'all',
        string $status = 'active',
        ?string $updatedSince = null,
        ?array $advertiserIds = null,
        ?string $membership = null,
    ): array {
        if (! $this->isConfigured()) {
            return [];
        }

        $rawPromotions = $this->fetchAllPromotionPages(
            regionCodes: $regionCodes,
            type: $type,
            status: $status,
            updatedSince: $updatedSince,
            advertiserIds: $advertiserIds,
            membership: $membership,
        );

        return collect($rawPromotions)
            ->map(fn (array $raw) => $this->normalize($raw))
            ->all();
    }

    /**
     * Fetch a single page of promotions/vouchers from the Awin API.
     *
     * POST /publisher/{publisherId}/promotions
     *
     * @param  array<int, string>  $regionCodes
     * @param  array<int, string>|null  $advertiserIds
     * @return array{data: array<int, array<string, mixed>>, hasMore: bool, page: int}
     */
    public function fetchPromotionsPage(
        int $page = 0,
        int $pageSize = self::MAX_PAGE_SIZE,
        array $regionCodes = ['DE'],
        string $type = 'all',
        string $status = 'active',
        ?string $updatedSince = null,
        ?array $advertiserIds = null,
        ?string $membership = null,
    ): array {
        $empty = ['data' => [], 'hasMore' => false, 'page' => $page];

        if (! $this->isConfigured()) {
            return $empty;
        }

        $clampedPageSize = max(1, min($pageSize, self::MAX_PAGE_SIZE));

        $body = [
            'regionCodes' => $regionCodes,
            'type' => $type,
            'status' => $status,
            'pagination' => [
                'page' => $page,
                'pageSize' => $clampedPageSize,
            ],
        ];

        if ($updatedSince !== null) {
            $body['updatedSince'] = $updatedSince;
        }

        if ($advertiserIds !== null) {
            $body['advertiserIds'] = $advertiserIds;
        }

        if ($membership !== null) {
            $body['membership'] = $membership;
        }

        try {
            $response = Http::withToken($this->apiToken)
                ->timeout(30)
                ->post("{$this->baseUrl}/publisher/{$this->publisherId}/promotions", $body);

            if (! $response->successful()) {
                Log::warning('[Awin] Promotions request failed.', [
                    'status' => $response->status(),
                ]);

                return $empty;
            }

            $json = $response->json();

            if (! is_array($json)) {
                Log::warning('[Awin] Promotions response was not a JSON object/array — degrading gracefully.');

                return $empty;
            }

            // Response shape assumption (not fully specified by the Awin docs beyond
            // "pagination is supported"): promotions are expected under a top-level
            // `data` key, with pagination metadata either alongside it (e.g. a
            // `pagination` object carrying `page`/`totalPages` or `hasMore`) or absent
            // entirely. We read defensively so any of these shapes — or an unexpected
            // one — degrades to "no more pages" instead of crashing.
            $data = $json['data'] ?? (array_is_list($json) ? $json : []);
            $data = is_array($data) ? $data : [];

            $hasMore = $this->detectHasMore($json, $page, count($data), $clampedPageSize);

            return ['data' => $data, 'hasMore' => $hasMore, 'page' => $page];
        } catch (\Throwable $e) {
            Log::error('[Awin] Promotions request threw an exception.', ['message' => $e->getMessage()]);

            return $empty;
        }
    }

    /**
     * Walk all pages of the promotions endpoint (bounded by MAX_PAGES as a safety limit)
     * and return the concatenated raw records.
     *
     * @param  array<int, string>  $regionCodes
     * @param  array<int, string>|null  $advertiserIds
     * @return array<int, array<string, mixed>>
     */
    public function fetchAllPromotionPages(
        array $regionCodes = ['DE'],
        string $type = 'all',
        string $status = 'active',
        ?string $updatedSince = null,
        ?array $advertiserIds = null,
        ?string $membership = null,
        int $pageSize = self::MAX_PAGE_SIZE,
    ): array {
        $all = [];
        $page = 0;

        do {
            $result = $this->fetchPromotionsPage(
                page: $page,
                pageSize: $pageSize,
                regionCodes: $regionCodes,
                type: $type,
                status: $status,
                updatedSince: $updatedSince,
                advertiserIds: $advertiserIds,
                membership: $membership,
            );

            if (empty($result['data'])) {
                break;
            }

            $all = array_merge($all, $result['data']);
            $page++;
        } while ($result['hasMore'] && $page < self::MAX_PAGES);

        return $all;
    }

    /**
     * Fetch the publisher's advertiser programmes (membership/joined status).
     *
     * GET /publishers/{publisherId}/programmes
     *
     * @return array<int, array<string, mixed>>
     */
    public function fetchProgrammes(): array
    {
        if (! $this->isConfigured()) {
            return [];
        }

        try {
            $response = Http::withToken($this->apiToken)
                ->timeout(30)
                ->get("{$this->baseUrl}/publishers/{$this->publisherId}/programmes");

            if (! $response->successful()) {
                Log::warning('[Awin] Programmes request failed.', [
                    'status' => $response->status(),
                ]);

                return [];
            }

            $json = $response->json();

            if (! is_array($json)) {
                return [];
            }

            $data = array_is_list($json) ? $json : ($json['data'] ?? []);

            return is_array($data) ? $data : [];
        } catch (\Throwable $e) {
            Log::error('[Awin] Programmes request threw an exception.', ['message' => $e->getMessage()]);

            return [];
        }
    }

    private function isConfigured(): bool
    {
        if (! config('awin.feed_enabled', false)) {
            Log::warning('[Awin] Feed sync skipped: AWIN_FEED_ENABLED is false.');

            return false;
        }

        if (! $this->publisherId || ! $this->apiToken) {
            Log::warning('[Awin] Feed sync skipped: publisher id or API token is not configured.');

            return false;
        }

        return true;
    }

    /**
     * Defensively determine whether more pages remain, tolerating several plausible
     * Awin pagination shapes (documented assumption — see fetchPromotionsPage()):
     *
     *  - `pagination.hasMore` (bool)
     *  - `pagination.totalPages` compared against `pagination.page`/current page
     *  - `pagination.page` + `pagination.totalPages` at the top level
     *  - fallback: if the returned page was full (== requested page size), assume
     *    there might be another page; if it was short, assume this was the last page.
     */
    private function detectHasMore(array $json, int $page, int $itemCount, int $pageSize): bool
    {
        $pagination = $json['pagination'] ?? null;

        if (is_array($pagination)) {
            if (array_key_exists('hasMore', $pagination)) {
                return (bool) $pagination['hasMore'];
            }

            if (array_key_exists('totalPages', $pagination)) {
                $currentPage = $pagination['page'] ?? $page;

                return ($currentPage + 1) < (int) $pagination['totalPages'];
            }
        }

        if (array_key_exists('totalPages', $json)) {
            return ($page + 1) < (int) $json['totalPages'];
        }

        // Fallback: a full page suggests there may be more; a short/empty page doesn't.
        return $itemCount >= $pageSize;
    }

    /**
     * Map a raw Awin promotion/voucher record into our internal DTO shape.
     *
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    private function normalize(array $raw): array
    {
        $advertiser = is_array($raw['advertiser'] ?? null) ? $raw['advertiser'] : [];
        $voucher = is_array($raw['voucher'] ?? null) ? $raw['voucher'] : [];

        $code = $voucher['code'] ?? null;
        $code = (is_string($code) && $code !== '') ? $code : null;

        return [
            'advertiserId' => (string) ($advertiser['id'] ?? ''),
            'advertiserName' => (string) ($advertiser['name'] ?? ''),
            'description' => (string) ($raw['description'] ?? $raw['title'] ?? ''),
            'code' => $code,
            'type' => $this->mapDealType((string) ($raw['type'] ?? ''), $code),
            'startDate' => $this->safeIso($raw['startDate'] ?? null),
            'endDate' => $this->safeIso($raw['endDate'] ?? null),
            'trackingUrl' => (string) ($raw['urlTracking'] ?? ''),
            'imageUrl' => $raw['imageUrl'] ?? null,
            'terms' => $raw['terms'] ?? null,
            'categories' => (array) ($raw['categories'] ?? []),
            'dealId' => (string) ($raw['promotionId'] ?? ''),
        ];
    }

    /**
     * Map Awin's `type` (promotion|voucher|...) to the internal deal_type enum
     * (sale|coupon|cashback — see offers migration). Awin's real enum values beyond
     * "promotion" and "voucher" are not documented here, so anything unrecognized
     * falls back to the safest existing value ('sale') rather than inventing a new
     * enum value. This ambiguity is called out explicitly in the task report.
     */
    private function mapDealType(string $rawType, ?string $code): string
    {
        return match (strtolower($rawType)) {
            'voucher' => $code !== null ? 'coupon' : 'sale',
            'promotion' => 'sale',
            default => $code !== null ? 'coupon' : 'sale',
        };
    }

    private function safeIso(mixed $value): ?string
    {
        if (! is_string($value) || $value === '') {
            return null;
        }

        try {
            return Carbon::parse($value)->toIso8601String();
        } catch (\Throwable) {
            return null;
        }
    }
}
