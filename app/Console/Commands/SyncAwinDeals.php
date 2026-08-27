<?php

namespace App\Console\Commands;

use App\Services\Awin\AwinClient;
use App\Services\Awin\AwinDealSyncer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncAwinDeals extends Command
{
    protected $signature = 'awin:sync';

    protected $description = 'Fetch the latest deals from the Awin Publisher API and upsert them into merchants/offers.';

    public function handle(AwinClient $client, AwinDealSyncer $syncer): int
    {
        if (! config('awin.feed_enabled', false)) {
            $this->info('Awin feed is disabled (AWIN_FEED_ENABLED=false) or not configured — skipping sync.');

            return self::SUCCESS;
        }

        $deals = $client->fetchDeals();

        if (empty($deals)) {
            $this->info('Awin sync: no deals returned (feed disabled, misconfigured, or empty response) — nothing to do.');

            return self::SUCCESS;
        }

        $stats = $syncer->sync($deals);

        $summary = sprintf(
            'Awin sync complete: %d created, %d updated, %d skipped.',
            $stats['created'],
            $stats['updated'],
            $stats['skipped']
        );

        $this->info($summary);
        Log::info('[Awin] '.$summary);

        return self::SUCCESS;
    }
}
