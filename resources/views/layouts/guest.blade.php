<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" content="#16a34a">
    <title>@yield('title', config('app.name')) — AL-DAWY</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|noto-sans-arabic:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @include('partials.aldawy-theme')
    <style>.font-brand { font-family: "Instrument Sans", "Noto Sans Arabic", ui-sans-serif, system-ui, sans-serif; }</style>
    @include('partials.storefront-analytics')
</head>
<body class="aldawy-site-bg font-brand min-h-screen text-slate-800 antialiased selection:bg-brand/25 selection:text-brand-dark dark:text-slate-100">
    <div class="flex min-h-screen flex-col">
        <header class="border-b border-slate-200 bg-white/90 px-4 py-4 shadow-sm backdrop-blur dark:border-slate-800 dark:bg-slate-900/90">
            <div class="mx-auto flex max-w-md items-center justify-between gap-3">
                <a href="{{ route('store.home') }}" class="text-lg font-bold text-brand dark:text-brand-light">AL-DAWY</a>
                <div class="flex items-center gap-2">
                    <a href="{{ route('store.home') }}" class="text-sm font-medium text-slate-500 hover:text-brand dark:text-slate-400">{{ __('aldawy.nav_home') }}</a>
                    <button type="button" onclick="aldawyToggleTheme()" class="rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-brand hover:text-brand dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300" title="Theme">
                        <span class="dark:hidden">🌙</span>
                        <span class="hidden dark:inline">☀️</span>
                    </button>
                </div>
            </div>
        </header>
        <main class="flex flex-1 items-center justify-center px-4 py-10">
            <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                @if (session('status'))
                    <p class="mb-4 rounded-xl border border-brand/30 bg-brand/5 px-3 py-2 text-sm text-brand dark:bg-brand/10">{{ session('status') }}</p>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
    @livewireScripts
</body>
</html>
