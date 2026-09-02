<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - ' : '' }}{{ config('app.name') }}</title>

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
<body class="flex min-h-screen flex-col bg-surface-light font-sans text-gray-900 antialiased dark:bg-surface-dark dark:text-gray-100">

    <header class="sticky top-0 z-40 border-b border-primary-100/70 bg-white/80 backdrop-blur-md dark:border-primary-400/10 dark:bg-primary-900/80">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center">
                <x-logo class="h-8 w-auto" />
            </a>

            <form action="{{ route('stores.index') }}" method="GET" class="hidden flex-1 max-w-md sm:block">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Search stores...') }}"
                       class="w-full rounded-btn border-primary-200 text-sm shadow-sm focus:border-primary-900 focus:ring-primary-900 dark:border-primary-700 dark:bg-primary-800">
            </form>

            <nav class="flex items-center gap-3">
                <a href="{{ route('stores.index') }}" class="hidden text-sm font-semibold text-gray-600 hover:text-primary-700 sm:block dark:text-gray-300 dark:hover:text-discount-400">
                    {{ __('Stores') }}
                </a>

                <button type="button" x-data="{ dark: document.documentElement.classList.contains('dark') }" x-on:click="
                    document.documentElement.classList.toggle('dark');
                    dark = document.documentElement.classList.contains('dark');
                    try { localStorage.setItem('theme', dark ? 'dark' : 'light'); } catch (e) {}
                " class="rounded-full p-2 text-gray-500 hover:bg-primary-100 dark:text-gray-300 dark:hover:bg-primary-700" aria-label="Toggle dark mode">
                    <svg x-show="!dark" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4.5" /><path d="M12 2.5v2M12 19.5v2M4.2 4.2l1.4 1.4M18.4 18.4l1.4 1.4M2.5 12h2M19.5 12h2M4.2 19.8l1.4-1.4M18.4 5.6l1.4-1.4" /></svg>
                    <svg x-show="dark" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 14.5A8.5 8.5 0 019.5 4a8.5 8.5 0 1010.5 10.5z" /></svg>
                </button>

                <x-language-switcher />

                @auth
                    @if(auth()->user()->hasAnyRole(['super-admin', 'content-manager', 'finance-manager', 'support']))
                        <a href="{{ route('admin.dashboard') }}" class="btn-cta px-4 py-2 text-xs">{{ __('Dashboard') }}</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn-cta px-4 py-2 text-xs">{{ __('Dashboard') }}</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-primary-700 dark:text-gray-300 dark:hover:text-discount-400">{{ __('Log in') }}</a>
                    <a href="{{ route('register') }}" class="btn-cta px-4 py-2 text-xs">{{ __('Sign up') }}</a>
                @endauth
            </nav>
        </div>
    </header>

    @if(session('status'))
        <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-card bg-discount-100 px-4 py-3 text-sm font-semibold text-discount-800 dark:bg-discount-800/30 dark:text-discount-200">
                {{ session('status') }}
            </div>
        </div>
    @endif

    <main class="flex-1">
        {{ $slot }}
    </main>

    <footer class="border-t border-primary-100/70 bg-white dark:border-primary-400/10 dark:bg-primary-900">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                <p class="text-sm text-gray-500 dark:text-gray-400">&copy; {{ date('Y') }} {{ config('app.name') }} &middot; {{ __('The best fashion, shoe, and beauty deals, curated daily.') }}</p>
                <div class="flex gap-6 text-sm font-semibold text-gray-500 dark:text-gray-400">
                    <a href="{{ route('legal.impressum') }}" class="hover:text-discount-600 dark:hover:text-discount-400">{{ __('Impressum') }}</a>
                    <a href="{{ route('legal.privacy') }}" class="hover:text-discount-600 dark:hover:text-discount-400">{{ __('Privacy Policy') }}</a>
                    <a href="{{ route('legal.terms') }}" class="hover:text-discount-600 dark:hover:text-discount-400">{{ __('Terms') }}</a>
                </div>
            </div>
        </div>
    </footer>

    <x-cookie-consent />
    @livewireScripts
</body>
</html>
