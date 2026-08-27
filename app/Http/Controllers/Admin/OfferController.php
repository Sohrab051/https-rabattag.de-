<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $offers = Offer::with('merchant', 'category')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->boolean('needs_review'), fn ($q) => $q->where('needs_review', true))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.offers.index', compact('offers'));
    }

    public function edit(string $locale, Offer $offer)
    {
        $categories = Category::orderBy('sort_order')->get();

        return view('admin.offers.edit', compact('offer', 'categories'));
    }

    public function update(Request $request, string $locale, Offer $offer)
    {
        $data = $request->validate([
            'title_en' => ['required', 'string', 'max:255'],
            'title_de' => ['required', 'string', 'max:255'],
            'description_en' => ['nullable', 'string'],
            'description_de' => ['nullable', 'string'],
            'discount_value' => ['nullable', 'numeric', 'min:0'],
            'discount_type' => ['required', 'in:percentage,fixed'],
            'coupon_code' => ['nullable', 'string', 'max:255'],
            'affiliate_url' => ['nullable', 'url', 'max:2048'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'expires_at' => ['nullable', 'date'],
            'is_featured' => ['sometimes', 'boolean'],
        ]);

        $offer->update([...$data, 'is_featured' => $request->boolean('is_featured')]);

        return back()->with('status', __('Offer updated.'));
    }

    public function destroy(string $locale, Offer $offer)
    {
        $offer->delete();

        return back()->with('status', __('Offer deleted.'));
    }

    public function publish(string $locale, Offer $offer)
    {
        $offer->update([
            'status' => 'published',
            'published_at' => $offer->published_at ?? now(),
        ]);

        return back()->with('status', __('Offer published.'));
    }

    public function toggleVerified(string $locale, Offer $offer)
    {
        $offer->update(['is_verified' => ! $offer->is_verified]);

        return back()->with('status', $offer->is_verified ? __('Offer marked as verified.') : __('Offer verification removed.'));
    }
}
