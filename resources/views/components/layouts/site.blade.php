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

    <header class="sticky top-0 z-40 border-b border-gray-200 bg-white/90 backdrop-blur dark:border-gray-800 dark:bg-gray-950/90">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center">
                <x-logo class="h-8 w-auto" />
            </a>

            <form action="{{ route('stores.index') }}" method="GET" class="hidden flex-1 max-w-md sm:block">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Search stores...') }}"
                       class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800">
            </form>

            <nav class="flex items-center gap-3">
                <a href="{{ route('stores.index') }}" class="hidden text-sm font-medium text-gray-600 hover:text-primary-600 sm:block dark:text-gray-300">
                    {{ __('Stores') }}
                </a>

                <button type="button" x-data x-on:click="
                    document.documentElement.classList.toggle('dark');
                    try { localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light'); } catch (e) {}
                " class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800" aria-label="Toggle dark mode">
                    🌓
                </button>

                <x-language-switcher />

                @auth
                    @if(auth()->user()->hasAnyRole(['super-admin', 'content-manager', 'finance-manager', 'support']))
                        <a href="{{ route('admin.dashboard') }}" class="btn-cta px-3 py-1.5 text-sm">{{ __('Dashboard') }}</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn-cta px-3 py-1.5 text-sm">{{ __('Dashboard') }}</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-primary-600 dark:text-gray-300">{{ __('Log in') }}</a>
                    <a href="{{ route('register') }}" class="btn-cta px-3 py-1.5 text-sm">{{ __('Sign up') }}</a>
                @endauth
            </nav>
        </div>
    </header>

    @if(session('status'))
        <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-xl bg-discount-50 px-4 py-3 text-sm font-medium text-discount-800 dark:bg-discount-800/30 dark:text-discount-200">
                {{ session('status') }}
            </div>
        </div>
    @endif

    <main class="flex-1">
        {{ $slot }}
    </main>

    <footer class="border-t border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-950">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                <p class="text-sm text-gray-500 dark:text-gray-400">&copy; {{ date('Y') }} {{ config('app.name') }} &middot; {{ __('The best fashion, shoe, and beauty deals, curated daily.') }}</p>
                <div class="flex gap-6 text-sm text-gray-500 dark:text-gray-400">
                    <a href="{{ route('legal.impressum') }}" class="hover:text-primary-600">{{ __('Impressum') }}</a>
                    <a href="{{ route('legal.privacy') }}" class="hover:text-primary-600">{{ __('Privacy Policy') }}</a>
                    <a href="{{ route('legal.terms') }}" class="hover:text-primary-600">{{ __('Terms') }}</a>
                </div>
            </div>
        </div>
    </footer>

    <x-cookie-consent />
    @livewireScripts
</body>
</html>
