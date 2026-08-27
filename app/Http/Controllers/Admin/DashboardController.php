<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClickLog;
use App\Models\Conversion;
use App\Models\Merchant;
use App\Models\Offer;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $startOfMonth = now()->startOfMonth();

        $clicksThisMonth = ClickLog::where('created_at', '>=', $startOfMonth)->count();

        $conversionsThisMonth = Conversion::whereHas('clickLog', function ($q) use ($startOfMonth) {
            $q->where('created_at', '>=', $startOfMonth);
        });

        $commissionThisMonth = (clone $conversionsThisMonth)->sum('commission_amount');

        $conversionRate = $clicksThisMonth > 0
            ? round(($conversionsThisMonth->count() / $clicksThisMonth) * 100, 2)
            : 0;

        $activeOffersCount = Offer::published()->count();

        $topMerchants = Merchant::query()
            ->select('merchants.*')
            ->selectSub(
                Conversion::query()
                    ->join('click_logs', 'click_logs.id', '=', 'conversions.click_log_id')
                    ->whereColumn('click_logs.merchant_id', 'merchants.id')
                    ->selectRaw('coalesce(sum(commission_amount), 0)'),
                'total_commission'
            )
            ->orderByDesc('total_commission')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'clicksThisMonth', 'commissionThisMonth',
            'conversionRate', 'activeOffersCount', 'topMerchants'
        ));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $type = $request->string('type', 'clicks');

        $filename = "report-{$type}-".now()->format('Ymd_His').'.csv';

        $callback = function () use ($type) {
            $handle = fopen('php://output', 'w');

            if ($type === 'offers') {
                fputcsv($handle, ['ID', 'Merchant', 'Title (DE)', 'Status', 'Clicks', 'Published At', 'Expires At']);
                Offer::with(['merchant', 'clickLogs'])->chunk(200, function ($offers) use ($handle) {
                    foreach ($offers as $offer) {
                        fputcsv($handle, [
                            $offer->id,
                            $offer->merchant?->name_de,
                            $offer->title_de,
                            $offer->status,
                            $offer->clickLogs->count(),
                            optional($offer->published_at)->toDateTimeString(),
                            optional($offer->expires_at)->toDateTimeString(),
                        ]);
                    }
                });
            } elseif ($type === 'merchants') {
                fputcsv($handle, ['ID', 'Name (DE)', 'Status', 'Commission Rate', 'Clicks']);
                Merchant::withCount('clickLogs')->chunk(200, function ($merchants) use ($handle) {
                    foreach ($merchants as $merchant) {
                        fputcsv($handle, [
                            $merchant->id,
                            $merchant->name_de,
                            $merchant->status,
                            $merchant->commission_rate,
                            $merchant->click_logs_count,
                        ]);
                    }
                });
            } else {
                fputcsv($handle, ['ID', 'Merchant', 'Offer ID', 'User ID', 'IP', 'Created At']);
                ClickLog::with('merchant')->chunk(200, function ($clicks) use ($handle) {
                    foreach ($clicks as $click) {
                        fputcsv($handle, [
                            $click->id,
                            $click->merchant?->name_de,
                            $click->offer_id,
                            $click->user_id,
                            $click->ip_address,
                            $click->created_at,
                        ]);
                    }
                });
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
