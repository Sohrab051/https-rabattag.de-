<?php

namespace App\Services\Awin;

use App\Models\Merchant;
use App\Models\Offer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Upserts normalized Awin deal DTOs (see AwinClient's docblock for the shape) into
 * merchants/offers. Safe to call repeatedly — merchants are matched on awin_merchant_id
 * and offers on awin_deal_id, so re-running a sync updates existing rows instead of
 * duplicating them.
 */
class AwinDealSyncer
{
    public function __construct(
        private readonly CategoryMapper $categoryMapper = new CategoryMapper,
    ) {}

    /**
     * @param  array<int, array<string, mixed>>  $deals
     * @return array{created: int, updated: int, skipped: int, merchants_created: int, merchants_updated: int}
     */
    public function sync(array $deals): array
    {
        $stats = [
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'merchants_created' => 0,
            'merchants_updated' => 0,
        ];

        foreach ($deals as $deal) {
            if (empty($deal['dealId']) || empty($deal['advertiserId'])) {
                $stats['skipped']++;

                continue;
            }

            $merchant = $this->upsertMerchant($deal, $stats);
            $wasExisting = Offer::where('awin_deal_id', $deal['dealId'])->exists();

            $this->upsertOffer($merchant, $deal);

            $wasExisting ? $stats['updated']++ : $stats['created']++;
        }

        return $stats;
    }

    private function upsertMerchant(array $deal, array &$stats): Merchant
    {
        $name = $deal['advertiserName'] ?: 'Awin Advertiser '.$deal['advertiserId'];

        $merchant = Merchant::firstOrNew(['awin_merchant_id' => $deal['advertiserId']]);
        $merchantExisted = $merchant->exists;

        $merchant->fill([
            'name_en' => $merchant->name_en ?: $name,
            'name_de' => $merchant->name_de ?: $name,
            'slug' => $merchant->slug ?: Str::slug($name).'-'.Str::lower(Str::random(4)),
            'source' => 'awin',
            'status' => $merchant->status ?: 'active',
            'affiliate_link' => $merchant->affiliate_link ?: ($deal['trackingUrl'] ?: null),
            'last_synced_at' => now(),
        ]);

        $merchant->save();

        $merchantExisted ? $stats['merchants_updated']++ : $stats['merchants_created']++;

        return $merchant;
    }

    private function upsertOffer(Merchant $merchant, array $deal): Offer
    {
        $category = $this->categoryMapper->map($deal['categories'] ?? []);
        $startsAt = $this->parseDate($deal['startDate'] ?? null);

        $offer = Offer::firstOrNew(['awin_deal_id' => $deal['dealId']]);

        $offer->fill([
            'merchant_id' => $merchant->id,
            'category_id' => $category?->id,
            'title_en' => $deal['description'] ?: $offer->title_en ?: 'Awin deal',
            'title_de' => $deal['description'] ?: $offer->title_de ?: 'Awin Angebot',
            'description_en' => $deal['description'] ?? $offer->description_en,
            'description_de' => $deal['description'] ?? $offer->description_de,
            'coupon_code' => $deal['code'] ?? null,
            'deal_type' => in_array($deal['type'] ?? null, ['sale', 'coupon', 'cashback'], true)
                ? $deal['type']
                : ($deal['code'] ? 'coupon' : 'sale'),
            'affiliate_url' => $deal['trackingUrl'] ?: null,
            'terms_en' => $deal['terms'] ?? $offer->terms_en,
            'terms_de' => $deal['terms'] ?? $offer->terms_de,
            'starts_at' => $startsAt,
            'expires_at' => $this->parseDate($deal['endDate'] ?? null),
            'source' => 'awin',
            'synced_at' => now(),
            'needs_review' => $category === null,
            'status' => $startsAt && $startsAt->isFuture() ? 'pending' : 'published',
            'published_at' => $offer->published_at ?? now(),
        ]);

        $offer->save();

        return $offer;
    }

    private function parseDate(?string $value): ?Carbon
    {
        if (! $value) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }
}
