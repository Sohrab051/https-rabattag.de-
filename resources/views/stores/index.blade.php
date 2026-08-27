<x-layouts.site :title="__('Stores')">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="font-display text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('All stores') }}</h1>

        <form method="GET" class="mt-4 flex flex-wrap gap-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Search stores...') }}"
                   class="rounded-xl border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800">
            <select name="category" class="rounded-xl border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800">
                <option value="">{{ __('All categories') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name() }}</option>
                @endforeach
            </select>
            <button class="btn-cta px-4 text-sm">{{ __('Filter') }}</button>
        </form>

        <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($merchants as $merchant)
                <a href="{{ route('stores.show', ['merchant' => $merchant->slug]) }}" class="card flex items-center gap-4 p-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        @if($merchant->logo)
                            <img src="{{ Storage::url($merchant->logo) }}" class="h-full w-full object-contain">
                        @else
                            <span class="text-xl font-bold text-gray-400">{{ mb_substr($merchant->name(), 0, 1) }}</span>
                        @endif
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $merchant->name() }}</p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ trans_choice(':count offer|:count offers', $merchant->publishedOffers()->count(), ['count' => $merchant->publishedOffers()->count()]) }}
                        </p>
                    </div>
                </a>
            @empty
                <p class="col-span-full text-sm text-gray-500 dark:text-gray-400">{{ __('No stores found.') }}</p>
            @endforelse
        </div>

        <div class="mt-8">{{ $merchants->links() }}</div>
    </div>
</x-layouts.site>
