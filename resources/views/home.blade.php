<x-layouts.site :title="__('Home')">
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900 text-white">
        <div class="pointer-events-none absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 28px 28px;"></div>
        <div class="relative mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 sm:py-16 lg:grid-cols-[1.3fr_1fr] lg:items-center lg:px-8">
            <div class="text-center lg:text-left">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-white ring-1 ring-white/20 backdrop-blur">
                    🔥 {{ __('New drops added daily') }}
                </span>
                <h1 class="mt-4 font-display text-2xl font-extrabold tracking-tight sm:text-4xl lg:text-5xl">{{ __('Shop smarter. Save more.') }}</h1>
                <p class="mx-auto mt-3 max-w-2xl text-sm text-primary-100 sm:text-base lg:mx-0">
                    {{ __('Discover verified discount deals from your favorite fashion, shoe, and beauty stores.') }}
                </p>
                <form action="{{ route('stores.index') }}" method="GET" class="mx-auto mt-6 flex max-w-lg gap-2 rounded-xl bg-white/10 p-1 ring-1 ring-white/20 backdrop-blur lg:mx-0">
                    <input type="text" name="q" placeholder="{{ __('Search stores...') }}"
                           class="w-full rounded-lg border-0 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm placeholder:text-gray-400 focus:ring-2 focus:ring-white">
                    <button type="submit" class="shrink-0 rounded-lg bg-discount-500 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-discount-600">{{ __('Search') }}</button>
                </form>
                <div class="mx-auto mt-5 flex max-w-lg flex-wrap items-center justify-center gap-x-5 gap-y-1.5 text-xs text-primary-100 sm:text-sm lg:mx-0 lg:justify-start">
                    <span class="inline-flex items-center gap-1.5">✅ {{ __('Verified stores') }}</span>
                    <span class="inline-flex items-center gap-1.5">🏷️ {{ __('Deep discounts') }}</span>
                    <span class="inline-flex items-center gap-1.5">🆓 {{ __('Always free') }}</span>
                </div>
            </div>

            <div class="mx-auto w-full max-w-sm rounded-2xl bg-white/95 p-5 text-gray-900 shadow-card-hover backdrop-blur dark:bg-gray-900/95 dark:text-gray-100">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('Right now on Rabattag') }}</p>
                <div class="mt-3 grid grid-cols-2 gap-3">
                    <div class="rounded-xl bg-primary-50 p-3 dark:bg-primary-900/20">
                        <p class="text-2xl font-display font-extrabold text-primary-600 dark:text-primary-300">{{ number_format($stats['offers']) }}</p>
                        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ __('Active offers') }}</p>
                    </div>
                    <div class="rounded-xl bg-discount-50 p-3 dark:bg-discount-800/20">
                        <p class="text-2xl font-display font-extrabold text-discount-600 dark:text-discount-300">{{ number_format($stats['stores']) }}</p>
                        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ __('Verified stores') }}</p>
                    </div>
                </div>
                <a href="{{ route('stores.index') }}" class="btn-cta mt-4 w-full justify-center">
                    {{ __('Browse all stores') }}
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Categories --}}
    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="font-display text-lg font-bold text-gray-900 dark:text-gray-100">{{ __('Browse categories') }}</h2>
            <a href="{{ route('stores.index') }}" class="text-sm font-semibold text-primary-600 hover:underline dark:text-primary-400">{{ __('View all') }}</a>
        </div>
        <div class="-mx-4 flex gap-4 overflow-x-auto px-4 pb-2 sm:mx-0 sm:grid sm:grid-cols-3 sm:gap-3 sm:overflow-visible sm:px-0 lg:grid-cols-5">
            @forelse($categories as $category)
                <a href="{{ route('stores.index', ['category' => $category->id]) }}"
                   class="group flex w-24 shrink-0 flex-col items-center gap-2 text-center sm:w-auto">
                    <span class="flex h-16 w-16 items-center justify-center rounded-full bg-white text-2xl shadow-card ring-1 ring-gray-100 transition-transform group-hover:-translate-y-0.5 group-hover:shadow-card-hover dark:bg-gray-900 dark:ring-gray-800">
                        {{ $category->icon ?? '🏷️' }}
                    </span>
                    <span class="text-xs font-display font-semibold text-gray-700 dark:text-gray-200">{{ $category->name() }}</span>
                </a>
            @empty
                @for($i = 0; $i < 5; $i++)
                    <div class="skeleton h-24 w-24 shrink-0 rounded-full sm:w-auto"></div>
                @endfor
            @endforelse
        </div>
    </section>

    {{-- Featured offers --}}
    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="font-display text-lg font-bold text-gray-900 dark:text-gray-100">{{ __('Biggest discounts') }}</h2>
            <a href="{{ route('stores.index') }}" class="text-sm font-semibold text-primary-600 hover:underline dark:text-primary-400">{{ __('View all') }}</a>
        </div>
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

    {{-- How it works --}}
    <section class="bg-white py-12 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="font-display text-xl font-bold text-gray-900 dark:text-gray-100">{{ __('Start saving in 3 simple steps') }}</h2>
                <p class="mx-auto mt-2 max-w-xl text-sm text-gray-500 dark:text-gray-400">{{ __('Getting a discount on your favorite stores has never been easier.') }}</p>
            </div>
            <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-3">
                @foreach([
                    ['icon' => '🔍', 'title' => __('Browse & choose'), 'text' => __('Explore verified deals from over :count partner stores.', ['count' => $stats['stores']])],
                    ['icon' => '🏷️', 'title' => __('Activate the offer'), 'text' => __('Click through and the discount is applied automatically or via code.')],
                    ['icon' => '🛍️', 'title' => __('Shop & save'), 'text' => __('Check out as usual and enjoy your discount, every time.')],
                ] as $i => $step)
                    <div class="relative rounded-2xl border border-gray-100 bg-surface-light p-6 text-center dark:border-gray-800 dark:bg-gray-950">
                        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-primary-600 text-xl text-white shadow-sm">{{ $step['icon'] }}</span>
                        <p class="mt-3 font-display text-sm font-bold text-gray-900 dark:text-gray-100">{{ $i + 1 }}. {{ $step['title'] }}</p>
                        <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">{{ $step['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Latest offers --}}
    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="font-display text-lg font-bold text-gray-900 dark:text-gray-100">{{ __('Latest offers') }}</h2>
            <a href="{{ route('stores.index') }}" class="text-sm font-semibold text-primary-600 hover:underline dark:text-primary-400">{{ __('View all') }}</a>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($latestOffers as $offer)
                <x-offer-card :offer="$offer" />
            @empty
                <p class="col-span-full text-sm text-gray-500 dark:text-gray-400">{{ __('No offers yet. Check back soon!') }}</p>
            @endforelse
        </div>
    </section>

    {{-- Trust strip --}}
    <section class="bg-gradient-to-r from-primary-700 to-primary-900 py-10 text-white">
        <div class="mx-auto grid max-w-5xl grid-cols-1 gap-6 px-4 text-center sm:grid-cols-3 sm:px-6 lg:px-8">
            <div>
                <p class="font-display text-3xl font-extrabold">{{ number_format($stats['stores']) }}+</p>
                <p class="mt-1 text-xs uppercase tracking-wide text-primary-200">{{ __('Verified stores') }}</p>
            </div>
            <div>
                <p class="font-display text-3xl font-extrabold text-discount-300">{{ number_format($stats['offers']) }}+</p>
                <p class="mt-1 text-xs uppercase tracking-wide text-primary-200">{{ __('Active offers') }}</p>
            </div>
            <div>
                <p class="font-display text-3xl font-extrabold">100%</p>
                <p class="mt-1 text-xs uppercase tracking-wide text-primary-200">{{ __('Free, always') }}</p>
            </div>
        </div>
    </section>
</x-layouts.site>
