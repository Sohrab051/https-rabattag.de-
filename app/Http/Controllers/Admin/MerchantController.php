<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Merchant;
use App\Services\MerchantService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MerchantController extends Controller
{
    public function __construct(private readonly MerchantService $merchantService)
    {
    }

    public function index(Request $request)
    {
        $merchants = Merchant::query()
            ->with('category')
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%'.$request->string('q').'%';
                $q->where(fn ($qq) => $qq->where('name_en', 'like', $term)->orWhere('name_de', 'like', $term));
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->integer('category')))
            ->orderBy('name_en')
            ->paginate(20)
            ->withQueryString();

        $categories = Category::orderBy('sort_order')->get();

        return view('admin.merchants.index', compact('merchants', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('sort_order')->get();
        $merchant = new Merchant();

        return view('admin.merchants.create', compact('categories', 'merchant'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $merchant = Merchant::create([
            'name_en' => $data['name_en'],
            'name_de' => $data['name_de'],
            'slug' => $data['slug'],
            'logo' => $this->handleLogoUpload($request),
            'awin_merchant_id' => $data['awin_merchant_id'] ?? null,
            'source' => $data['source'],
            'affiliate_link' => $data['affiliate_link'] ?? null,
            'status' => $data['status'],
        ]);

        $this->merchantService->syncCategories($merchant, $data['categories'] ?? []);

        return redirect()
            ->route('admin.merchants.index', ['locale' => app()->getLocale()])
            ->with('status', __('Store created.'));
    }

    public function edit(string $locale, Merchant $merchant)
    {
        $categories = Category::orderBy('sort_order')->get();
        $merchant->load('categories');

        return view('admin.merchants.edit', compact('merchant', 'categories'));
    }

    public function update(Request $request, string $locale, Merchant $merchant)
    {
        $data = $this->validateData($request, $merchant->id);

        $merchant->update([
            'name_en' => $data['name_en'],
            'name_de' => $data['name_de'],
            'slug' => $data['slug'],
            'logo' => $this->handleLogoUpload($request) ?? $merchant->logo,
            'awin_merchant_id' => $data['awin_merchant_id'] ?? null,
            'affiliate_link' => $data['affiliate_link'] ?? null,
            'status' => $data['status'],
        ]);

        $this->merchantService->syncCategories($merchant, $data['categories'] ?? []);

        return redirect()
            ->route('admin.merchants.index', ['locale' => app()->getLocale()])
            ->with('status', __('Store updated.'));
    }

    public function toggleStatus(string $locale, Merchant $merchant)
    {
        $merchant->update([
            'status' => $merchant->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('status', __('Store status updated.'));
    }

    private function validateData(Request $request, ?int $merchantId = null): array
    {
        $data = $request->validate([
            'name_en' => ['required', 'string', 'max:255'],
            'name_de' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:merchants,slug'.($merchantId ? ",{$merchantId}" : '')],
            'logo' => ['nullable', 'image', 'max:2048'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],
            'awin_merchant_id' => ['nullable', 'string', 'max:255', 'unique:merchants,awin_merchant_id'.($merchantId ? ",{$merchantId}" : '')],
            'affiliate_link' => ['nullable', 'url', 'max:2048'],
            'status' => ['required', 'in:active,inactive,pending_contract'],
        ]);

        $data['source'] = ($data['awin_merchant_id'] ?? null) ? 'awin' : 'manual';

        return $data;
    }

    private function handleLogoUpload(Request $request): ?string
    {
        if (! $request->hasFile('logo')) {
            return null;
        }

        return $request->file('logo')->store('merchant-logos', 'public');
    }
}
