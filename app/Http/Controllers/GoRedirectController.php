<?php

namespace App\Http\Controllers;

use App\Models\ClickLog;
use App\Models\Merchant;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GoRedirectController extends Controller
{
    public function __invoke(Request $request, Merchant $merchant)
    {
        if ($merchant->status !== 'active' || ! $merchant->affiliate_link) {
            abort(404);
        }

        $offerId = $request->integer('offer') ?: null;
        $offer = $offerId ? Offer::find($offerId) : null;

        $destination = $offer?->resolvedAffiliateUrl() ?? $merchant->affiliate_link;

        ClickLog::create([
            'click_id' => (string) Str::uuid(),
            'user_id' => $request->user()?->id,
            'merchant_id' => $merchant->id,
            'offer_id' => $offerId,
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
        ]);

        return redirect()->away($destination);
    }
}
