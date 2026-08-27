<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\RunAwinSyncJob;
use App\Models\AwinSyncRun;

class AwinSyncController extends Controller
{
    public function index()
    {
        $configured = (bool) config('awin.feed_enabled') && filled(config('awin.publisher_id')) && filled(config('awin.api_token'));
        $maskedPublisherId = $this->maskPublisherId(config('awin.publisher_id'));
        $feedEnabled = (bool) config('awin.feed_enabled');

        $latestRun = AwinSyncRun::query()->latest('id')->first();
        $runs = AwinSyncRun::query()->latest('id')->paginate(15);

        return view('admin.awin.index', [
            'configured' => $configured,
            'maskedPublisherId' => $maskedPublisherId,
            'feedEnabled' => $feedEnabled,
            'latestRun' => $latestRun,
            'runs' => $runs,
        ]);
    }

    public function sync()
    {
        $hasActiveRun = AwinSyncRun::query()->whereIn('status', ['pending', 'running'])->exists();

        if ($hasActiveRun) {
            return back()->with('status', __('An Awin sync is already in progress. Please wait for it to finish.'));
        }

        $run = AwinSyncRun::create(['status' => 'pending']);

        RunAwinSyncJob::dispatch($run->id);

        return redirect()
            ->route('admin.awin.index', ['locale' => app()->getLocale()])
            ->with('status', __('Sync started.'));
    }

    private function maskPublisherId(?string $publisherId): string
    {
        if (! $publisherId) {
            return __('Not configured');
        }

        $length = strlen($publisherId);

        if ($length <= 4) {
            return str_repeat('*', $length);
        }

        return str_repeat('*', $length - 4).substr($publisherId, -4);
    }
}
