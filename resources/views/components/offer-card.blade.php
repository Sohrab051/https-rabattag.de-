@props(['offer'])

@php
    $hasDiscount = ! is_null($offer->discount_value) && $offer->discount_value > 0;
    $expiresSoon = $offer->expires_at && $offer->expires_at->diffInHours(now()) <= 48 && ! $offer->isExpired();
    $isNew = $offer->created_at && $offer->created_at->gt(now()->subDays(7));
@endphp

<div class="group card flex flex-col overflow-hidden transition-card hover:-translate-y-0.5">
    <div class="flex items-start gap-3 p-4 pb-0">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-primary-100 to-primary-50 dark:from-primary-900/40 dark:to-primary-900/10 overflow-hidden ring-1 ring-inset ring-primary-100 dark:ring-primary-800/40">
            @if($offer->merchant->logo)
                <img src="{{ Storage::url($offer->merchant->logo) }}" alt="{{ $offer->merchant->name() }}" class="h-full w-full object-contain">
            @else
                <span class="text-lg font-display font-bold text-primary-600 dark:text-primary-300">{{ mb_substr($offer->merchant->name(), 0, 1) }}</span>
            @endif
        </div>
        <div class="min-w-0 flex-1">
            <p class="truncate text-sm font-display font-semibold text-gray-900 dark:text-gray-100">{{ $offer->merchant->name() }}</p>
            <div class="mt-1 flex flex-wrap gap-1">
                @if($offer->is_featured)
                    <span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-[10px] font-semibold text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">{{ __('Popular') }}</span>
                @endif
                @if($isNew)
                    <span class="inline-flex items-center rounded-full bg-discount-50 px-2 py-0.5 text-[10px] font-semibold text-discount-700 dark:bg-discount-800/30 dark:text-discount-300">{{ __('New') }}</span>
                @endif
            </div>
        </div>
        @if($hasDiscount)
            <div class="flex shrink-0 flex-col items-center justify-center rounded-xl bg-discount-600 px-2.5 py-1.5 text-white shadow-sm">
                <span class="text-base font-display font-extrabold leading-none">-{{ rtrim(rtrim(number_format($offer->discount_value, 2), '0'), '.') }}%</span>
            </div>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-4">
        <p class="line-clamp-2 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $offer->title() }}</p>

        <div class="mt-3 flex items-center justify-between text-xs">
            @if($offer->expires_at)
                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 font-semibold {{ $expiresSoon ? 'bg-urgent-50 text-urgent-700 dark:bg-urgent-700/20 dark:text-urgent-300 animate-pulse-urgent' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    {{ __('Until :date', ['date' => $offer->expires_at->translatedFormat('d M')]) }}
                </span>
            @else
                <span class="text-gray-400 dark:text-gray-500">{{ __('No expiry') }}</span>
            @endif
        </div>

        @if($offer->coupon_code)
            <div x-data="{ copied: false, code: @js($offer->coupon_code) }" class="mt-3 flex items-center justify-between gap-2 rounded-xl border border-dashed border-primary-300 bg-primary-50 px-3 py-1.5 dark:border-primary-700 dark:bg-primary-900/20">
                <span class="truncate font-mono text-xs font-semibold text-primary-700 dark:text-primary-300">{{ $offer->coupon_code }}</span>
                <button type="button"
                        x-on:click="navigator.clipboard.writeText(code); copied = true; setTimeout(() => copied = false, 1500)"
                        class="shrink-0 text-xs font-semibold text-primary-600 hover:underline dark:text-primary-400">
                    <span x-show="!copied">{{ __('Copy code') }}</span>
                    <span x-show="copied" x-cloak>{{ __('Copied!') }}</span>
                </button>
            </div>
        @endif

        <a href="{{ route('stores.show', ['merchant' => $offer->merchant->slug]) }}"
           class="btn-cta mt-4 w-full group-hover:bg-primary-700">
            {{ __('View offer') }}
            <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
        </a>
    </div>
</div>
