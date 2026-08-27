<?php

namespace Tests\Feature\Console;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SyncAwinDealsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_awin_sync_command_runs_successfully_with_faked_http(): void
    {
        config([
            'awin.feed_enabled' => true,
            'awin.publisher_id' => '12345',
            'awin.api_token' => 'test-token',
        ]);

        Http::fake([
            'api.awin.com/*' => Http::response([
                'data' => [
                    [
                        'promotionId' => 'promo-cli-1',
                        'advertiser' => ['id' => 'adv-cli-1', 'name' => 'CLI Shop'],
                        'description' => 'CLI Deal',
                        'urlTracking' => 'https://track/cli-1',
                    ],
                ],
                'pagination' => ['hasMore' => false],
            ], 200),
        ]);

        $this->artisan('awin:sync')->assertExitCode(0);

        $this->assertDatabaseHas('merchants', ['awin_merchant_id' => 'adv-cli-1']);
    }

    public function test_awin_sync_command_skips_gracefully_when_feed_disabled(): void
    {
        config(['awin.feed_enabled' => false]);

        Http::fake();

        $this->artisan('awin:sync')->assertExitCode(0);

        Http::assertNothingSent();
    }

    public function test_console_schedule_still_references_awin_sync_and_expire_check_unchanged(): void
    {
        $contents = file_get_contents(base_path('routes/console.php'));

        $this->assertStringContainsString("Schedule::command('awin:sync')->hourly();", $contents);
        $this->assertStringContainsString("Schedule::command('offers:expire-check')->everyFifteenMinutes();", $contents);
    }
}
