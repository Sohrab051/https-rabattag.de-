<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Merchant;
use App\Models\Offer;

class HomeController extends Controller
{
    public function __invoke()
    {
        $categories = Category::orderBy('sort_order')->get();

        $stats = [
            'offers' => Offer::published()->count(),
            'stores' => Merchant::where('status', 'active')->count(),
        ];

        $featuredOffers = Offer::with('merchant')
            ->published()
            ->featured()
            ->orderByDesc('priority')
            ->take(8)
            ->get();

        $latestOffers = Offer::with('merchant')
            ->published()
            ->orderByDesc('published_at')
            ->take(12)
            ->get();

        // Sidebar: "Trending" has no dedicated popularity metric in this app,
        // so we order by is_featured (editorially promoted) then most recently
        // published as the closest real signal — no fabricated click counts.
        $trendingOffers = Offer::with('merchant')
            ->published()
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->take(4)
            ->get();

        $newestOffers = Offer::with('merchant')
            ->published()
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        $topMerchants = Merchant::withCount(['offers as published_offers_count' => function ($query) {
            $query->where('status', 'published')
                ->where('published_at', '<=', now())
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
                });
        }])
            ->where('status', 'active')
            ->orderByDesc('is_featured')
            ->orderByDesc('published_offers_count')
            ->take(5)
            ->get();

        return view('home', compact(
            'categories',
            'featuredOffers',
            'latestOffers',
            'stats',
            'trendingOffers',
            'newestOffers',
            'topMerchants',
        ));
    }
}
