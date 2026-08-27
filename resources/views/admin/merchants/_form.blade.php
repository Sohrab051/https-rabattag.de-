@csrf
@if($merchant->exists)
    @method('PUT')
@endif

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <label class="text-xs font-semibold text-gray-500">{{ __('Name (EN)') }}</label>
        <input type="text" name="name_en" id="name_en" value="{{ old('name_en', $merchant->name_en) }}" required
               oninput="if (!document.getElementById('slug').dataset.touched) { document.getElementById('slug').value = this.value.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, ''); }"
               class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
    </div>
    <div>
        <label class="text-xs font-semibold text-gray-500">{{ __('Name (DE)') }}</label>
        <input type="text" name="name_de" value="{{ old('name_de', $merchant->name_de) }}" required class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
    </div>
    <div>
        <label class="text-xs font-semibold text-gray-500">{{ __('Slug') }}</label>
        <input type="text" name="slug" id="slug" value="{{ old('slug', $merchant->slug) }}" oninput="this.dataset.touched = '1'" required class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
    </div>
    <div>
        <label class="text-xs font-semibold text-gray-500">{{ __('Logo') }}</label>
        <input type="file" name="logo" accept="image/*" class="mt-1 w-full text-sm">
        @if($merchant->logo)
            <img src="{{ Storage::url($merchant->logo) }}" alt="" class="mt-2 h-10 w-10 rounded-lg object-cover">
        @endif
    </div>

    <div class="sm:col-span-2">
        <label class="text-xs font-semibold text-gray-500">{{ __('Categories') }}</label>
        <div class="mt-1 flex flex-wrap gap-3">
            @foreach($categories as $category)
                <label class="flex items-center gap-2 rounded-lg border border-gray-300 px-3 py-1.5 text-sm dark:border-gray-700">
                    <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                           @checked(in_array($category->id, old('categories', $merchant->categories->pluck('id')->all())))
                           class="rounded border-gray-300">
                    {{ $category->name_en }}
                </label>
            @endforeach
        </div>
        <p class="mt-1 text-xs text-gray-500">{{ __('The first category checked becomes the primary category.') }}</p>
    </div>

    <div>
        <label class="text-xs font-semibold text-gray-500">{{ __('Awin advertiser ID') }}</label>
        <input type="text" name="awin_merchant_id" value="{{ old('awin_merchant_id', $merchant->awin_merchant_id) }}" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
    </div>
    <div>
        <label class="text-xs font-semibold text-gray-500">{{ __('Source') }}</label>
        <div class="mt-1">
            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                {{ ucfirst($merchant->source ?? 'manual') }}
            </span>
            <span class="text-xs text-gray-500">{{ __('Set automatically from the Awin advertiser ID.') }}</span>
        </div>
    </div>
    <div class="sm:col-span-2">
        <label class="text-xs font-semibold text-gray-500">{{ __('Affiliate link') }}</label>
        <input type="url" name="affiliate_link" value="{{ old('affiliate_link', $merchant->affiliate_link) }}" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
    </div>
    <div>
        <label class="text-xs font-semibold text-gray-500">{{ __('Status') }}</label>
        <select name="status" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
            @foreach(['active', 'inactive', 'pending_contract'] as $status)
                <option value="{{ $status }}" @selected(old('status', $merchant->status ?? 'pending_contract') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-xs font-semibold text-gray-500">{{ __('Last synced') }}</label>
        <p class="mt-1 text-sm text-gray-500">{{ $merchant->last_synced_at?->format('d.m.Y H:i') ?? __('Never') }}</p>
    </div>
</div>

<div class="mt-6 flex gap-3 border-t border-gray-200 pt-6 dark:border-gray-800">
    <button type="submit" class="btn-cta">{{ $merchant->exists ? __('Save changes') : __('Create store') }}</button>
    <a href="{{ route('admin.merchants.index') }}" class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold dark:border-gray-600">{{ __('Cancel') }}</a>
</div>
