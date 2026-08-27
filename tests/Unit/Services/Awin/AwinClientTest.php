<?php

namespace Tests\Unit\Services\Awin;

use App\Services\Awin\AwinClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AwinClientTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'awin.publisher_id' => '12345',
            'awin.api_token' => 'test-token-secret',
            'awin.feed_enabled' => true,
            'awin.base_url' => 'https://api.awin.com',
        ]);
    }

    public function test_fetch_deals_returns_empty_and_logs_warning_when_feed_disabled(): void
    {
        config(['awin.feed_enabled' => false]);
        Http::fake();
        Log::spy();

        $client = new AwinClient;
        $result = $client->fetchDeals();

        $this->assertSame([], $result);
        Log::shouldHaveReceived('warning')->once();
        Http::assertNothingSent();
    }

    public function test_fetch_deals_returns_empty_and_logs_warning_when_no_token(): void
    {
        config(['awin.api_token' => null]);
        Http::fake();
        Log::spy();

        $client = new AwinClient;
        $result = $client->fetchDeals();

        $this->assertSame([], $result);
        Log::shouldHaveReceived('warning')->once();
        Http::assertNothingSent();
    }

    public function test_promotions_request_hits_correct_url_method_headers_and_body(): void
    {
        Http::fake([
            'api.awin.com/*' => Http::response(['data' => [], 'pagination' => ['hasMore' => false]], 200),
        ]);

        $client = new AwinClient;
        $client->fetchDeals(regionCodes: ['DE'], type: 'all', status: 'active', updatedSince: null);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.awin.com/publisher/12345/promotions'
                && $request->method() === 'POST'
                && $request->hasHeader('Authorization', 'Bearer test-token-secret')
                && $request['regionCodes'] === ['DE']
                && $request['type'] === 'all'
                && $request['status'] === 'active'
                && isset($request['pagination']['pageSize']);
        });
    }

    public function test_pagination_requests_second_page_and_merges_results(): void
    {
        $page0 = [
            'data' => [
                ['promotionId' => '1', 'advertiser' => ['id' => 'a1', 'name' => 'Shop A'], 'urlTracking' => 'https://track/1'],
            ],
            'pagination' => ['page' => 0, 'hasMore' => true],
        ];
        $page1 = [
            'data' => [
                ['promotionId' => '2', 'advertiser' => ['id' => 'a2', 'name' => 'Shop B'], 'urlTracking' => 'https://track/2'],
            ],
            'pagination' => ['page' => 1, 'hasMore' => false],
        ];

        $call = 0;
        Http::fake(function ($request) use (&$call, $page0, $page1) {
            $call++;

            return Http::response($call === 1 ? $page0 : $page1, 200);
        });

        $client = new AwinClient;
        $deals = $client->fetchDeals();

        $this->assertCount(2, $deals);
        $this->assertSame('1', $deals[0]['dealId']);
        $this->assertSame('2', $deals[1]['dealId']);

        Http::assertSentCount(2);
        Http::assertSent(fn ($request) => ($request['pagination']['page'] ?? null) === 1);
    }

    public function test_page_size_is_clamped_to_200(): void
    {
        Http::fake([
            'api.awin.com/*' => Http::response(['data' => [], 'pagination' => ['hasMore' => false]], 200),
        ]);

        $client = new AwinClient;
        $client->fetchPromotionsPage(page: 0, pageSize: 500);

        Http::assertSent(fn ($request) => $request['pagination']['pageSize'] === 200);
    }

    public function test_updated_since_is_passed_through_in_request_body(): void
    {
        Http::fake([
            'api.awin.com/*' => Http::response(['data' => [], 'pagination' => ['hasMore' => false]], 200),
        ]);

        $client = new AwinClient;
        $client->fetchDeals(updatedSince: '2026-08-01T00:00:00Z');

        Http::assertSent(fn ($request) => $request['updatedSince'] === '2026-08-01T00:00:00Z');
    }

    public function test_response_mapping_produces_correct_dto_fields(): void
    {
        Http::fake([
            'api.awin.com/*' => Http::response([
                'data' => [
                    [
                        'promotionId' => 'promo-99',
                        'advertiser' => ['id' => 'adv-42', 'name' => 'Example Shop'],
                        'description' => 'Summer Sale',
                        'type' => 'voucher',
                        'voucher' => ['code' => 'SUMMER20'],
                        'startDate' => '2026-08-01T00:00:00Z',
                        'endDate' => '2026-08-31T23:59:59Z',
                        'urlTracking' => 'https://awin-tracking.example/click?id=promo-99',
                    ],
                ],
                'pagination' => ['hasMore' => false],
            ], 200),
        ]);

        $client = new AwinClient;
        $deals = $client->fetchDeals();

        $this->assertCount(1, $deals);
        $deal = $deals[0];

        $this->assertSame('adv-42', $deal['advertiserId']);
        $this->assertSame('promo-99', $deal['dealId']);
        $this->assertSame('https://awin-tracking.example/click?id=promo-99', $deal['trackingUrl']);
        $this->assertSame('SUMMER20', $deal['code']);
        $this->assertSame('coupon', $deal['type']);
        $this->assertNotNull($deal['startDate']);
        $this->assertNotNull($deal['endDate']);
    }

    public function test_response_mapping_leaves_code_null_when_no_voucher(): void
    {
        Http::fake([
            'api.awin.com/*' => Http::response([
                'data' => [
                    [
                        'promotionId' => 'promo-1',
                        'advertiser' => ['id' => 'adv-1', 'name' => 'Plain Shop'],
                        'type' => 'promotion',
                        'urlTracking' => 'https://track/plain',
                    ],
                ],
                'pagination' => ['hasMore' => false],
            ], 200),
        ]);

        $client = new AwinClient;
        $deals = $client->fetchDeals();

        $this->assertNull($deals[0]['code']);
        $this->assertSame('sale', $deals[0]['type']);
    }

    public function test_malformed_response_degrades_gracefully(): void
    {
        Http::fake([
            'api.awin.com/*' => Http::response(['unexpected' => 'shape'], 200),
        ]);

        $client = new AwinClient;
        $deals = $client->fetchDeals();

        $this->assertSame([], $deals);
    }

    public function test_http_error_returns_empty_without_throwing(): void
    {
        Http::fake([
            'api.awin.com/*' => Http::response('Server Error', 500),
        ]);

        $client = new AwinClient;
        $deals = $client->fetchDeals();

        $this->assertSame([], $deals);
    }

    public function test_missing_dates_do_not_crash_normalization(): void
    {
        Http::fake([
            'api.awin.com/*' => Http::response([
                'data' => [
                    [
                        'promotionId' => 'promo-x',
                        'advertiser' => ['id' => 'adv-x'],
                        'startDate' => 'not-a-date',
                        'endDate' => null,
                    ],
                ],
                'pagination' => ['hasMore' => false],
            ], 200),
        ]);

        $client = new AwinClient;
        $deals = $client->fetchDeals();

        $this->assertNull($deals[0]['startDate']);
        $this->assertNull($deals[0]['endDate']);
    }

    public function test_programmes_endpoint_hits_correct_url_and_extracts_membership(): void
    {
        Http::fake([
            'api.awin.com/publishers/12345/programmes' => Http::response([
                [
                    'advertiser' => ['id' => 'adv-1', 'name' => 'Shop A'],
                    'membership' => ['status' => 'joined'],
                ],
                [
                    'advertiser' => ['id' => 'adv-2', 'name' => 'Shop B'],
                    'membership' => ['status' => 'pending'],
                ],
            ], 200),
        ]);

        $client = new AwinClient;
        $programmes = $client->fetchProgrammes();

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.awin.com/publishers/12345/programmes'
                && $request->method() === 'GET'
                && $request->hasHeader('Authorization', 'Bearer test-token-secret');
        });

        $this->assertCount(2, $programmes);
        $this->assertSame('joined', $programmes[0]['membership']['status']);
        $this->assertSame('pending', $programmes[1]['membership']['status']);
    }
}
