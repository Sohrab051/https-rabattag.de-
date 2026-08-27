<?php

namespace App\Jobs;

use App\Models\AwinSyncRun;
use App\Services\Awin\AwinClient;
use App\Services\Awin\AwinDealSyncer;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Runs an Awin deal sync in the background and records the outcome on the
 * associated AwinSyncRun row. Reuses AwinClient/AwinDealSyncer exactly as the
 * `awin:sync` CLI command does — no duplicated sync logic here.
 */
class RunAwinSyncJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public int $tries = 3;

    public function __construct(
        public readonly int $awinSyncRunId,
    ) {}

    /**
     * Keep this job unique while pending/queued/executing so only one Awin
     * sync can run at a time — a second layer of safety on top of the
     * pending/running check performed at dispatch time in the controller.
     */
    public function uniqueId(): string
    {
        return 'awin-sync';
    }

    /**
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [60, 300, 900];
    }

    public function handle(AwinClient $client, AwinDealSyncer $syncer): void
    {
        $run = AwinSyncRun::find($this->awinSyncRunId);

        if (! $run) {
            Log::error('[Awin] RunAwinSyncJob: AwinSyncRun not found.', ['id' => $this->awinSyncRunId]);

            return;
        }

        $run->update([
            'status' => 'running',
            'started_at' => now(),
        ]);

        try {
            $deals = $client->fetchDeals();
            $stats = $syncer->sync($deals);

            $run->update([
                'status' => 'completed',
                'finished_at' => now(),
                'offers_created' => $stats['created'] ?? 0,
                'offers_updated' => $stats['updated'] ?? 0,
                'offers_skipped' => $stats['skipped'] ?? 0,
                'merchants_created' => $stats['merchants_created'] ?? 0,
                'merchants_updated' => $stats['merchants_updated'] ?? 0,
            ]);
        } catch (\Throwable $e) {
            $this->markFailed($run, $e);

            throw $e;
        }
    }

    /**
     * Called by the queue worker once all retry attempts are exhausted.
     */
    public function failed(?\Throwable $exception): void
    {
        $run = AwinSyncRun::find($this->awinSyncRunId);

        if (! $run || $run->status === 'failed') {
            return;
        }

        $this->markFailed($run, $exception);
    }

    private function markFailed(AwinSyncRun $run, ?\Throwable $e): void
    {
        $message = $e ? Str::limit($e->getMessage(), 500) : 'Unknown error';

        Log::error('[Awin] RunAwinSyncJob failed.', [
            'awin_sync_run_id' => $run->id,
            'message' => $message,
        ]);

        $run->update([
            'status' => 'failed',
            'finished_at' => now(),
            'error_message' => $message,
            'errors_count' => $run->errors_count + 1,
        ]);
    }
}
