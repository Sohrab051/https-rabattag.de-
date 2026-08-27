<x-layouts.admin :title="__('Merchants')">
    <form method="GET" class="mb-4 flex flex-wrap items-center gap-3">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Search by name') }}" class="rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800">
        <select name="status" class="rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800" onchange="this.form.submit()">
            <option value="">{{ __('All statuses') }}</option>
            <option value="active" @selected(request('status') === 'active')>{{ __('Active') }}</option>
            <option value="inactive" @selected(request('status') === 'inactive')>{{ __('Inactive') }}</option>
            <option value="pending_contract" @selected(request('status') === 'pending_contract')>{{ __('Pending contract') }}</option>
        </select>
        <select name="category" class="rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800" onchange="this.form.submit()">
            <option value="">{{ __('All categories') }}</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>{{ $category->name_en }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-semibold dark:border-gray-600">{{ __('Filter') }}</button>
        <a href="{{ route('admin.merchants.create') }}" class="btn-cta ml-auto">{{ __('Add store') }}</a>
    </form>

    <div class="card overflow-x-auto p-4">
        <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
            <thead>
                <tr class="text-left text-gray-500 dark:text-gray-400">
                    <th class="py-2 pr-4">{{ __('Store') }}</th>
                    <th class="py-2 pr-4">{{ __('Category') }}</th>
                    <th class="py-2 pr-4">{{ __('Source') }}</th>
                    <th class="py-2 pr-4">{{ __('Status') }}</th>
                    <th class="py-2 pr-4">{{ __('Offers') }}</th>
                    <th class="py-2 pr-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($merchants as $merchant)
                    <tr>
                        <td class="py-2 pr-4 font-medium">{{ $merchant->name_en }}</td>
                        <td class="py-2 pr-4">{{ $merchant->category?->name_en ?? '—' }}</td>
                        <td class="py-2 pr-4">
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                {{ ucfirst($merchant->source) }}
                            </span>
                        </td>
                        <td class="py-2 pr-4"><x-status-badge :status="$merchant->status" /></td>
                        <td class="py-2 pr-4">{{ $merchant->offers_count ?? $merchant->offers()->count() }}</td>
                        <td class="py-2 pr-4 space-x-2 whitespace-nowrap">
                            <a href="{{ route('admin.merchants.edit', ['merchant' => $merchant]) }}" class="text-primary-600 hover:underline">{{ __('Edit') }}</a>
                            <form method="POST" action="{{ route('admin.merchants.toggle-status', ['merchant' => $merchant]) }}" class="inline">
                                @csrf @method('PATCH')
                                <button class="text-gray-500 hover:underline">{{ __('Toggle active') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $merchants->links() }}</div>
</x-layouts.admin>
