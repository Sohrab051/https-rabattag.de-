<x-layouts.site :title="__('Home')">
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-primary-800 via-primary-700 to-primary-900 text-white">
        <div class="pointer-events-none absolute -top-32 -left-20 h-96 w-96 rounded-full bg-primary-400/30 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 right-0 h-80 w-80 rounded-full bg-discount-400/20 blur-3xl"></div>
        <div class="pointer-events-none absolute inset-0 opacity-[0.07]" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 28px 28px;"></div>
        <div class="relative mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 sm:py-20 lg:grid-cols-[1.3fr_1fr] lg:items-center lg:px-8">
            <div class="text-center lg:text-left">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-gradient-to-r from-discount-500 to-discount-400 px-3.5 py-1.5 text-xs font-bold uppercase tracking-wide text-primary-900 shadow-glow">
                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l1.2 3.6L17 7l-3.8 1.4L12 12l-1.2-3.6L7 7l3.8-1.4L12 2z"/><path d="M5 14l.7 2 2 .7-2 .7-.7 2-.7-2-2-.7 2-.7.7-2z"/><path d="M19 14l.6 1.7L21 16l-1.4.6L19 18l-.6-1.4L17 16l1.4-.3.6-1.7z"/></svg>
                    {{ __('New drops added daily') }}
                </span>
                <h1 class="mt-6 font-display text-4xl italic leading-[1.1] tracking-tight text-white sm:text-5xl lg:text-6xl">{{ __('Shop smarter. Save more.') }}</h1>
                <p class="mx-auto mt-4 max-w-2xl text-sm text-primary-200 sm:text-base lg:mx-0">
                    {{ __('Discover verified discount deals from your favorite fashion, shoe, and beauty stores.') }}
                </p>
                <form action="{{ route('stores.index') }}" method="GET" class="mx-auto mt-7 flex max-w-lg gap-2 rounded-btn bg-white/10 p-1.5 ring-1 ring-white/20 backdrop-blur lg:mx-0">
                    <input type="text" name="q" placeholder="{{ __('Search stores...') }}"
                           class="w-full rounded-btn border-0 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm placeholder:text-gray-400 focus:ring-2 focus:ring-discount-400">
                    <button type="submit" class="shrink-0 rounded-btn bg-gradient-to-r from-discount-500 to-discount-400 px-5 py-2.5 text-sm font-bold uppercase tracking-wide text-primary-900 transition-all hover:brightness-110">{{ __('Search') }}</button>
                </form>
                <div class="mx-auto mt-6 flex max-w-lg flex-wrap items-center justify-center gap-x-5 gap-y-2 text-xs font-semibold text-primary-200 sm:text-sm lg:mx-0 lg:justify-start">
                    <span class="inline-flex items-center gap-1.5"><svg class="h-4 w-4 text-discount-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg> {{ __('Verified stores') }}</span>
                    <span class="inline-flex items-center gap-1.5"><svg class="h-4 w-4 text-discount-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20.59 13.41L11 3.83A2 2 0 009.59 3.24H4a1 1 0 00-1 1v5.59a2 2 0 00.59 1.41l9.58 9.58a2 2 0 002.83 0l4.59-4.59a2 2 0 000-2.82z"/><circle cx="7.5" cy="7.5" r="1.2" fill="currentColor" stroke="none"/></svg> {{ __('Deep discounts') }}</span>
                    <span class="inline-flex items-center gap-1.5"><svg class="h-4 w-4 text-discount-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 12v8H4v-8M2 8h20v4H2zM12 8v12M12 8c-1.5-3-6-3-6-.5S9 8 12 8zM12 8c1.5-3 6-3 6-.5S15 8 12 8z"/></svg> {{ __('Always free') }}</span>
                </div>
            </div>

            <div class="mx-auto w-full max-w-sm rounded-card border border-white/10 bg-white/95 p-5 text-gray-900 shadow-card-hover backdrop-blur-md dark:bg-primary-800/80 dark:text-gray-100">
                <p class="text-xs font-bold uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('Right now on Rabattag') }}</p>
                <div class="mt-3 grid grid-cols-2 gap-3">
                    <div class="rounded-2xl bg-primary-50 p-3 dark:bg-primary-900/40">
                        <p class="text-2xl font-display text-primary-900 dark:text-white">{{ number_format($stats['offers']) }}</p>
                        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ __('Active offers') }}</p>
                    </div>
                    <div class="rounded-2xl bg-discount-50 p-3 dark:bg-discount-800/20">
                        <p class="text-2xl font-display text-discount-700 dark:text-discount-300">{{ number_format($stats['stores']) }}</p>
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

    {{-- Categories rail --}}
    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="font-display text-2xl italic tracking-tight text-gray-900 dark:text-gray-100">{{ __('Browse categories') }}</h2>
            <a href="{{ route('stores.index') }}" class="text-sm font-semibold text-primary-700 hover:text-discount-600 dark:text-primary-300 dark:hover:text-discount-400">{{ __('View all') }}</a>
        </div>
        <div class="-mx-4 flex gap-4 overflow-x-auto px-4 pb-2 sm:mx-0 sm:grid sm:grid-cols-3 sm:gap-3 sm:overflow-visible sm:px-0 lg:grid-cols-5">
            @forelse($categories as $category)
                <a href="{{ route('stores.index', ['category' => $category->id]) }}"
                   class="group flex w-24 shrink-0 flex-col items-center gap-2 text-center sm:w-auto">
                    <span class="flex h-16 w-16 items-center justify-center rounded-full border border-primary-200 bg-white text-primary-700 shadow-card transition-all duration-200 group-hover:-translate-y-0.5 group-hover:border-discount-400 group-hover:text-discount-600 dark:border-primary-400/20 dark:bg-primary-800/60 dark:text-primary-200 dark:group-hover:text-discount-400">
                        <x-category-icon :slug="$category->slug" class="h-6 w-6" />
                    </span>
                    <span class="text-xs font-semibold text-gray-700 dark:text-gray-200">{{ $category->name() }}</span>
                </a>
            @empty
                @for($i = 0; $i < 5; $i++)
                    <div class="skeleton h-24 w-24 shrink-0 rounded-full sm:w-auto"></div>
                @endfor
            @endforelse
        </div>
    </section>

    {{-- Sidebar + main content --}}
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-[280px_1fr]">
            {{-- Sidebar --}}
            <aside class="order-2 space-y-6 lg:order-1">
                {{-- Trending Deals --}}
                <div class="card p-4">
                    <h2 class="mb-3 font-display text-sm font-bold text-gray-900 dark:text-gray-100">{{ __('Trending Deals') }}</h2>
                    <ul class="space-y-3">
                        @forelse($trendingOffers as $offer)
                            <li>
                                <a href="{{ route('stores.show', ['merchant' => $offer->merchant->slug]) }}" class="flex items-center gap-2.5 group">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-gradient-to-br from-primary-100 to-primary-50 ring-1 ring-inset ring-primary-100 dark:from-primary-900/40 dark:to-primary-900/10 dark:ring-primary-800/40">
                                        @if($offer->merchant->logo)
                                            <img src="{{ Storage::url($offer->merchant->logo) }}" alt="{{ $offer->merchant->name() }}" class="h-full w-full object-contain">
                                        @else
                                            <span class="text-xs font-display font-bold text-primary-600 dark:text-primary-300">{{ mb_substr($offer->merchant->name(), 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-medium text-gray-800 group-hover:text-primary-600 dark:text-gray-200 dark:group-hover:text-primary-400">{{ $offer->merchant->name() }}</p>
                                        <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ $offer->title() }}</p>
                                    </div>
                                    @if($offer->discount_value)
                                        <span class="badge-discount shrink-0">-{{ rtrim(rtrim(number_format($offer->discount_value, 2), '0'), '.') }}%</span>
                                    @endif
                                </a>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500 dark:text-gray-400">{{ __('No offers yet. Check back soon!') }}</li>
                        @endforelse
                    </ul>
                </div>

                {{-- Newly Added --}}
                <div class="card p-4">
                    <h2 class="mb-3 font-display text-sm font-bold text-gray-900 dark:text-gray-100">{{ __('Newly added') }}</h2>
                    <ul class="space-y-3">
                        @forelse($newestOffers as $offer)
                            <li>
                                <a href="{{ route('stores.show', ['merchant' => $offer->merchant->slug]) }}" class="flex items-center justify-between gap-2 group">
                                    <span class="truncate text-sm font-medium text-gray-800 group-hover:text-primary-600 dark:text-gray-200 dark:group-hover:text-primary-400">{{ $offer->merchant->name() }}</span>
                                    @if($offer->discount_value)
                                        <span class="badge-discount shrink-0">-{{ rtrim(rtrim(number_format($offer->discount_value, 2), '0'), '.') }}%</span>
                                    @endif
                                </a>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500 dark:text-gray-400">{{ __('No offers yet. Check back soon!') }}</li>
                        @endforelse
                    </ul>
                </div>

                {{-- Top Partner Brands --}}
                <div class="card p-4">
                    <h2 class="mb-3 font-display text-sm font-bold text-gray-900 dark:text-gray-100">{{ __('Top Partner Brands') }}</h2>
                    <ul class="space-y-3">
                        @forelse($topMerchants as $merchant)
                            <li>
                                <a href="{{ route('stores.show', ['merchant' => $merchant->slug]) }}" class="flex items-center gap-2.5 group">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-gradient-to-br from-primary-100 to-primary-50 ring-1 ring-inset ring-primary-100 dark:from-primary-900/40 dark:to-primary-900/10 dark:ring-primary-800/40">
                                        @if($merchant->logo)
                                            <img src="{{ Storage::url($merchant->logo) }}" alt="{{ $merchant->name() }}" class="h-full w-full object-contain">
                                        @else
                                            <span class="text-xs font-display font-bold text-primary-600 dark:text-primary-300">{{ mb_substr($merchant->name(), 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-medium text-gray-800 group-hover:text-primary-600 dark:text-gray-200 dark:group-hover:text-primary-400">{{ $merchant->name() }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ trans_choice(':count offer|:count offers', $merchant->published_offers_count, ['count' => $merchant->published_offers_count]) }}</p>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500 dark:text-gray-400">{{ __('No stores found.') }}</li>
                        @endforelse
                    </ul>
                </div>

                {{-- Newsletter --}}
                <div class="card p-4">
                    <h2 class="mb-1 font-display text-sm font-bold text-gray-900 dark:text-gray-100">{{ __('Get Weekly Deals') }}</h2>
                    <p class="mb-3 text-xs text-gray-500 dark:text-gray-400">{{ __('The best discounts, delivered to your inbox every week.') }}</p>

                    @if(session('newsletter_status'))
                        <div class="mb-3 rounded-lg bg-discount-50 px-3 py-2 text-xs font-medium text-discount-800 dark:bg-discount-800/30 dark:text-discount-200">
                            {{ session('newsletter_status') }}
                        </div>
                    @endif

                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="space-y-2">
                        @csrf
                        <input type="email" name="email" required placeholder="{{ __('Your email address') }}"
                               class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800">
                        @error('email')
                            <p class="text-xs font-medium text-urgent-600 dark:text-urgent-400">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="btn-cta w-full justify-center">{{ __('Subscribe') }}</button>
                    </form>
                </div>
            </aside>

            {{-- Main content --}}
            <div class="order-1 space-y-10 lg:order-2">
                {{-- Featured offers --}}
                <section>
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="font-display text-lg font-bold text-gray-900 dark:text-gray-100">{{ __('Biggest discounts') }}</h2>
                        <a href="{{ route('stores.index') }}" class="text-sm font-semibold text-primary-600 hover:underline dark:text-primary-400">{{ __('View all') }}</a>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
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
                <section class="rounded-card bg-white py-10 dark:bg-primary-800/40">
                    <div class="text-center">
                        <h2 class="font-display text-3xl italic tracking-tight text-gray-900 dark:text-gray-100">{{ __('Start saving in 3 simple steps') }}</h2>
                        <p class="mx-auto mt-2 max-w-xl text-sm text-gray-500 dark:text-gray-400">{{ __('Getting a discount on your favorite stores has never been easier.') }}</p>
                    </div>
                    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-3">
                        @foreach([
                            ['icon' => 'search', 'title' => __('Browse & choose'), 'text' => __('Explore verified deals from over :count partner stores.', ['count' => $stats['stores']])],
                            ['icon' => 'tag', 'title' => __('Activate the offer'), 'text' => __('Click through and the discount is applied automatically or via code.')],
                            ['icon' => 'bag', 'title' => __('Shop & save'), 'text' => __('Check out as usual and enjoy your discount, every time.')],
                        ] as $i => $step)
                            <div class="relative rounded-card border border-gray-100 bg-surface-light p-6 text-center dark:border-primary-400/10 dark:bg-primary-900/40">
                                <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-discount-500 to-discount-400 text-primary-900 shadow-glow">
                                    @if($step['icon'] === 'search')
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
                                    @elseif($step['icon'] === 'tag')
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20.59 13.41L11 3.83A2 2 0 009.59 3.24H4a1 1 0 00-1 1v5.59a2 2 0 00.59 1.41l9.58 9.58a2 2 0 002.83 0l4.59-4.59a2 2 0 000-2.82z"/><circle cx="7.5" cy="7.5" r="1.2" fill="currentColor" stroke="none"/></svg>
                                    @else
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 7h12l1 13H5L6 7z"/><path d="M9 7a3 3 0 016 0"/></svg>
                                    @endif
                                </span>
                                <p class="mt-3 font-display text-sm font-bold text-gray-900 dark:text-gray-100">{{ $i + 1 }}. {{ $step['title'] }}</p>
                                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">{{ $step['text'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- Latest offers --}}
                <section>
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="font-display text-lg font-bold text-gray-900 dark:text-gray-100">{{ __('Latest offers') }}</h2>
                        <a href="{{ route('stores.index') }}" class="text-sm font-semibold text-primary-600 hover:underline dark:text-primary-400">{{ __('View all') }}</a>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @forelse($latestOffers as $offer)
                            <x-offer-card :offer="$offer" />
                        @empty
                            <p class="col-span-full text-sm text-gray-500 dark:text-gray-400">{{ __('No offers yet. Check back soon!') }}</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </div>

    {{-- Trust strip --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-primary-800 via-primary-700 to-primary-900 py-12 text-white">
        <div class="pointer-events-none absolute -top-16 left-1/3 h-72 w-72 rounded-full bg-primary-400/20 blur-3xl"></div>
        <div class="relative mx-auto grid max-w-5xl grid-cols-1 gap-6 px-4 text-center sm:grid-cols-3 sm:px-6 lg:px-8">
            <div>
                <p class="font-display text-4xl">{{ number_format($stats['stores']) }}+</p>
                <p class="mt-1 text-xs font-bold uppercase tracking-wide text-primary-300">{{ __('Verified stores') }}</p>
            </div>
            <div>
                <p class="font-display text-4xl text-discount-400">{{ number_format($stats['offers']) }}+</p>
                <p class="mt-1 text-xs font-bold uppercase tracking-wide text-primary-300">{{ __('Active offers') }}</p>
            </div>
            <div>
                <p class="font-display text-4xl">100%</p>
                <p class="mt-1 text-xs font-bold uppercase tracking-wide text-primary-300">{{ __('Free, always') }}</p>
            </div>
        </div>
    </section>
</x-layouts.site>
