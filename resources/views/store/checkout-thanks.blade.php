@extends('layouts.store')

@section('content')
    <div class="aldawy-hero-bg border-b border-slate-200 py-12 dark:border-slate-800">
        <div class="mx-auto max-w-2xl px-4 text-center">
            <h1 class="text-3xl font-bold text-slate-800 dark:text-white">{{ __('aldawy.checkout_thanks_title') }}</h1>
            <p class="mt-3 text-slate-600 dark:text-slate-300">{{ __('aldawy.checkout_thanks_sub', ['ref' => $order->reference]) }}</p>
        </div>
    </div>
    <div class="mx-auto max-w-2xl px-4 py-12">
        <div class="rounded-2xl border border-slate-200 bg-canvas p-6 text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200">
            <p class="text-sm">{{ __('aldawy.checkout_thanks_email') }}</p>
            <ul class="mt-4 space-y-2 text-sm">
                @foreach ($order->items as $item)
                    @php
                        $loc = app()->getLocale();
                        $name = '—';
                        if (is_array($item->product_name_snapshot)) {
                            $name = (string) ($item->product_name_snapshot[$loc] ?? $item->product_name_snapshot['en'] ?? $item->product_name_snapshot['ar'] ?? '—');
                        }
                    @endphp
                    <li class="flex justify-between gap-4 border-b border-slate-100 pb-2 last:border-0 dark:border-slate-800">
                        <span>{{ $name }}</span>
                        <span class="shrink-0 font-mono text-xs">{{ number_format((float) $item->line_total, 2) }}</span>
                    </li>
                @endforeach
            </ul>
            <dl class="mt-4 space-y-1 border-t border-slate-200 pt-4 text-sm dark:border-slate-700">
                <div class="flex justify-between gap-4 text-slate-600 dark:text-slate-300">
                    <dt>{{ __('aldawy.checkout_items_subtotal') }}</dt>
                    <dd class="font-semibold">{{ number_format((float) $order->subtotal, 2) }} {{ config('aldawy.currency', 'EGP') }}</dd>
                </div>
                <div class="flex justify-between gap-4 text-slate-600 dark:text-slate-300">
                    <dt>{{ __('aldawy.checkout_shipping') }}</dt>
                    <dd class="font-semibold">{{ number_format((float) $order->shipping_fee, 2) }} {{ config('aldawy.currency', 'EGP') }}</dd>
                </div>
            </dl>
            @if ($order->shipping_address_line1 || $order->city)
                <p class="mt-4 text-sm text-slate-600 dark:text-slate-300">
                    <span class="font-semibold text-slate-800 dark:text-slate-100">{{ __('aldawy.invoice_address') }}:</span>
                    {{ $order->shipping_address_line1 }}
                    @if ($order->shipping_address_line2)
                        — {{ $order->shipping_address_line2 }}
                    @endif
                    @if ($order->city)
                        <br>
                        <span class="font-semibold text-slate-800 dark:text-slate-100">{{ __('aldawy.invoice_city') }}:</span>
                        {{ $order->city->getTranslation('name', app()->getLocale()) }}
                    @endif
                </p>
            @endif
            <p class="mt-4 text-lg font-bold text-brand">
                {{ __('aldawy.invoice_total') }}: {{ number_format((float) $order->total, 2) }} {{ config('aldawy.currency', 'EGP') }}
            </p>
        </div>
        @if ($showGuestAccountNudge ?? false)
            <div class="mt-8 rounded-2xl border border-brand/30 bg-brand/5 p-6 text-center dark:bg-brand/10">
                <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ __('aldawy.checkout_guest_nudge_title') }}</p>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ __('aldawy.checkout_guest_nudge_sub') }}</p>
                <div class="mt-4 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('register', ['redirect' => url('/my')]) }}" class="rounded-xl bg-brand px-5 py-2.5 text-sm font-bold text-white hover:bg-brand-dark">
                        {{ __('aldawy.checkout_guest_register') }}
                    </a>
                    <a href="{{ route('login', ['redirect' => url('/my')]) }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-800 hover:bg-surface dark:border-slate-700 dark:text-white">
                        {{ __('aldawy.checkout_guest_login') }}
                    </a>
                </div>
            </div>
        @endif
        <div class="mt-8 flex flex-wrap justify-center gap-4">
            @if ($invoiceReady ?? false)
                <a href="{{ route('store.checkout.invoice') }}" class="rounded-xl border border-brand px-6 py-3 text-sm font-bold text-brand hover:bg-brand/10">
                    {{ __('aldawy.download_invoice') }}
                </a>
            @else
                <p class="w-full text-center text-sm text-slate-500 dark:text-slate-400">{{ __('aldawy.invoice_generating') }}</p>
            @endif
            <a href="{{ route('store.shop') }}" class="rounded-xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-800 hover:bg-surface dark:border-slate-700 dark:text-white dark:hover:bg-slate-800">{{ __('aldawy.cta_shop') }}</a>
            @auth
                <a href="{{ url('/my') }}" class="rounded-xl bg-brand px-6 py-3 text-sm font-bold text-white hover:bg-brand-dark">{{ __('aldawy.account_tile_orders') }}</a>
            @endauth
        </div>
    </div>
@endsection
