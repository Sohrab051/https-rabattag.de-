<div
    x-data="{
        show: false,
        init() {
            try { this.show = !localStorage.getItem('cookie_consent'); } catch (e) { this.show = true; }
        },
        accept(value) {
            try { localStorage.setItem('cookie_consent', value); } catch (e) {}
            this.show = false;
        }
    }"
    x-show="show"
    x-cloak
    x-transition
    class="fixed inset-x-0 bottom-0 z-50 border-t border-primary-100 bg-white/95 p-4 backdrop-blur dark:border-primary-400/10 dark:bg-primary-900/95"
>
    <div class="mx-auto flex max-w-5xl flex-col items-center justify-between gap-3 sm:flex-row">
        <p class="text-sm text-gray-600 dark:text-gray-300">
            {{ __('We use cookies to run this site and, with your consent, to measure traffic. See our') }}
            <a href="{{ route('legal.privacy') }}" class="underline">{{ __('Privacy Policy') }}</a>.
        </p>
        <div class="flex shrink-0 gap-2">
            <button type="button" x-on:click="accept('essential')"
                class="rounded-full border border-gray-300 px-3 py-1.5 text-sm font-medium text-gray-700 dark:border-gray-600 dark:text-gray-200">
                {{ __('Essential only') }}
            </button>
            <button type="button" x-on:click="accept('all')" class="btn-cta px-3 py-1.5 text-sm">
                {{ __('Accept all') }}
            </button>
        </div>
    </div>
</div>
