<x-layouts.site :title="__('Terms of Service')">
    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8 text-gray-700 dark:text-gray-300">
        <h1 class="font-display text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('Terms of Service') }}</h1>
        <p class="mt-2 text-sm italic text-gray-500 dark:text-gray-400">{{ __('Draft template — have this reviewed by a lawyer before launch.') }}</p>

        <h2 class="mt-6 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('What we offer') }}</h2>
        <p class="mt-2">{{ __('We list discount deals and offers from partner stores. Clicking an offer redirects you to the merchant\'s site, where the discount is already applied; availability, terms, and final prices are set by the respective merchant.') }}</p>

        <h2 class="mt-6 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Using offers') }}</h2>
        <p class="mt-2">{{ __('Click an offer to be redirected to the merchant\'s site, where the discount is automatically applied before the offer expires. We do not guarantee that a discount will apply to every order, and merchants may change or withdraw an offer at any time.') }}</p>

        <h2 class="mt-6 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Affiliate links') }}</h2>
        <p class="mt-2">{{ __('Some links to merchants are affiliate links. We may receive a commission from the merchant when you make a purchase after following such a link, at no extra cost to you.') }}</p>

        <h2 class="mt-6 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Fraud and misuse') }}</h2>
        <p class="mt-2">{{ __('Accounts found to be abusing offers, scraping the site, or attempting to defraud partner stores may be suspended.') }}</p>
    </div>
</x-layouts.site>
