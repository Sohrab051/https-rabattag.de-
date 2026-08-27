<x-layouts.admin :title="__('Offers')">
    <form method="GET" class="mb-4 flex flex-wrap items-center gap-3">
        <select name="status" class="rounded-xl border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-800" onchange="this.form.submit()">
            <option value="">{{ __('All statuses') }}</option>
            <option value="draft" @selected(request('status') === 'draft')>{{ __('Draft') }}</option>
            <option value="pending" @selected(request('status') === 'pending')>{{ __('Pending') }}</option>
            <option value="published" @selected(request('status') === 'published')>{{ __('Published') }}</option>
            <option value="expired" @selected(request('status') === 'expired')>{{ __('Expired') }}</option>
        </select>
        <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
            <input type="checkbox" name="needs_review" value="1" @checked(request()->boolean('needs_review')) onchange="this.form.submit()" class="rounded border-gray-300">
            {{ __('Needs review') }}
        </label>
    </form>

    <div class="card overflow-x-auto p-4">
        <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
            <thead>
                <tr class="text-left text-gray-500 dark:text-gray-400">
                    <th class="py-2 pr-4">{{ __('Offer') }}</th>
                    <th class="py-2 pr-4">{{ __('Store') }}</th>
                    <th class="py-2 pr-4">{{ __('Type') }}</th>
                    <th class="py-2 pr-4">{{ __('Status') }}</th>
                    <th class="py-2 pr-4">{{ __('Expires') }}</th>
                    <th class="py-2 pr-4">{{ __('Verified') }}</th>
                    <th class="py-2 pr-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($offers as $offer)
                    <tr>
                        <td class="py-2 pr-4 font-medium">
                            {{ $offer->title_en }}
                            @if($offer->needs_review)
                                <span class="ml-1 inline-flex items-center rounded-full bg-urgent-50 px-2 py-0.5 text-[10px] font-semibold text-urgent-700 dark:bg-urgent-700/20 dark:text-urgent-300">{{ __('Needs review') }}</span>
                            @endif
                            @if($offer->coupon_code)
                                <span class="ml-1 inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">{{ $offer->coupon_code }}</span>
                            @endif
                        </td>
                        <td class="py-2 pr-4">{{ $offer->merchant->name_en }}</td>
                        <td class="py-2 pr-4">
                            <span class="inline-flex items-center rounded-full bg-primary-50 px-2 py-0.5 text-[10px] font-semibold text-primary-700 dark:bg-primary-900/30 dark:text-primary-300">
                                {{ __(ucfirst($offer->deal_type)) }}
                            </span>
                        </td>
                        <td class="py-2 pr-4"><x-status-badge :status="$offer->status" /></td>
                        <td class="py-2 pr-4">{{ $offer->expires_at?->format('d.m.Y') ?? '—' }}</td>
                        <td class="py-2 pr-4">
                            <form method="POST" action="{{ route('admin.offers.toggle-verified', ['offer' => $offer]) }}" class="inline">
                                @csrf @method('PATCH')
                                <button class="{{ $offer->is_verified ? 'text-discount-600' : 'text-gray-400' }} hover:underline">
                                    {{ $offer->is_verified ? __('Verified') : __('Unverified') }}
                                </button>
                            </form>
                        </td>
                        <td class="py-2 pr-4 space-x-2 whitespace-nowrap">
                            <a href="{{ route('admin.offers.edit', ['offer' => $offer]) }}" class="text-primary-600 hover:underline">{{ __('Edit') }}</a>
                            @if($offer->resolvedAffiliateUrl())
                                <a href="{{ $offer->resolvedAffiliateUrl() }}" target="_blank" rel="noopener" class="text-gray-500 hover:underline">{{ __('Test link') }}</a>
                            @endif
                            @if($offer->status !== 'published')
                                <form method="POST" action="{{ route('admin.offers.publish', ['offer' => $offer]) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <button class="text-discount-600 hover:underline">{{ __('Publish') }}</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.offers.destroy', ['offer' => $offer]) }}" class="inline" onsubmit="return confirm('{{ __('Delete this offer?') }}')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">{{ __('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $offers->links() }}</div>
</x-layouts.admin>
