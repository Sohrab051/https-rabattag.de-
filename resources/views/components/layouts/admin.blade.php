<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - ' : '' }}{{ __('Admin') }} - {{ config('app.name') }}</title>

    <script>
        try {
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        } catch (e) {}
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-gray-100 font-sans text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-100">
    <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">
        <aside
            x-cloak
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full sm:translate-x-0'"
            class="fixed inset-y-0 left-0 z-50 w-64 shrink-0 -translate-x-full border-r border-gray-200 bg-white p-4 transition-transform duration-200 ease-in-out dark:border-gray-800 dark:bg-gray-900 sm:static sm:translate-x-0 sm:block"
        >
            <div class="mb-8 flex items-center justify-between px-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                    <x-logo class="h-7 w-auto" />
                </a>
                <button type="button" x-on:click="sidebarOpen = false" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 sm:hidden" aria-label="{{ __('Close menu') }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>
            <nav class="space-y-1 text-sm font-medium">
                <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">{{ __('Dashboard') }}</a>
                <a href="{{ route('admin.merchant-offer.create') }}" class="block rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">{{ __('Add store & offer') }}</a>
                <a href="{{ route('admin.merchants.index') }}" class="block rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">{{ __('Merchants') }}</a>
                <a href="{{ route('admin.offers.index') }}" class="block rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">{{ __('Offers') }}</a>
                @if(auth()->user()?->hasAnyRole(['super-admin', 'content-manager']))
                    <a href="{{ route('admin.categories.index') }}" class="block rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">{{ __('Category Management') }}</a>
                @endif
                <a href="{{ route('admin.awin.index') }}" class="block rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">{{ __('Awin') }}</a>
                <a href="{{ route('admin.users.index') }}" class="block rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">{{ __('Users') }}</a>
                <a href="{{ route('admin.reports.export') }}" class="block rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">{{ __('Export report (CSV)') }}</a>
                <a href="{{ route('admin.settings') }}" class="block rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">{{ __('Settings') }}</a>
                <a href="{{ route('home') }}" class="mt-4 block rounded-lg px-3 py-2 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">&larr; {{ __('Back to site') }}</a>
            </nav>
        </aside>

        <div
            x-cloak
            x-show="sidebarOpen"
            x-on:click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/50 sm:hidden"
        ></div>

        <div class="min-w-0 flex-1">
            <header class="flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center gap-3">
                    <button type="button" x-on:click="sidebarOpen = true" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 sm:hidden" aria-label="{{ __('Open menu') }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="font-display text-lg font-bold">{{ $title ?? __('Admin') }}</h1>
                </div>
                <button type="button" x-data="{ dark: document.documentElement.classList.contains('dark') }" x-on:click="
                    document.documentElement.classList.toggle('dark');
                    dark = document.documentElement.classList.contains('dark');
                    try { localStorage.setItem('theme', dark ? 'dark' : 'light'); } catch (e) {}
                " class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800" aria-label="{{ __('Toggle dark mode') }}">
                    <svg x-show="!dark" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4.5" /><path d="M12 2.5v2M12 19.5v2M4.2 4.2l1.4 1.4M18.4 18.4l1.4 1.4M2.5 12h2M19.5 12h2M4.2 19.8l1.4-1.4M18.4 5.6l1.4-1.4" /></svg>
                    <svg x-show="dark" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 14.5A8.5 8.5 0 019.5 4a8.5 8.5 0 1010.5 10.5z"/></svg>
                </button>
            </header>

            @if(session('status'))
                <div class="mx-6 mt-4 rounded-xl bg-discount-50 px-4 py-3 text-sm font-medium text-discount-800 dark:bg-discount-800/30 dark:text-discount-200">
                    {{ session('status') }}
                </div>
            @endif

            <main class="p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
    @livewireScripts
</body>
</html>
