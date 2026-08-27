<x-layouts.admin :title="__('Edit offer')">
    <form method="POST" action="{{ route('admin.offers.update', ['offer' => $offer]) }}" class="card max-w-2xl space-y-4 p-6">
        @csrf @method('PUT')

        <div>
            <label class="text-xs font-semibold text-gray-500">{{ __('Title (EN)') }}</label>
            <input type="text" name="title_en" value="{{ $offer->title_en }}" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
        </div>
        <div>
            <label class="text-xs font-semibold text-gray-500">{{ __('Title (DE)') }}</label>
            <input type="text" name="title_de" value="{{ $offer->title_de }}" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
        </div>
        <div>
            <label class="text-xs font-semibold text-gray-500">{{ __('Description (EN)') }}</label>
            <textarea name="description_en" rows="3" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">{{ $offer->description_en }}</textarea>
        </div>
        <div>
            <label class="text-xs font-semibold text-gray-500">{{ __('Description (DE)') }}</label>
            <textarea name="description_de" rows="3" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">{{ $offer->description_de }}</textarea>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="text-xs font-semibold text-gray-500">{{ __('Discount value') }}</label>
                <input type="number" step="0.01" name="discount_value" value="{{ $offer->discount_value }}" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-500">{{ __('Discount type') }}</label>
                <select name="discount_type" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
                    <option value="percentage" @selected($offer->discount_type === 'percentage')>{{ __('Percentage') }}</option>
                    <option value="fixed" @selected($offer->discount_type === 'fixed')>{{ __('Fixed amount') }}</option>
                </select>
            </div>
        </div>
        <div>
            <label class="text-xs font-semibold text-gray-500">{{ __('Category') }}</label>
            <select name="category_id" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
                <option value="">{{ __('— use store category —') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected($offer->category_id === $category->id)>{{ $category->name_en }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-semibold text-gray-500">{{ __('Coupon code') }}</label>
            <input type="text" name="coupon_code" value="{{ $offer->coupon_code }}" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
        </div>
        <div>
            <label class="text-xs font-semibold text-gray-500">{{ __('Deal-specific affiliate URL (optional, falls back to store link)') }}</label>
            <input type="url" name="affiliate_url" value="{{ $offer->affiliate_url }}" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
        </div>
        <div>
            <label class="text-xs font-semibold text-gray-500">{{ __('Expires at') }}</label>
            <input type="datetime-local" name="expires_at" value="{{ $offer->expires_at?->format('Y-m-d\TH:i') }}" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_featured" value="1" @checked($offer->is_featured) id="is_featured" class="rounded border-gray-300">
            <label for="is_featured" class="text-sm">{{ __('Featured / pinned') }}</label>
        </div>

        <div class="flex flex-wrap items-center gap-2 border-t border-gray-200 pt-4 text-xs dark:border-gray-800">
            <span class="inline-flex items-center rounded-full bg-primary-50 px-2 py-0.5 font-semibold text-primary-700 dark:bg-primary-900/30 dark:text-primary-300">{{ __(ucfirst($offer->deal_type)) }}</span>
            @if($offer->source === 'awin')
                <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 font-semibold text-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ __('Source: Awin') }}</span>
            @endif
            @if($offer->needs_review)
                <span class="inline-flex items-center rounded-full bg-urgent-50 px-2 py-0.5 font-semibold text-urgent-700 dark:bg-urgent-700/20 dark:text-urgent-300">{{ __('Needs review') }}</span>
            @endif
            @if($offer->resolvedAffiliateUrl())
                <a href="{{ $offer->resolvedAffiliateUrl() }}" target="_blank" rel="noopener" class="text-primary-600 hover:underline">{{ __('Test link') }}</a>
            @endif
        </div>

        <button class="btn-cta">{{ __('Save changes') }}</button>
    </form>
</x-layouts.admin>
