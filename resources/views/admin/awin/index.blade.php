<x-layouts.admin :title="__('Awin Sync')">
    <div class="mb-6 grid gap-4 sm:grid-cols-2">
        <div class="card p-4">
            <h2 class="mb-3 font-display text-sm font-bold text-gray-500 dark:text-gray-400">{{ __('Connection status') }}</h2>
            <div class="space-y-2 text-sm">
                <div class="flex items-center justify-between">
                    <span>{{ __('Status') }}</span>
                    @if($configured)
                        <span class="inline-flex items-center rounded-full bg-discount-100 px-2 py-0.5 text-[10px] font-semibold text-discount-800 dark:bg-discount-800/30 dark:text-discount-200">{{ __('Configured') }}</span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-semibold text-red-800 dark:bg-red-900/30 dark:text-red-200">{{ __('Not Configured') }}</span>
                    @endif
                </div>
                <div class="flex items-center justify-between">
                    <span>{{ __('Feed enabled') }}</span>
                    <span>{{ $feedEnabled ? __('Yes') : __('No') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span>{{ __('Publisher ID') }}</span>
                    <span class="font-mono">{{ $maskedPublisherId }}</span>
                </div>
            </div>
        </div>

        <div class="card p-4">
            <h2 class="mb-3 font-display text-sm font-bold text-gray-500 dark:text-gray-400">{{ __('Latest sync') }}</h2>
            @if($latestRun)
                <div class="space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <span>{{ __('Status') }}</span>
                        @if($latestRun->status === 'completed')
                            <span class="inline-flex items-center rounded-full bg-discount-100 px-2 py-0.5 text-[10px] font-semibold text-discount-800 dark:bg-discount-800/30 dark:text-discount-200">{{ __('Completed') }}</span>
                        @elseif($latestRun->status === 'running')
                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-semibold text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">{{ __('Running...') }}</span>
                        @elseif($latestRun->status === 'failed')
                            <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-semibold text-red-800 dark:bg-red-900/30 dark:text-red-200">{{ __('Failed') }}</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">{{ __('Pending') }}</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span>{{ __('Started') }}</span>
                        <span>{{ $latestRun->started_at?->diffForHumans() ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>{{ __('Finished') }}</span>
                        <span>{{ $latestRun->finished_at?->diffForHumans() ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>{{ __('Merchants created/updated') }}</span>
                        <span>{{ $latestRun->merchants_created }} / {{ $latestRun->merchants_updated }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>{{ __('Offers created/updated') }}</span>
                        <span>{{ $latestRun->offers_created }} / {{ $latestRun->offers_updated }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>{{ __('Errors') }}</span>
                        <span>{{ $latestRun->errors_count }}</span>
                    </div>
                    @if($latestRun->error_message)
                        <p class="rounded-lg bg-red-50 px-3 py-2 text-xs text-red-800 dark:bg-red-900/30 dark:text-red-200">{{ $latestRun->error_message }}</p>
                    @endif
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No sync has run yet.') }}</p>
            @endif

            @if(auth()->user()?->hasAnyRole(['super-admin', 'content-manager']))
                @php($syncDisabled = $latestRun && $latestRun->isActive())
                <form method="POST" action="{{ route('admin.awin.sync') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="btn-cta" @disabled($syncDisabled)>
                        {{ $syncDisabled ? __('Sync in progress...') : __('Sync Now') }}
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if(session('status'))
        <div class="mb-4 rounded-xl bg-discount-50 px-4 py-3 text-sm font-medium text-discount-800 dark:bg-discount-800/30 dark:text-discount-200">
            {{ session('status') }}
        </div>
    @endif

    <div class="card overflow-x-auto p-4">
        <h2 class="mb-3 font-display text-sm font-bold text-gray-500 dark:text-gray-400">{{ __('Sync history') }}</h2>
        <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
            <thead>
                <tr class="text-left text-gray-500 dark:text-gray-400">
                    <th class="py-2 pr-4">{{ __('Started') }}</th>
                    <th class="py-2 pr-4">{{ __('Finished') }}</th>
                    <th class="py-2 pr-4">{{ __('Status') }}</th>
                    <th class="py-2 pr-4">{{ __('Merchants') }}</th>
                    <th class="py-2 pr-4">{{ __('Offers') }}</th>
                    <th class="py-2 pr-4">{{ __('Errors') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($runs as $run)
                    <tr>
                        <td class="py-2 pr-4">{{ $run->started_at?->format('Y-m-d H:i') ?? '—' }}</td>
                        <td class="py-2 pr-4">{{ $run->finished_at?->format('Y-m-d H:i') ?? '—' }}</td>
                        <td class="py-2 pr-4">{{ ucfirst($run->status) }}</td>
                        <td class="py-2 pr-4">{{ $run->merchants_created }}/{{ $run->merchants_updated }}</td>
                        <td class="py-2 pr-4">{{ $run->offers_created }}/{{ $run->offers_updated }}</td>
                        <td class="py-2 pr-4">{{ $run->errors_count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="py-4 text-gray-500 dark:text-gray-400" colspan="6">{{ __('No sync runs yet.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $runs->links() }}
        </div>
    </div>
</x-layouts.admin>
