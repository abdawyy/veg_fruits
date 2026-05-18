<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" content="#16a34a">
    <title>{{ $htmlTitle ?? config('app.name').' — AL-DAWY' }}</title>
    @if (! empty($pageMetaDescription))
        <meta name="description" content="{{ $pageMetaDescription }}">
    @endif
    @if (! empty($ogImageUrl))
        <meta property="og:image" content="{{ $ogImageUrl }}">
    @endif
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|noto-sans-arabic:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @include('partials.aldawy-theme')
    <style>.font-brand { font-family: "Instrument Sans", "Noto Sans Arabic", ui-sans-serif, system-ui, sans-serif; }</style>
    @include('partials.storefront-analytics')
</head>
<body class="aldawy-site-bg min-h-screen bg-transparent font-brand text-slate-800 antialiased selection:bg-brand/25 selection:text-brand-dark dark:text-slate-100" x-data="{ navOpen: false }">
    <livewire:store.price-notice-banner />
    <header class="sticky top-0 z-50 overflow-visible border-b border-slate-200 bg-white/95 shadow-sm backdrop-blur-md dark:border-slate-800 dark:bg-slate-900/95">
        <div class="mx-auto flex max-w-6xl flex-col gap-3 px-4 py-3 sm:gap-4 sm:py-4 lg:grid lg:grid-cols-[auto_minmax(220px,1fr)_auto] lg:items-center lg:gap-x-6 lg:gap-y-0">
            <div class="flex w-full min-w-0 items-center justify-between gap-3 lg:col-start-1 lg:row-start-1 lg:w-auto lg:justify-self-start">
                <a href="{{ route('store.home') }}" class="group flex min-w-0 shrink-0 items-center gap-2">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand text-lg font-bold text-white shadow-sm ring-2 ring-brand/20 transition group-hover:bg-brand-dark dark:ring-brand/40">A</span>
                    <div class="min-w-0 leading-tight">
                        <span class="block text-lg font-bold tracking-tight text-slate-800 dark:text-white">AL-DAWY</span>
                        <span class="text-xs font-medium text-brand dark:text-brand-light">{{ __('aldawy.badge_fresh') }}</span>
                    </div>
                </a>
                <button
                    type="button"
                    class="inline-flex shrink-0 items-center justify-center rounded-lg border border-slate-200 p-2.5 text-slate-700 transition hover:bg-surface hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand/30 lg:hidden dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-800"
                    @click="navOpen = true"
                    :aria-expanded="navOpen.toString()"
                    aria-controls="aldawy-store-nav-drawer"
                    aria-label="{{ __('aldawy.nav_menu') }}"
                >
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>

            <div class="min-w-0 lg:col-start-2 lg:row-start-1 lg:w-full lg:max-w-3xl">
                <livewire:store.product-search-bar />
            </div>

            <div class="hidden min-w-0 shrink-0 flex-col gap-3 lg:col-start-3 lg:row-start-1 lg:flex lg:flex-row lg:items-center lg:gap-2">
                <nav class="flex flex-wrap items-center justify-end gap-1">
                    <a href="{{ route('store.home') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-surface hover:text-slate-800 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white">{{ __('aldawy.nav_home') }}</a>
                    <div class="relative" x-data="{ browseOpen: false }" @click.outside="browseOpen = false">
                        <button
                            type="button"
                            class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-surface hover:text-slate-800 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white"
                            @click="browseOpen = !browseOpen"
                            :aria-expanded="browseOpen.toString()"
                            aria-haspopup="menu"
                            aria-controls="aldawy-nav-browse-menu"
                        >
                            <span>{{ __('aldawy.nav_browse') }}</span>
                            <svg class="h-4 w-4 shrink-0 text-slate-500 transition-transform dark:text-slate-400" :class="{ 'rotate-180': browseOpen }" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                        <div
                            id="aldawy-nav-browse-menu"
                            x-cloak
                            x-show="browseOpen"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1"
                            class="absolute start-0 top-full z-[120] mt-1 min-w-[13rem] rounded-xl border border-slate-200 bg-white py-1 shadow-xl ring-1 ring-black/5 dark:border-slate-600 dark:bg-slate-900 dark:ring-white/10"
                            role="menu"
                        >
                            <a href="{{ route('store.shop') }}" role="menuitem" class="block px-4 py-2.5 text-sm text-slate-800 hover:bg-slate-50 dark:text-slate-100 dark:hover:bg-slate-800" @click="browseOpen = false">{{ __('aldawy.nav_shop') }}</a>
                            <a href="{{ route('store.fruits') }}" role="menuitem" class="block px-4 py-2.5 text-sm text-slate-800 hover:bg-slate-50 dark:text-slate-100 dark:hover:bg-slate-800" @click="browseOpen = false">{{ __('aldawy.nav_fruits') }}</a>
                            <a href="{{ route('store.vegetables') }}" role="menuitem" class="block px-4 py-2.5 text-sm text-slate-800 hover:bg-slate-50 dark:text-slate-100 dark:hover:bg-slate-800" @click="browseOpen = false">{{ __('aldawy.nav_vegetables') }}</a>
                            <a href="{{ route('store.services') }}" role="menuitem" class="block px-4 py-2.5 text-sm text-slate-800 hover:bg-slate-50 dark:text-slate-100 dark:hover:bg-slate-800" @click="browseOpen = false">{{ __('aldawy.nav_services') }}</a>
                        </div>
                    </div>
                    @include('store.partials.cart-nav-icon', ['variant' => 'desktop'])
                    @auth
                        <a href="{{ url('/my') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-brand transition hover:bg-brand/10 dark:text-brand-light dark:hover:bg-brand/20">{{ __('aldawy.nav_account') }}</a>
                        @if(auth()->user()->is_admin)
                            <a href="{{ url('/admin') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-accent transition hover:bg-accent/10 dark:hover:bg-accent/20">{{ __('aldawy.admin_portal') }}</a>
                        @endif
                        <form action="{{ route('logout') }}" method="post" class="inline">
                            @csrf
                            <button type="submit" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-500 hover:text-danger dark:text-slate-400">{{ __('aldawy.nav_logout') }}</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-surface dark:text-slate-300 dark:hover:bg-slate-800">{{ __('aldawy.nav_login') }}</a>
                        <a href="{{ route('register') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-brand transition hover:bg-brand/10 dark:text-brand-light">{{ __('aldawy.nav_register') }}</a>
                    @endauth
                </nav>
                <div class="flex items-center justify-end gap-2">
                    <button type="button" onclick="aldawyToggleTheme()" class="rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-brand hover:text-brand dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-brand" title="Theme">
                        <span class="dark:hidden">🌙</span>
                        <span class="hidden dark:inline">☀️</span>
                    </button>
                    <a href="{{ request()->fullUrlWithQuery(['locale' => 'en']) }}" class="rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-brand hover:text-brand dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">EN</a>
                    <a href="{{ request()->fullUrlWithQuery(['locale' => 'ar']) }}" class="rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-brand hover:text-brand dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">عربي</a>
                </div>
            </div>
        </div>
    </header>

    {{-- Mobile / tablet navigation drawer --}}
    <div
        x-cloak
        x-show="navOpen"
        x-transition.opacity
        class="fixed inset-0 z-[65] bg-slate-900/50 backdrop-blur-sm lg:hidden"
        @click="navOpen = false"
        aria-hidden="true"
    ></div>
    <div
        id="aldawy-store-nav-drawer"
        x-cloak
        x-show="navOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="translate-x-full rtl:-translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="translate-x-full rtl:-translate-x-full opacity-0"
        class="fixed inset-y-0 end-0 z-[75] flex w-full max-w-sm flex-col border-s border-slate-200 bg-white shadow-2xl lg:hidden dark:border-slate-800 dark:bg-slate-900"
        role="dialog"
        aria-modal="true"
        aria-label="{{ __('aldawy.nav_menu') }}"
    >
        <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3 dark:border-slate-800">
            <span class="text-lg font-bold text-slate-900 dark:text-white">{{ __('aldawy.nav_menu') }}</span>
            <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800" @click="navOpen = false" aria-label="{{ __('aldawy.nav_menu_close') }}">✕</button>
        </div>
        <nav class="flex flex-1 flex-col gap-1 overflow-y-auto px-3 py-4" @click.stop>
            <a href="{{ route('store.home') }}" class="rounded-lg px-3 py-3 text-base font-medium text-slate-800 hover:bg-surface dark:text-slate-100 dark:hover:bg-slate-800" @click="navOpen = false">{{ __('aldawy.nav_home') }}</a>
            <details class="group rounded-lg border border-slate-200 dark:border-slate-700">
                <summary class="cursor-pointer list-none px-3 py-3 text-base font-medium text-slate-800 marker:hidden hover:bg-surface dark:text-slate-100 dark:hover:bg-slate-800 [&::-webkit-details-marker]:hidden">
                    <span class="flex items-center justify-between gap-2">
                        <span>{{ __('aldawy.nav_browse') }}</span>
                        <svg class="h-4 w-4 shrink-0 text-slate-500 transition dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </span>
                </summary>
                <div class="border-t border-slate-100 pb-2 pt-1 dark:border-slate-700">
                    <a href="{{ route('store.shop') }}" class="block rounded-lg px-5 py-2.5 text-sm text-slate-700 hover:bg-surface dark:text-slate-200 dark:hover:bg-slate-800" @click="navOpen = false">{{ __('aldawy.nav_shop') }}</a>
                    <a href="{{ route('store.fruits') }}" class="block rounded-lg px-5 py-2.5 text-sm text-slate-700 hover:bg-surface dark:text-slate-200 dark:hover:bg-slate-800" @click="navOpen = false">{{ __('aldawy.nav_fruits') }}</a>
                    <a href="{{ route('store.vegetables') }}" class="block rounded-lg px-5 py-2.5 text-sm text-slate-700 hover:bg-surface dark:text-slate-200 dark:hover:bg-slate-800" @click="navOpen = false">{{ __('aldawy.nav_vegetables') }}</a>
                    <a href="{{ route('store.services') }}" class="block rounded-lg px-5 py-2.5 text-sm text-slate-700 hover:bg-surface dark:text-slate-200 dark:hover:bg-slate-800" @click="navOpen = false">{{ __('aldawy.nav_services') }}</a>
                </div>
            </details>
            @include('store.partials.cart-nav-icon', ['variant' => 'drawer'])
            @auth
                <a href="{{ url('/my') }}" class="rounded-lg px-3 py-3 text-base font-medium text-brand hover:bg-brand/10 dark:text-brand-light" @click="navOpen = false">{{ __('aldawy.nav_account') }}</a>
                @if(auth()->user()->is_admin)
                    <a href="{{ url('/admin') }}" class="rounded-lg px-3 py-3 text-base font-medium text-accent hover:bg-accent/10" @click="navOpen = false">{{ __('aldawy.admin_portal') }}</a>
                @endif
                <form action="{{ route('logout') }}" method="post" class="mt-2 border-t border-slate-200 pt-3 dark:border-slate-700">
                    @csrf
                    <button type="submit" class="w-full rounded-lg px-3 py-3 text-start text-base font-medium text-slate-500 hover:bg-surface dark:text-slate-400 dark:hover:bg-slate-800">{{ __('aldawy.nav_logout') }}</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="rounded-lg px-3 py-3 text-base font-medium text-slate-800 hover:bg-surface dark:text-slate-100 dark:hover:bg-slate-800" @click="navOpen = false">{{ __('aldawy.nav_login') }}</a>
                <a href="{{ route('register') }}" class="rounded-lg px-3 py-3 text-base font-medium text-brand hover:bg-brand/10 dark:text-brand-light" @click="navOpen = false">{{ __('aldawy.nav_register') }}</a>
            @endauth
            <div class="mt-auto flex flex-wrap items-center gap-2 border-t border-slate-200 px-1 py-4 dark:border-slate-700">
                <button type="button" onclick="aldawyToggleTheme()" class="rounded-full border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300">
                    <span class="dark:hidden">🌙</span>
                    <span class="hidden dark:inline">☀️</span>
                </button>
                <a href="{{ request()->fullUrlWithQuery(['locale' => 'en']) }}" class="rounded-full border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300">EN</a>
                <a href="{{ request()->fullUrlWithQuery(['locale' => 'ar']) }}" class="rounded-full border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300">عربي</a>
            </div>
        </nav>
    </div>

    <main>
        <div id="aldawy-toast" class="pointer-events-none fixed inset-x-0 top-20 z-[100] hidden px-4" aria-live="polite">
            <div class="mx-auto max-w-md rounded-xl border border-slate-200 bg-white px-4 py-3 text-center text-sm font-semibold text-slate-800 shadow-lg dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"></div>
        </div>
        @if (session('status'))
            <div class="border-b border-success/30 bg-success/10 px-4 py-3 text-center text-sm font-medium text-brand-dark dark:bg-success/20 dark:text-success" role="status">
                {{ session('status') }}
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="mt-20 border-t border-emerald-900/30 bg-brand-dark py-12 text-slate-300">
        <div class="mx-auto max-w-6xl px-4 text-center text-sm">
            <p class="font-medium text-white">{{ config('app.name') }}</p>
            <p class="mt-2 text-slate-400">{{ __('aldawy.footer_note') }}</p>
            <p class="mt-4 text-xs text-slate-500">{{ __('aldawy.photo_credit') }}</p>
        </div>
    </footer>
    @livewireScripts
</body>
</html>
