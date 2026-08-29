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
<body
    class="aldawy-site-bg flex min-h-screen flex-col bg-transparent font-brand text-slate-800 antialiased selection:bg-brand/25 selection:text-brand-dark dark:text-slate-100"
    x-data="{ navOpen: false }"
    data-cart-url="{{ route('store.cart') }}"
    data-toast-view-cart="{{ __('aldawy.toast_view_cart') }}"
    data-toast-dismiss="{{ __('aldawy.toast_dismiss') }}"
>
    <div class="aldawy-top-accent" aria-hidden="true"></div>
    <livewire:store.price-notice-banner />

    @include('store.partials.navbar')

    <main class="flex-1">
        <div id="aldawy-toast-root" class="aldawy-toast-root" aria-live="polite" aria-atomic="true"></div>

        @include('store.partials.flash-status')

        @yield('content')
    </main>

    @include('store.partials.footer')
    <livewire:store.cart-preview-drawer />
    @livewireScripts
</body>
</html>
