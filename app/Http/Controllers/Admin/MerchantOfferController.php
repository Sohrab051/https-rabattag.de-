<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Merchant;
use App\Models\Offer;
use App\Services\MerchantService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MerchantOfferController extends Controller
{
    public function __construct(private readonly MerchantService $merchantService)
    {
    }

    public function create()
    {
        $categories = Category::orderBy('sort_order')->get();
        $merchants = Merchant::orderBy('name_en')->get();

        return view('admin.merchant-offer.create', compact('categories', 'merchants'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'merchant_id' => ['nullable', 'exists:merchants,id'],
            'merchant_name_en' => ['required_without:merchant_id', 'nullable', 'string', 'max:255'],
            'merchant_name_de' => ['required_without:merchant_id', 'nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'description_en' => ['nullable', 'string'],
            'description_de' => ['nullable', 'string'],
            'website_url' => ['nullable', 'url'],
            'affiliate_link' => ['nullable', 'url'],
            'commission_rate' => ['required', 'numeric', 'min:0'],
            'awin_merchant_id' => ['nullable', 'string', 'max:255', 'unique:merchants,awin_merchant_id'],

            'title_en' => ['required', 'string', 'max:255'],
            'title_de' => ['required', 'string', 'max:255'],
            'offer_description_en' => ['nullable', 'string'],
            'offer_description_de' => ['nullable', 'string'],
            'discount_value' => ['nullable', 'numeric', 'min:0'],
            'min_purchase_amount' => ['nullable', 'numeric', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_featured' => ['sometimes', 'boolean'],
            'action' => ['required', 'in:draft,publish'],
        ]);

        if ($data['merchant_id']) {
            $merchant = Merchant::findOrFail($data['merchant_id']);
        } else {
            $merchant = Merchant::create([
                'name_en' => $data['merchant_name_en'],
                'name_de' => $data['merchant_name_de'],
                'slug' => Str::slug($data['merchant_name_en']).'-'.Str::random(4),
                'description_en' => $data['description_en'] ?? null,
                'description_de' => $data['description_de'] ?? null,
                'website_url' => $data['website_url'] ?? null,
                'affiliate_link' => $data['affiliate_link'] ?? null,
                'commission_rate' => $data['commission_rate'],
                'status' => $data['affiliate_link'] ?? null ? 'active' : 'pending_contract',
                'awin_merchant_id' => $data['awin_merchant_id'] ?? null,
                'source' => ($data['awin_merchant_id'] ?? null) ? 'awin' : 'manual',
            ]);

            // Keep the primary category and the categories pivot in sync,
            // the same way MerchantController does, so merchants created
            // inline from this wizard don't end up with an empty pivot.
            $this->merchantService->syncCategories($merchant, array_filter([$data['category_id'] ?? null]));
        }

        $isPublish = $data['action'] === 'publish';

        $offer = Offer::create([
            'merchant_id' => $merchant->id,
            'title_en' => $data['title_en'],
            'title_de' => $data['title_de'],
            'description_en' => $data['offer_description_en'] ?? null,
            'description_de' => $data['offer_description_de'] ?? null,
            'discount_value' => $data['discount_value'] ?? null,
            'min_purchase_amount' => $data['min_purchase_amount'] ?? null,
            'starts_at' => $data['starts_at'] ?? null,
            'expires_at' => $data['expires_at'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'status' => $isPublish ? 'published' : 'draft',
            'published_at' => $isPublish ? ($data['starts_at'] ?? now()) : null,
        ]);

        return redirect()
            ->route('admin.offers.index', ['locale' => app()->getLocale()])
            ->with('status', __('Offer :status successfully.', ['status' => $isPublish ? 'published' : 'saved as draft']));
    }
}
