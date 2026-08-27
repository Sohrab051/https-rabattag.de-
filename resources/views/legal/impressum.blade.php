<x-layouts.site :title="__('Impressum')">
    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('Impressum') }}</h1>
        <p class="mt-4 text-gray-700 dark:text-gray-300">{{ __('Legally required information about the site operator (§ 5 TMG).') }}</p>
        <p class="mt-2 text-sm italic text-gray-500 dark:text-gray-400">{{ __('Replace the placeholders below with your company\'s real details before going live.') }}</p>
        <ul class="mt-4 list-disc space-y-1 pl-5 text-gray-700 dark:text-gray-300">
            <li>{{ config('app.name') }}</li>
            <li>[Street address]</li>
            <li>[Postal code, city, country]</li>
            <li>{{ __('Represented by') }}: [Managing director]</li>
            <li>{{ __('Contact') }}: [email], [phone]</li>
            <li>{{ __('Commercial register') }}: [register number]</li>
            <li>{{ __('VAT ID') }}: [VAT ID]</li>
        </ul>
    </div>
</x-layouts.site>
