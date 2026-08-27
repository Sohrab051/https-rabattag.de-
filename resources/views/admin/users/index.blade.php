<x-layouts.admin :title="__('Users')">
    <div class="card overflow-x-auto p-4">
        <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
            <thead>
                <tr class="text-left text-gray-500 dark:text-gray-400">
                    <th class="py-2 pr-4">{{ __('Name') }}</th>
                    <th class="py-2 pr-4">{{ __('Email') }}</th>
                    <th class="py-2 pr-4">{{ __('Status') }}</th>
                    <th class="py-2 pr-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($users as $user)
                    <tr>
                        <td class="py-2 pr-4 font-medium">{{ $user->name }}</td>
                        <td class="py-2 pr-4">{{ $user->email }}</td>
                        <td class="py-2 pr-4">
                            @if($user->is_blocked)
                                <x-status-badge status="rejected" />
                            @else
                                <x-status-badge status="approved" />
                            @endif
                        </td>
                        <td class="py-2 pr-4">
                            <form method="POST" action="{{ route('admin.users.block', ['user' => $user]) }}" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                @csrf @method('PATCH')
                                <button class="text-red-600 hover:underline">{{ $user->is_blocked ? __('Unblock') : __('Block') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
</x-layouts.admin>
