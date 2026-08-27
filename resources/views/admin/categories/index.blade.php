<x-layouts.admin :title="__('Category Management')">
    <div class="mb-4 flex justify-end">
        <a href="{{ route('admin.categories.create') }}" class="btn-cta">{{ __('Add category') }}</a>
    </div>

    @if($errors->any())
        <div class="mb-4 rounded-xl bg-red-50 px-4 py-3 text-sm font-medium text-red-800 dark:bg-red-900/30 dark:text-red-200">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="card overflow-x-auto p-4">
        <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
            <thead>
                <tr class="text-left text-gray-500 dark:text-gray-400">
                    <th class="py-2 pr-4">{{ __('Name') }}</th>
                    <th class="py-2 pr-4">{{ __('Slug') }}</th>
                    <th class="py-2 pr-4">{{ __('Status') }}</th>
                    <th class="py-2 pr-4">{{ __('Merchants') }}</th>
                    <th class="py-2 pr-4">{{ __('Offers') }}</th>
                    <th class="py-2 pr-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($categories as $category)
                    @php($hasDependents = $category->merchants_count > 0 || $category->offers_count > 0)
                    <tr>
                        <td class="py-2 pr-4 font-medium">{{ $category->icon }} {{ $category->name_en }} / {{ $category->name_de }}</td>
                        <td class="py-2 pr-4">{{ $category->slug }}</td>
                        <td class="py-2 pr-4">
                            @if($category->is_active)
                                <span class="inline-flex items-center rounded-full bg-discount-100 px-2 py-0.5 text-[10px] font-semibold text-discount-800 dark:bg-discount-800/30 dark:text-discount-200">{{ __('Active') }}</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">{{ __('Inactive') }}</span>
                            @endif
                        </td>
                        <td class="py-2 pr-4">{{ $category->merchants_count }}</td>
                        <td class="py-2 pr-4">{{ $category->offers_count }}</td>
                        <td class="py-2 pr-4 space-x-2 whitespace-nowrap">
                            <a href="{{ route('admin.categories.edit', ['category' => $category]) }}" class="text-primary-600 hover:underline">{{ __('Edit') }}</a>
                            <form method="POST" action="{{ route('admin.categories.toggle-status', ['category' => $category]) }}" class="inline">
                                @csrf @method('PATCH')
                                <button class="text-gray-500 hover:underline">{{ __('Toggle active') }}</button>
                            </form>
                            @if($hasDependents)
                                <span class="cursor-not-allowed text-gray-400" title="{{ __('This category cannot be deleted because it still has :merchants store(s) and :offers offer(s) assigned to it.', ['merchants' => $category->merchants_count, 'offers' => $category->offers_count]) }}">{{ __('Delete') }}</span>
                            @else
                                <form method="POST" action="{{ route('admin.categories.destroy', ['category' => $category]) }}" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 hover:underline">{{ __('Delete') }}</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.admin>
