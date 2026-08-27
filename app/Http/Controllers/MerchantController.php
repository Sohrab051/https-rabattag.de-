<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Merchant;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    public function index(Request $request)
    {
        $merchants = Merchant::query()
            ->where('status', 'active')
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->integer('category')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%'.$request->string('q').'%';
                $q->where(fn ($qq) => $qq->where('name_en', 'like', $term)->orWhere('name_de', 'like', $term));
            })
            ->orderBy('name_en')
            ->paginate(24)
            ->withQueryString();

        $categories = Category::orderBy('sort_order')->get();

        return view('stores.index', compact('merchants', 'categories'));
    }

    public function show(string $locale, Merchant $merchant)
    {
        $merchant->load(['publishedOffers', 'reviews' => fn ($q) => $q->approved()->latest()]);

        return view('stores.show', compact('merchant'));
    }
}
