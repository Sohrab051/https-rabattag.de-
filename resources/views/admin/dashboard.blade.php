<x-layouts.admin :title="__('Dashboard')">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="card p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Clicks this month') }}</p>
            <p class="mt-2 font-display text-3xl font-extrabold">{{ number_format($clicksThisMonth) }}</p>
        </div>
        <div class="card p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Conversion rate') }}</p>
            <p class="mt-2 font-display text-3xl font-extrabold">{{ $conversionRate }}%</p>
        </div>
        <div class="card p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Active offers') }}</p>
            <p class="mt-2 font-display text-3xl font-extrabold text-discount-600">{{ number_format($activeOffersCount) }}</p>
        </div>
        <div class="card p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Commission this month') }}</p>
            <p class="mt-2 font-display text-3xl font-extrabold text-primary-600">€{{ number_format($commissionThisMonth, 2) }}</p>
        </div>
    </div>

    <div class="card mt-6 p-5">
        <h2 class="mb-4 font-display text-lg font-bold">{{ __('Top earning stores') }}</h2>
        <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
            <thead>
                <tr class="text-left text-gray-500 dark:text-gray-400">
                    <th class="py-2 pr-4">{{ __('Store') }}</th>
                    <th class="py-2 pr-4">{{ __('Commission earned') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($topMerchants as $merchant)
                    <tr>
                        <td class="py-2 pr-4 font-medium">{{ $merchant->name() }}</td>
                        <td class="py-2 pr-4">€{{ number_format($merchant->total_commission, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="py-4 text-gray-500 dark:text-gray-400">{{ __('No data yet.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.admin>
