@csrf
@if($category->exists)
    @method('PUT')
@endif

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <label class="text-xs font-semibold text-gray-500">{{ __('Name (EN)') }}</label>
        <input type="text" name="name_en" id="name_en" value="{{ old('name_en', $category->name_en) }}" required
               oninput="if (!document.getElementById('slug').dataset.touched) { document.getElementById('slug').value = this.value.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, ''); }"
               class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
    </div>
    <div>
        <label class="text-xs font-semibold text-gray-500">{{ __('Name (DE)') }}</label>
        <input type="text" name="name_de" value="{{ old('name_de', $category->name_de) }}" required class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
    </div>
    <div>
        <label class="text-xs font-semibold text-gray-500">{{ __('Slug') }}</label>
        <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" oninput="this.dataset.touched = '1'" required class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
    </div>
    <div>
        <label class="text-xs font-semibold text-gray-500">{{ __('Icon') }}</label>
        <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" class="mt-1 w-full rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
    </div>
    <div>
        <label class="flex items-center gap-2 text-xs font-semibold text-gray-500">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->exists ? $category->is_active : true)) class="rounded border-gray-300">
            {{ __('Active') }}
        </label>
    </div>
</div>

<div class="mt-6 flex gap-3 border-t border-gray-200 pt-6 dark:border-gray-800">
    <button type="submit" class="btn-cta">{{ $category->exists ? __('Save changes') : __('Create category') }}</button>
    <a href="{{ route('admin.categories.index') }}" class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold dark:border-gray-600">{{ __('Cancel') }}</a>
</div>
