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

        return view('home', compact('categories', 'featuredOffers', 'latestOffers', 'stats'));
    }
}
