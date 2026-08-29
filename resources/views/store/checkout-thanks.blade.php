@extends('layouts.store')

@section('content')
    @include('store.partials.trust-bar')

    <div class="aldawy-hero-bg border-b border-slate-200 py-14 dark:border-slate-800">
        <div class="mx-auto max-w-2xl px-4 text-center">
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-brand/15 text-4xl ring-4 ring-brand/20" aria-hidden="true">✓</div>
            <h1 class="mt-6 text-3xl font-extrabold text-slate-800 dark:text-white sm:text-4xl">{{ __('aldawy.checkout_thanks_title') }}</h1>
            <p class="mt-3 text-slate-600 dark:text-slate-300">{{ __('aldawy.checkout_thanks_sub', ['ref' => $order->reference]) }}</p>
            <p class="mt-4 inline-flex items-center gap-2 rounded-full border border-brand/20 bg-brand/5 px-4 py-2 text-sm font-semibold text-brand dark:bg-brand/10 dark:text-brand-light">
                <span aria-hidden="true">💵</span> {{ __('aldawy.checkout_cod') }}
            </p>
        </div>
    </div>

    <div class="mx-auto max-w-2xl px-4 py-12">
        <div class="aldawy-card overflow-hidden !p-0">
            <div class="border-b border-slate-100 bg-gradient-to-r from-brand/5 to-transparent px-6 py-4 dark:border-slate-800 dark:from-brand/10">
                <h2 class="font-bold text-slate-800 dark:text-white">{{ __('aldawy.checkout_summary') }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ __('aldawy.checkout_thanks_email') }}</p>
            </div>
            <div class="px-6 py-5">
                <ul class="space-y-3 text-sm">
                    @foreach ($order->items as $item)
                        @php
                            $loc = app()->getLocale();
                            $name = '—';
                            if (is_array($item->product_name_snapshot)) {
                                $name = (string) ($item->product_name_snapshot[$loc] ?? $item->product_name_snapshot['en'] ?? $item->product_name_snapshot['ar'] ?? '—');
                            }
                        @endphp
                        <li class="flex justify-between gap-4 border-b border-slate-100 pb-3 last:border-0 dark:border-slate-800">
                            <span class="font-medium text-slate-700 dark:text-slate-200">{{ $name }}</span>
                            <span class="shrink-0 font-bold text-slate-800 dark:text-white">{{ number_format((float) $item->line_total, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
                <dl class="mt-5 space-y-2 border-t border-slate-100 pt-5 text-sm dark:border-slate-800">
                    <div class="flex justify-between gap-4 text-slate-600 dark:text-slate-300">
                        <dt>{{ __('aldawy.checkout_items_subtotal') }}</dt>
                        <dd class="font-semibold">{{ number_format((float) $order->subtotal, 2) }} {{ config('aldawy.currency', 'EGP') }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 text-slate-600 dark:text-slate-300">
                        <dt>{{ __('aldawy.checkout_shipping') }}</dt>
                        <dd class="font-semibold">{{ number_format((float) $order->shipping_fee, 2) }} {{ config('aldawy.currency', 'EGP') }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 border-t border-slate-100 pt-3 dark:border-slate-800">
                        <dt class="font-bold text-slate-800 dark:text-white">{{ __('aldawy.invoice_total') }}</dt>
                        <dd class="text-xl font-extrabold text-brand">{{ number_format((float) $order->total, 2) }} {{ config('aldawy.currency', 'EGP') }}</dd>
                    </div>
                </dl>
                @if ($order->shipping_address_line1 || $order->city)
                    <div class="mt-5 rounded-xl bg-surface p-4 text-sm dark:bg-slate-800">
                        <p class="font-bold text-slate-800 dark:text-white">{{ __('aldawy.invoice_address') }}</p>
                        <p class="mt-1 text-slate-600 dark:text-slate-300">
                            {{ $order->shipping_address_line1 }}
                            @if ($order->shipping_address_line2)— {{ $order->shipping_address_line2 }}@endif
                        </p>
                        @if ($order->city)
                            <p class="mt-2 font-semibold text-slate-700 dark:text-slate-200">{{ $order->city->getTranslation('name', app()->getLocale()) }}</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        @if ($showGuestAccountNudge ?? false)
            <div class="aldawy-card mt-8 border-brand/20 bg-brand/5 p-6 text-center dark:bg-brand/10">
                <p class="font-bold text-slate-800 dark:text-white">{{ __('aldawy.checkout_guest_nudge_title') }}</p>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ __('aldawy.checkout_guest_nudge_sub') }}</p>
                <div class="mt-5 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('register', ['redirect' => url('/my')]) }}" class="aldawy-btn-primary">{{ __('aldawy.checkout_guest_register') }}</a>
                    <a href="{{ route('login', ['redirect' => url('/my')]) }}" class="aldawy-btn-secondary">{{ __('aldawy.checkout_guest_login') }}</a>
                </div>
            </div>
        @endif

        <div class="mt-8 flex flex-wrap justify-center gap-3">
            @if ($invoiceReady ?? false)
                <a href="{{ route('store.checkout.invoice') }}" class="aldawy-btn-secondary">{{ __('aldawy.download_invoice') }}</a>
            @else
                <p class="w-full text-center text-sm text-slate-500 dark:text-slate-400">{{ __('aldawy.invoice_generating') }}</p>
            @endif
            <a href="{{ route('store.shop') }}" class="aldawy-btn-secondary">{{ __('aldawy.cta_shop') }}</a>
            @auth
                <a href="{{ url('/my') }}" class="aldawy-btn-primary">{{ __('aldawy.account_tile_orders') }}</a>
            @endauth
        </div>
    </div>
@endsection
