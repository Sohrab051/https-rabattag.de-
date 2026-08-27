<x-layouts.admin :title="__('Add store & offer')">
    <div x-data="{
        tab: 'en',
        preview: {
            title_en: '', title_de: '',
            merchant_name_en: '', merchant_name_de: '',
            discount_value: '0',
        },
        get title() { return this.tab === 'en' ? this.preview.title_en : this.preview.title_de },
        get merchantName() { return this.tab === 'en' ? this.preview.merchant_name_en : this.preview.merchant_name_de },
    }" class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        <form method="POST" action="{{ route('admin.merchant-offer.store') }}" class="card space-y-6 p-6 lg:col-span-2">
            @csrf

            <div>
                <h2 class="font-display text-lg font-bold">{{ __('Store') }}</h2>
                <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold text-gray-500">{{ __('Use existing store') }}</label>
                        <select name="merchant_id" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
                            <option value="">{{ __('— create new —') }}</option>
                            @foreach($merchants as $merchant)
                                <option value="{{ $merchant->id }}">
                                    {{ $merchant->name_en }}{{ $merchant->source === 'awin' ? ' (Awin' . ($merchant->last_synced_at ? ', synced ' . $merchant->last_synced_at->format('d.m.Y') : '') . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500">{{ __('Category') }}</label>
                        <select name="category_id" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
                            <option value="">—</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name_en }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500">{{ __('Name (EN)') }}</label>
                        <input type="text" name="merchant_name_en" x-model="preview.merchant_name_en" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500">{{ __('Name (DE)') }}</label>
                        <input type="text" name="merchant_name_de" x-model="preview.merchant_name_de" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500">{{ __('Website URL') }}</label>
                        <input type="url" name="website_url" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500">{{ __('Affiliate link (leave blank if pending contract)') }}</label>
                        <input type="url" name="affiliate_link" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500">{{ __('Commission rate %') }}</label>
                        <input type="number" step="0.01" name="commission_rate" value="0" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500">{{ __('Awin advertiser ID (optional)') }}</label>
                        <input type="text" name="awin_merchant_id" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800" placeholder="{{ __('Only for stores linked to an Awin advertiser') }}">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="text-xs font-semibold text-gray-500">{{ __('Description (EN)') }}</label>
                        <textarea name="description_en" rows="2" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800"></textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="text-xs font-semibold text-gray-500">{{ __('Description (DE)') }}</label>
                        <textarea name="description_de" rows="2" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800"></textarea>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6 dark:border-gray-800">
                <h2 class="font-display text-lg font-bold">{{ __('Offer') }}</h2>
                <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold text-gray-500">{{ __('Title (EN)') }}</label>
                        <input type="text" name="title_en" x-model="preview.title_en" required class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500">{{ __('Title (DE)') }}</label>
                        <input type="text" name="title_de" x-model="preview.title_de" required class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="text-xs font-semibold text-gray-500">{{ __('Description (EN)') }}</label>
                        <textarea name="offer_description_en" rows="2" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800"></textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="text-xs font-semibold text-gray-500">{{ __('Description (DE)') }}</label>
                        <textarea name="offer_description_de" rows="2" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800"></textarea>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500">{{ __('Discount value (%)') }}</label>
                        <input type="number" step="0.01" name="discount_value" x-model="preview.discount_value" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500">{{ __('Minimum purchase amount') }}</label>
                        <input type="number" step="0.01" name="min_purchase_amount" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500">{{ __('Starts at') }}</label>
                        <input type="datetime-local" name="starts_at" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500">{{ __('Expires at') }}</label>
                        <input type="datetime-local" name="expires_at" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_featured" value="1" id="is_featured" class="rounded border-gray-300">
                        <label for="is_featured" class="text-sm">{{ __('Featured / pinned') }}</label>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 border-t border-gray-200 pt-6 dark:border-gray-800">
                <button type="submit" name="action" value="draft" class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold dark:border-gray-600">
                    {{ __('Save as draft') }}
                </button>
                <button type="submit" name="action" value="publish" class="btn-cta">
                    {{ __('Publish now') }}
                </button>
            </div>
        </form>

        <div>
            <p class="mb-2 text-xs font-semibold uppercase text-gray-500">{{ __('Live preview') }}</p>
            <div class="mb-3 flex gap-2">
                <button type="button" x-on:click="tab = 'en'" :class="tab === 'en' ? 'bg-primary-600 text-white' : 'bg-gray-100 dark:bg-gray-800'" class="rounded-lg px-3 py-1 text-xs font-semibold">EN</button>
                <button type="button" x-on:click="tab = 'de'" :class="tab === 'de' ? 'bg-primary-600 text-white' : 'bg-gray-100 dark:bg-gray-800'" class="rounded-lg px-3 py-1 text-xs font-semibold">DE</button>
            </div>

            <div class="card flex flex-col overflow-hidden">
                <div class="flex items-center gap-3 p-4 pb-0">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-700">
                        <span class="text-lg font-bold text-gray-400" x-text="(merchantName || '?').charAt(0).toUpperCase()"></span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold" x-text="merchantName || '{{ __('Store name') }}'"></p>
                    </div>
                    <span class="badge-discount shrink-0" x-show="preview.discount_value > 0">
                        -<span x-text="preview.discount_value"></span>%
                    </span>
                </div>
                <div class="p-4">
                    <p class="text-sm font-medium" x-text="title || '{{ __('Offer title') }}'"></p>
                    <div class="btn-cta mt-4 w-full">{{ __('View offer') }}</div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
