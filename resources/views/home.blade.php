<x-layouts.site :title="__('Home')">
    <section class="relative overflow-hidden bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900 text-white">
        <div class="pointer-events-none absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 28px 28px;"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-12 text-center sm:px-6 sm:py-14 lg:px-8">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-white ring-1 ring-white/20 backdrop-blur">
                🔥 {{ __('New drops added daily') }}
            </span>
            <h1 class="mt-4 font-display text-2xl font-extrabold tracking-tight sm:text-4xl">{{ __('Shop smarter. Save more.') }}</h1>
            <p class="mx-auto mt-3 max-w-2xl text-sm text-primary-100 sm:text-base">
                {{ __('Discover verified discount deals from your favorite fashion, shoe, and beauty stores.') }}
            </p>
            <form action="{{ route('stores.index') }}" method="GET" class="mx-auto mt-6 flex max-w-lg gap-2 rounded-xl bg-white/10 p-1 ring-1 ring-white/20 backdrop-blur">
                <input type="text" name="q" placeholder="{{ __('Search stores...') }}"
                       class="w-full rounded-lg border-0 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm placeholder:text-gray-400 focus:ring-2 focus:ring-white">
                <button type="submit" class="shrink-0 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-primary-700 transition-colors hover:bg-primary-50">{{ __('Search') }}</button>
            </form>
            <div class="mx-auto mt-5 flex max-w-lg flex-wrap items-center justify-center gap-x-5 gap-y-1.5 text-xs text-primary-100 sm:text-sm">
                <span class="inline-flex items-center gap-1.5">✅ {{ __('Verified stores') }}</span>
                <span class="inline-flex items-center gap-1.5">🏷️ {{ __('Deep discounts') }}</span>
                <span class="inline-flex items-center gap-1.5">🆓 {{ __('Always free') }}</span>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <h2 class="mb-4 font-display text-lg font-bold text-gray-900 dark:text-gray-100">{{ __('Categories') }}</h2>
        @php
            $categoryColors = [
                'from-rose-100 to-rose-50 dark:from-rose-900/40 dark:to-rose-900/10 ring-rose-100 dark:ring-rose-800/40',
                'from-sky-100 to-sky-50 dark:from-sky-900/40 dark:to-sky-900/10 ring-sky-100 dark:ring-sky-800/40',
                'from-amber-100 to-amber-50 dark:from-amber-900/40 dark:to-amber-900/10 ring-amber-100 dark:ring-amber-800/40',
                'from-pink-100 to-pink-50 dark:from-pink-900/40 dark:to-pink-900/10 ring-pink-100 dark:ring-pink-800/40',
                'from-violet-100 to-violet-50 dark:from-violet-900/40 dark:to-violet-900/10 ring-violet-100 dark:ring-violet-800/40',
            ];
        @endphp
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
            @forelse($categories as $i => $category)
                <a href="{{ route('stores.index', ['category' => $category->id]) }}"
                   class="card group flex flex-col items-center gap-3 p-5 text-center transition-card hover:-translate-y-0.5">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br text-2xl ring-1 ring-inset transition-transform group-hover:scale-105 {{ $categoryColors[$i % count($categoryColors)] }}">{{ $category->icon ?? '🏷️' }}</span>
                    <span class="text-sm font-display font-semibold text-gray-700 dark:text-gray-200">{{ $category->name() }}</span>
                </a>
            @empty
                @for($i = 0; $i < 5; $i++)
                    <div class="skeleton h-32"></div>
                @endfor
            @endforelse
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <h2 class="mb-4 font-display text-lg font-bold text-gray-900 dark:text-gray-100">{{ __('Biggest discounts') }}</h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($featuredOffers as $offer)
                <x-offer-card :offer="$offer" />
            @empty
                @for($i = 0; $i < 4; $i++)
                    <x-skeleton-card />
                @endfor
            @endforelse
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <h2 class="mb-4 font-display text-lg font-bold text-gray-900 dark:text-gray-100">{{ __('Latest offers') }}</h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($latestOffers as $offer)
                <x-offer-card :offer="$offer" />
            @empty
                <p class="col-span-full text-sm text-gray-500 dark:text-gray-400">{{ __('No offers yet. Check back soon!') }}</p>
            @endforelse
        </div>
    </section>
</x-layouts.site>
