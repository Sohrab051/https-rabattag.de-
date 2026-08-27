<x-layouts.site :title="$merchant->name()">
    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="card relative flex flex-col items-center gap-4 overflow-hidden p-6 sm:flex-row">
            <div class="absolute inset-x-0 top-0 h-24 bg-gradient-to-br from-primary-600 to-primary-800"></div>
            <div class="relative flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl bg-white shadow-md ring-4 ring-white dark:bg-gray-800 dark:ring-gray-900 overflow-hidden">
                @if($merchant->logo)
                    <img src="{{ Storage::url($merchant->logo) }}" class="h-full w-full object-contain">
                @else
                    <span class="text-3xl font-display font-bold text-primary-600">{{ mb_substr($merchant->name(), 0, 1) }}</span>
                @endif
            </div>
            <div class="relative text-center sm:text-left">
                <h1 class="font-display text-2xl font-bold text-white sm:text-gray-900 sm:dark:text-gray-100">{{ $merchant->name() }}</h1>
                @if($merchant->description())
                    <p class="mt-1 text-sm text-primary-50 sm:text-gray-600 sm:dark:text-gray-400">{{ $merchant->description() }}</p>
                @endif
                <span class="badge-discount mt-2">{{ trans_choice(':count active offer|:count active offers', $merchant->publishedOffers->count(), ['count' => $merchant->publishedOffers->count()]) }}</span>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div class="card flex items-center gap-3 p-4">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-600 font-display text-sm font-bold text-white">1</span>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('View the offer') }}</p>
            </div>
            <div class="card flex items-center gap-3 p-4">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-600 font-display text-sm font-bold text-white">2</span>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Get redirected to the store') }}</p>
            </div>
            <div class="card flex items-center gap-3 p-4">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-discount-600 font-display text-sm font-bold text-white">3</span>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('The discount is already applied') }}</p>
            </div>
        </div>

        <h2 class="mb-4 mt-10 font-display text-lg font-bold text-gray-900 dark:text-gray-100">{{ __('Active offers') }}</h2>
        <div class="space-y-4">
            @forelse($merchant->publishedOffers as $offer)
                <div class="card flex flex-col justify-between gap-4 p-5 sm:flex-row sm:items-center">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $offer->title() }}</p>
                            @if($offer->discount_value)
                                <span class="badge-discount">-{{ rtrim(rtrim(number_format($offer->discount_value, 2), '0'), '.') }}%</span>
                            @endif
                        </div>
                        @if($offer->description())
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $offer->description() }}</p>
                        @endif
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            @if($offer->min_purchase_amount)
                                {{ __('Minimum purchase: :amount', ['amount' => number_format($offer->min_purchase_amount, 2)]) }} &middot;
                            @endif
                            @if($offer->expires_at)
                                {{ __('Expires :date', ['date' => $offer->expires_at->translatedFormat('d M Y')]) }}
                            @else
                                {{ __('No expiry') }}
                            @endif
                        </p>
                    </div>

                    <div class="flex shrink-0 flex-col items-stretch gap-2 sm:items-end">
                        @if($offer->coupon_code)
                            <div x-data="{ copied: false, code: @js($offer->coupon_code) }" class="flex items-center justify-between gap-2 rounded-xl border border-dashed border-primary-300 bg-primary-50 px-3 py-1.5 dark:border-primary-700 dark:bg-primary-900/20">
                                <span class="truncate font-mono text-xs font-semibold text-primary-700 dark:text-primary-300">{{ $offer->coupon_code }}</span>
                                <button type="button"
                                        x-on:click="navigator.clipboard.writeText(code); copied = true; setTimeout(() => copied = false, 1500)"
                                        class="shrink-0 text-xs font-semibold text-primary-600 hover:underline dark:text-primary-400">
                                    <span x-show="!copied">{{ __('Copy code') }}</span>
                                    <span x-show="copied" x-cloak>{{ __('Copied!') }}</span>
                                </button>
                            </div>
                        @endif
                        <a href="{{ route('go', ['merchant' => $merchant->slug, 'offer' => $offer->id]) }}" target="_blank" rel="noopener sponsored" class="btn-cta">
                            {{ __('Activate offer') }}
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No active offers right now.') }}</p>
            @endforelse
        </div>

        <h2 class="mb-4 mt-10 font-display text-lg font-bold text-gray-900 dark:text-gray-100">{{ __('Reviews') }}</h2>
        <div class="space-y-3">
            @forelse($merchant->reviews as $review)
                <div class="card p-4">
                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $review->user->name }} &middot; {{ str_repeat('★', $review->rating) }}</p>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $review->comment }}</p>
                </div>
            @empty
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No reviews yet.') }}</p>
            @endforelse
        </div>
    </div>
</x-layouts.site>
