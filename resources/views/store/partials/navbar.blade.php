@php
    $locale = app()->getLocale();
    $cartCount = (int) ($cartLineCount ?? 0);
    $navItems = [
        ['route' => 'store.home', 'label' => __('aldawy.nav_home'), 'icon' => '🏠'],
        ['route' => 'store.shop', 'label' => __('aldawy.nav_shop'), 'icon' => '🛍️'],
        ['route' => 'store.fruits', 'label' => __('aldawy.nav_fruits'), 'icon' => '🍎'],
        ['route' => 'store.vegetables', 'label' => __('aldawy.nav_vegetables'), 'icon' => '🥬'],
        ['route' => 'store.boxes', 'label' => __('aldawy.nav_boxes'), 'icon' => '📦'],
        ['route' => 'store.services', 'label' => __('aldawy.nav_services'), 'icon' => '✨'],
    ];
    $isActive = fn (string $name, array $also = []) => request()->routeIs($name, ...$also) ? 'is-active' : '';
@endphp

{{-- Top trust strip --}}
<div class="hidden border-b border-brand/10 bg-brand/5 sm:block dark:border-brand/20 dark:bg-brand/10">
    <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-2 px-4 py-2 text-xs">
        <p class="font-semibold text-brand-dark dark:text-brand-light">
            <span aria-hidden="true">💵</span> {{ __('aldawy.trust_cod') }}
            <span class="mx-2 opacity-40">·</span>
            <span aria-hidden="true">🌐</span> {{ __('aldawy.trust_bilingual') }}
        </p>
        <div class="flex items-center gap-2">
            <button type="button" onclick="aldawyToggleTheme()" class="aldawy-nav-pill" title="Theme">
                <span class="dark:hidden">🌙</span>
                <span class="hidden dark:inline">☀️</span>
            </button>
            <a href="{{ request()->fullUrlWithQuery(['locale' => 'en']) }}" @class(['aldawy-nav-pill', 'is-active' => $locale === 'en'])>EN</a>
            <a href="{{ request()->fullUrlWithQuery(['locale' => 'ar']) }}" @class(['aldawy-nav-pill', 'is-active' => $locale === 'ar'])>عربي</a>
        </div>
    </div>
</div>

