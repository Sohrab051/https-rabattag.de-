<?php

namespace App\Console\Commands;

use App\Models\Offer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpireOffers extends Command
{
    protected $signature = 'offers:expire-check';

    protected $description = 'Mark published offers whose expires_at has passed as expired.';

    public function handle(): int
    {
        $count = Offer::query()
            ->where('status', 'published')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);

        $message = "Offer expiry check complete: {$count} offer(s) marked as expired.";

        $this->info($message);
        Log::info('[Offers] '.$message);

        return self::SUCCESS;
    }
}
