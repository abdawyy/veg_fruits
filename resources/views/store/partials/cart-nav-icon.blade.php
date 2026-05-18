@php
    $c = (int) ($cartLineCount ?? 0);
    $variant = $variant ?? 'desktop';
@endphp
<a
    href="{{ route('store.cart') }}"
    class="@if ($variant === 'drawer') flex w-full items-center gap-3 rounded-lg px-3 py-3 text-base font-medium text-slate-800 hover:bg-surface dark:text-slate-100 dark:hover:bg-slate-800 @else relative inline-flex items-center justify-center rounded-lg p-2 text-slate-600 transition hover:bg-surface hover:text-slate-800 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white @endif"
    aria-label="{{ __('aldawy.nav_cart') }}"
    @if ($variant === 'drawer') @click="navOpen = false" @endif
>
    <svg class="{{ $variant === 'drawer' ? 'h-6 w-6' : 'h-5 w-5' }} shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm12 0a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
    </svg>
    <span class="sr-only">{{ __('aldawy.nav_cart') }}</span>
    @if ($variant === 'drawer')
        <span class="flex-1 text-start">{{ __('aldawy.nav_cart') }}</span>
    @endif
    <span class="aldawy-cart-badge {{ $c > 0 ? '' : 'hidden' }} {{ $variant === 'drawer' ? 'relative ms-auto flex h-6 min-w-[1.25rem] items-center justify-center rounded-full bg-accent px-1.5 text-xs font-bold text-white' : 'absolute -end-0.5 -top-0.5 flex h-4 min-w-[1rem] items-center justify-center rounded-full bg-accent px-1 text-[10px] font-bold text-white' }}">
        <span class="aldawy-cart-count">{{ $c }}</span>
    </span>
</a>