<header class="sticky top-0 z-50 border-b border-slate-200/80 aldawy-glass shadow-sm dark:border-slate-800/80">
    <div class="mx-auto max-w-6xl px-4 py-3 lg:py-3.5">
        <div class="grid grid-cols-[minmax(0,1fr)_auto] items-center gap-x-3 gap-y-3 lg:grid-cols-[auto_minmax(220px,1fr)_auto] lg:items-center lg:gap-x-6">
            {{-- Logo --}}
            <a href="{{ route('store.home') }}" class="aldawy-nav-logo group col-start-1 row-start-1 shrink-0">
                <span class="aldawy-nav-logo-mark" aria-hidden="true">🥬</span>
                <span class="hidden min-w-0 sm:block">
                    <span class="block text-base font-extrabold tracking-tight text-slate-800 dark:text-white">AL-DAWY</span>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-brand dark:text-brand-light">{{ __('aldawy.badge_fresh') }}</span>
                </span>
            </a>

            {{-- Mobile cart + menu --}}
            <div class="col-start-2 row-start-1 flex items-center gap-1 lg:hidden">
                <a href="{{ route('store.cart') }}" @class(['aldawy-nav-cart !px-2.5 !py-2', $isActive('store.cart')]) aria-label="{{ __('aldawy.nav_cart') }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm12 0a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                    </svg>
                    <span class="aldawy-cart-badge {{ $cartCount > 0 ? '' : 'hidden' }} aldawy-nav-cart-badge !static">
                        <span class="aldawy-cart-count">{{ $cartCount }}</span>
                    </span>
                </a>
                <button
                    type="button"
                    class="aldawy-nav-menu-btn"
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

            {{-- Search (single Livewire instance) --}}
            <div class="col-span-2 row-start-2 min-w-0 w-full lg:col-span-1 lg:col-start-2 lg:row-start-1">
                <livewire:store.product-search-bar />
            </div>

            {{-- Desktop actions --}}
            <div class="col-start-3 row-start-1 hidden min-w-0 items-center justify-end gap-2 lg:flex">
                <nav class="me-1 flex flex-wrap items-center justify-end gap-0.5 xl:gap-1" aria-label="{{ __('aldawy.nav_browse') }}">
                    @foreach ($navItems as $item)
                        <a
                            href="{{ route($item['route']) }}"
                            @class(['aldawy-nav-link hidden xl:inline-flex', $isActive($item['route'])])
                        >
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                    {{-- Compact browse dropdown on lg (xl shows all links) --}}
                    <div class="relative xl:hidden" x-data="{ browseOpen: false }" @click.outside="browseOpen = false">
                        <button
                            type="button"
                            @class(['aldawy-nav-link inline-flex', request()->routeIs('store.shop', 'store.fruits', 'store.vegetables', 'store.boxes', 'store.services') ? 'is-active' : ''])
                            @click="browseOpen = !browseOpen"
                            :aria-expanded="browseOpen.toString()"
                            aria-haspopup="menu"
                        >
                            {{ __('aldawy.nav_browse') }}
                            <svg class="h-4 w-4 shrink-0 transition-transform" :class="{ 'rotate-180': browseOpen }" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                        <div
                            x-cloak
                            x-show="browseOpen"
                            x-transition
                            class="absolute end-0 top-full z-[120] mt-1 min-w-[13rem] rounded-xl border border-slate-200 bg-white py-1 shadow-xl dark:border-slate-600 dark:bg-slate-900"
                            role="menu"
                        >
                            @foreach ($navItems as $item)
                                @if ($item['route'] !== 'store.home')
                                    <a href="{{ route($item['route']) }}" role="menuitem" class="block px-4 py-2.5 text-sm text-slate-800 hover:bg-slate-50 dark:text-slate-100 dark:hover:bg-slate-800" @click="browseOpen = false">{{ $item['label'] }}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </nav>

                <a href="{{ route('store.cart') }}" @class(['aldawy-nav-cart', $isActive('store.cart')])>
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm12 0a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                    </svg>
                    <span class="hidden xl:inline">{{ __('aldawy.nav_cart') }}</span>
                    <span class="aldawy-cart-badge {{ $cartCount > 0 ? '' : 'hidden' }} aldawy-nav-cart-badge">
                        <span class="aldawy-cart-count">{{ $cartCount }}</span>
                    </span>
                </a>

                @auth
                    <a href="{{ url('/my') }}" @class(['aldawy-nav-account', request()->is('my', 'my/*') ? 'is-active' : ''])>
                        {{ __('aldawy.nav_account') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="aldawy-nav-account">{{ __('aldawy.nav_login') }}</a>
                @endauth
            </div>
        </div>
    </div>
</header>

{{-- Mobile drawer --}}
<div
    x-cloak
    x-show="navOpen"
    x-transition.opacity
    class="fixed inset-0 z-[65] bg-slate-900/60 backdrop-blur-sm lg:hidden"
    @click="navOpen = false"
    aria-hidden="true"
></div>
<div
    id="aldawy-store-nav-drawer"
    x-cloak
    x-show="navOpen"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="translate-x-full rtl:-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="translate-x-full rtl:-translate-x-full"
    class="aldawy-nav-drawer lg:hidden"
    role="dialog"
    aria-modal="true"
    aria-label="{{ __('aldawy.nav_menu') }}"
>
    <div class="flex items-center justify-between border-b border-slate-200 px-4 py-4 dark:border-slate-800">
        <div class="flex items-center gap-2">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-brand to-brand-dark text-lg">🥬</span>
            <span class="text-lg font-extrabold text-slate-900 dark:text-white">AL-DAWY</span>
        </div>
        <button type="button" class="rounded-xl p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800" @click="navOpen = false" aria-label="{{ __('aldawy.nav_menu_close') }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
        </button>
    </div>

    <nav class="flex flex-1 flex-col overflow-y-auto px-4 py-4" @click.stop>
        <a href="{{ route('store.cart') }}" class="aldawy-nav-drawer-cart mb-4" @click="navOpen = false">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm12 0a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
            </svg>
            <span>{{ __('aldawy.nav_cart') }}</span>
            <span class="aldawy-cart-badge {{ $cartCount > 0 ? '' : 'hidden' }} aldawy-nav-cart-badge ms-auto">
                <span class="aldawy-cart-count">{{ $cartCount }}</span>
            </span>
        </a>

        <p class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('aldawy.nav_browse') }}</p>
        <div class="grid grid-cols-2 gap-2">
            @foreach ($navItems as $item)
                <a
                    href="{{ route($item['route']) }}"
                    @class(['aldawy-nav-drawer-tile', $isActive($item['route'])])
                    @click="navOpen = false"
                >
                    <span class="text-xl" aria-hidden="true">{{ $item['icon'] }}</span>
                    <span class="text-sm font-semibold">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>

        <div class="mt-6 space-y-1 border-t border-slate-200 pt-4 dark:border-slate-800">
            @auth
                <a href="{{ url('/my') }}" class="aldawy-nav-drawer-link" @click="navOpen = false">{{ __('aldawy.nav_account') }}</a>
                @if(auth()->user()->is_admin)
                    <a href="{{ url('/admin') }}" class="aldawy-nav-drawer-link text-accent" @click="navOpen = false">{{ __('aldawy.admin_portal') }}</a>
                @endif
                <form action="{{ route('logout') }}" method="post" class="pt-2">
                    @csrf
                    <button type="submit" class="aldawy-nav-drawer-link w-full text-start text-danger">{{ __('aldawy.nav_logout') }}</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="aldawy-nav-drawer-link" @click="navOpen = false">{{ __('aldawy.nav_login') }}</a>
                <a href="{{ route('register') }}" class="aldawy-nav-drawer-link font-bold text-brand" @click="navOpen = false">{{ __('aldawy.nav_register') }}</a>
            @endauth
        </div>

        <div class="mt-auto flex flex-wrap items-center gap-2 border-t border-slate-200 py-4 dark:border-slate-800">
            <button type="button" onclick="aldawyToggleTheme()" class="aldawy-nav-pill">
                <span class="dark:hidden">🌙</span>
                <span class="hidden dark:inline">☀️</span>
            </button>
            <a href="{{ request()->fullUrlWithQuery(['locale' => 'en']) }}" @class(['aldawy-nav-pill', 'is-active' => $locale === 'en'])>EN</a>
            <a href="{{ request()->fullUrlWithQuery(['locale' => 'ar']) }}" @class(['aldawy-nav-pill', 'is-active' => $locale === 'ar'])>عربي</a>
        </div>
    </nav>
</div>
