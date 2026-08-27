<?php

namespace Tests\Feature\Jobs;

use App\Jobs\RunAwinSyncJob;
use App\Models\AwinSyncRun;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RunAwinSyncJobTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'awin.feed_enabled' => true,
            'awin.publisher_id' => '12345',
            'awin.api_token' => 'test-token',
        ]);
    }

    public function test_handle_marks_run_completed_with_counts_on_success(): void
    {
        Http::fake([
            'api.awin.com/*' => Http::response([
                'data' => [
                    [
                        'promotionId' => 'promo-1',
                        'advertiser' => ['id' => 'adv-1', 'name' => 'Shop A'],
                        'description' => 'Deal',
                        'urlTracking' => 'https://track/1',
                    ],
                ],
                'pagination' => ['hasMore' => false],
            ], 200),
        ]);

        $run = AwinSyncRun::create(['status' => 'pending']);

        (new RunAwinSyncJob($run->id))->handle(app(\App\Services\Awin\AwinClient::class), app(\App\Services\Awin\AwinDealSyncer::class));

        $run->refresh();

        $this->assertSame('completed', $run->status);
        $this->assertNotNull($run->started_at);
        $this->assertNotNull($run->finished_at);
        $this->assertSame(1, $run->offers_created);
        $this->assertSame(0, $run->offers_updated);
        $this->assertSame(1, $run->merchants_created);
        $this->assertSame(0, $run->merchants_updated);
    }

    public function test_handle_counts_merchant_as_updated_when_it_already_exists(): void
    {
        \App\Models\Merchant::create([
            'name_en' => 'Shop A',
            'name_de' => 'Shop A',
            'slug' => 'shop-a-'.\Illuminate\Support\Str::random(6),
            'source' => 'awin',
            'status' => 'active',
            'awin_merchant_id' => 'adv-1',
        ]);

        Http::fake([
            'api.awin.com/*' => Http::response([
                'data' => [
                    [
                        'promotionId' => 'promo-1',
                        'advertiser' => ['id' => 'adv-1', 'name' => 'Shop A'],
                        'description' => 'Deal',
                        'urlTracking' => 'https://track/1',
                    ],
                ],
                'pagination' => ['hasMore' => false],
            ], 200),
        ]);

        $run = AwinSyncRun::create(['status' => 'pending']);

        (new RunAwinSyncJob($run->id))->handle(app(\App\Services\Awin\AwinClient::class), app(\App\Services\Awin\AwinDealSyncer::class));

        $run->refresh();

        $this->assertSame(0, $run->merchants_created);
        $this->assertSame(1, $run->merchants_updated);
    }

    public function test_handle_counts_mix_of_new_and_existing_merchants(): void
    {
        \App\Models\Merchant::create([
            'name_en' => 'Shop A',
            'name_de' => 'Shop A',
            'slug' => 'shop-a-'.\Illuminate\Support\Str::random(6),
            'source' => 'awin',
            'status' => 'active',
            'awin_merchant_id' => 'adv-1',
        ]);

        Http::fake([
            'api.awin.com/*' => Http::response([
                'data' => [
                    [
                        'promotionId' => 'promo-1',
                        'advertiser' => ['id' => 'adv-1', 'name' => 'Shop A'],
                        'description' => 'Deal 1',
                        'urlTracking' => 'https://track/1',
                    ],
                    [
                        'promotionId' => 'promo-2',
                        'advertiser' => ['id' => 'adv-2', 'name' => 'Shop B'],
                        'description' => 'Deal 2',
                        'urlTracking' => 'https://track/2',
                    ],
                ],
                'pagination' => ['hasMore' => false],
            ], 200),
        ]);

        $run = AwinSyncRun::create(['status' => 'pending']);

        (new RunAwinSyncJob($run->id))->handle(app(\App\Services\Awin\AwinClient::class), app(\App\Services\Awin\AwinDealSyncer::class));

        $run->refresh();

        $this->assertSame(1, $run->merchants_created);
        $this->assertSame(1, $run->merchants_updated);
    }

    public function test_handle_marks_run_failed_with_error_message_on_exception(): void
    {
        Http::fake([
            'api.awin.com/*' => Http::response('Server Error', 500),
        ]);

        // Force an exception path by making the syncer throw.
        $failingSyncer = new class extends \App\Services\Awin\AwinDealSyncer
        {
            public function sync(array $deals): array
            {
                throw new \RuntimeException('Simulated sync failure');
            }
        };

        $run = AwinSyncRun::create(['status' => 'pending']);

        $job = new RunAwinSyncJob($run->id);

        try {
            $job->handle(app(\App\Services\Awin\AwinClient::class), $failingSyncer);
            $this->fail('Expected exception was not thrown.');
        } catch (\RuntimeException $e) {
            // expected — job re-throws so the queue can retry
        }

        $run->refresh();

        $this->assertSame('failed', $run->status);
        $this->assertNotEmpty($run->error_message);
        $this->assertNotNull($run->finished_at);
    }

    public function test_failed_method_marks_run_failed_when_retries_exhausted(): void
    {
        $run = AwinSyncRun::create(['status' => 'running']);

        $job = new RunAwinSyncJob($run->id);
        $job->failed(new \RuntimeException('Final failure'));

        $run->refresh();

        $this->assertSame('failed', $run->status);
        $this->assertSame('Final failure', $run->error_message);
    }
}
