<x-layouts.site :title="__('Stores')">
    <section class="border-b border-gray-100 bg-white py-10 dark:border-gray-800 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="font-display text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('All stores') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Browse verified partner stores and their active discounts.') }}</p>

            <form method="GET" class="mt-5 flex flex-col gap-3 sm:flex-row">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Search stores...') }}"
                       class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 sm:max-w-sm">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <button class="btn-cta shrink-0 px-5 text-sm">{{ __('Search') }}</button>
            </form>

            <div class="mt-5 -mx-4 flex gap-2 overflow-x-auto px-4 pb-1 sm:mx-0 sm:flex-wrap sm:px-0">
                <a href="{{ route('stores.index', array_filter(['q' => request('q')])) }}"
                   class="inline-flex shrink-0 items-center gap-1.5 rounded-full border px-3.5 py-1.5 text-xs font-semibold transition-colors {{ ! request('category') ? 'border-primary-600 bg-primary-600 text-white' : 'border-gray-200 text-gray-600 hover:border-primary-300 hover:text-primary-600 dark:border-gray-700 dark:text-gray-300' }}">
                    {{ __('All categories') }}
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('stores.index', array_filter(['q' => request('q'), 'category' => $category->id])) }}"
                       class="inline-flex shrink-0 items-center gap-1.5 rounded-full border px-3.5 py-1.5 text-xs font-semibold transition-colors {{ request('category') == $category->id ? 'border-primary-600 bg-primary-600 text-white' : 'border-gray-200 text-gray-600 hover:border-primary-300 hover:text-primary-600 dark:border-gray-700 dark:text-gray-300' }}">
                        <x-category-icon :slug="$category->slug" class="h-3.5 w-3.5" />
                        {{ $category->name() }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($merchants as $merchant)
                <a href="{{ route('stores.show', ['merchant' => $merchant->slug]) }}" class="card group flex items-center gap-4 p-4 transition-card hover:-translate-y-0.5">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-700">
                        @if($merchant->logo)
                            <img src="{{ Storage::url($merchant->logo) }}" class="h-full w-full object-contain">
                        @else
                            <span class="text-xl font-display font-bold text-gray-400">{{ mb_substr($merchant->name(), 0, 1) }}</span>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate font-display font-semibold text-gray-900 dark:text-gray-100">{{ $merchant->name() }}</p>
                        <p class="mt-1 inline-flex items-center gap-1 rounded-full bg-discount-50 px-2 py-0.5 text-xs font-semibold text-discount-700 dark:bg-discount-800/30 dark:text-discount-300">
                            {{ trans_choice(':count offer|:count offers', $merchant->publishedOffers()->count(), ['count' => $merchant->publishedOffers()->count()]) }}
                        </p>
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-gray-300 transition-transform group-hover:translate-x-0.5 group-hover:text-primary-500 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
            @empty
                <p class="col-span-full text-sm text-gray-500 dark:text-gray-400">{{ __('No stores found.') }}</p>
            @endforelse
        </div>

        <div class="mt-8">{{ $merchants->links() }}</div>
    </div>
</x-layouts.site>
