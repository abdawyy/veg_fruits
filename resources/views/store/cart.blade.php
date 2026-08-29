@extends('layouts.store')

@section('content')
    @include('store.partials.trust-bar')

    <div class="aldawy-hero-bg border-b border-slate-200 py-10 dark:border-slate-800">
        <div class="aldawy-hero-inner mx-auto max-w-6xl px-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="aldawy-kicker mb-3">{{ __('aldawy.badge_fresh') }}</p>
                    <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">{{ __('aldawy.cart_title') }}</h1>
                    <p class="mt-2 max-w-xl text-slate-600 dark:text-slate-300">{{ __('aldawy.cart_sub') }}</p>
                </div>
                @if ($rows->isNotEmpty())
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="aldawy-trust-pill">
                            🛒 {{ trans_choice('aldawy.cart_items_count', $rows->count(), ['count' => $rows->count()]) }}
                        </span>
                        <span class="aldawy-trust-pill">
                            💵 {{ __('aldawy.trust_cod') }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-6xl px-4 py-10 sm:py-12">
        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-danger/30 bg-danger/5 px-5 py-4 text-sm text-danger dark:bg-danger/10">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($priceChangedLines->isNotEmpty())
            <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50/90 px-5 py-4 text-sm text-amber-900 dark:border-amber-800 dark:bg-amber-950/50 dark:text-amber-100">
                <p class="font-bold">{{ __('aldawy.cart_price_changed_title') }}</p>
                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach ($priceChangedLines as $change)
                        <li>
                            {{ $change['product']->getTranslation('name', app()->getLocale()) }}:
                            {{ __('aldawy.cart_price_changed_line', [
                                'old' => number_format((float) $change['snapshot'], 2),
                                'new' => number_format((float) $change['current'], 2),
                                'currency' => config('aldawy.currency', 'EGP'),
                                'unit' => ($change['unit'] ?? 'kg') === 'piece' ? __('aldawy.per_piece') : __('aldawy.per_kg'),
                            ]) }}
                        </li>
                    @endforeach
                </ul>
                <p class="mt-2 text-xs opacity-80">{{ __('aldawy.cart_price_changed_hint') }}</p>
            </div>
        @endif

        @if ($rows->isEmpty())
            <div class="aldawy-card mx-auto max-w-lg p-10 text-center">
                <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-brand/10 text-5xl" aria-hidden="true">🛒</div>
                <h2 class="mt-6 text-xl font-bold text-slate-800 dark:text-white">{{ __('aldawy.cart_empty') }}</h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ __('aldawy.cart_empty_sub') }}</p>
                <a href="{{ route('store.shop') }}" class="aldawy-btn-primary mt-8">
                    {{ __('aldawy.cta_shop') }}
                </a>
            </div>
        @else
            <div class="grid gap-8 lg:grid-cols-12 lg:items-start">
                {{-- Line items --}}
                <div class="space-y-4 lg:col-span-7">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h2 class="text-lg font-bold text-slate-800 dark:text-white">{{ __('aldawy.cart_your_items') }}</h2>
                        <form method="post" action="{{ route('store.cart.clear') }}" onsubmit="return confirm('{{ __('aldawy.cart_clear_confirm') }}');">
                            @csrf
                            <button type="submit" class="text-sm font-semibold text-danger transition hover:underline">{{ __('aldawy.cart_clear') }}</button>
                        </form>
                    </div>

                    @foreach ($rows as $row)
                        @include('store.partials.cart-line-item', ['row' => $row])
                    @endforeach

                    {{-- Coupon (mobile: below items; desktop: also shown in sidebar) --}}
                    <div class="aldawy-card p-5 lg:hidden">
                        @include('store.partials.cart-coupon', [
                            'appliedCoupon' => $appliedCoupon,
                            'couponCode' => $couponCode,
                            'discountAmount' => $discountAmount,
                        ])
                    </div>
                </div>

                {{-- Sticky checkout sidebar --}}
                <div class="aldawy-cart-sidebar space-y-5 lg:col-span-5">
                    {{-- Order summary --}}
                    <div class="aldawy-card overflow-hidden">
                        <div class="border-b border-slate-100 bg-gradient-to-r from-brand/10 to-transparent px-5 py-4 dark:border-slate-800 dark:from-brand/15">
                            <h2 class="text-lg font-bold text-slate-800 dark:text-white">{{ __('aldawy.checkout_summary') }}</h2>
                        </div>
                        <div class="space-y-3 px-5 py-4 text-sm">
                            <div class="flex justify-between gap-4 text-slate-600 dark:text-slate-300">
                                <span>{{ __('aldawy.checkout_items_subtotal') }}</span>
                                <span class="font-bold text-slate-800 dark:text-white">
                                    {{ number_format((float) $subtotal, 2) }} {{ config('aldawy.currency', 'EGP') }}
                                </span>
                            </div>
                            @if (bccomp($discountAmount ?? '0', '0', 4) > 0)
                                <div class="flex justify-between gap-4 text-success">
                                    <span>{{ __('aldawy.checkout_discount') }}</span>
                                    <span class="font-bold">−{{ number_format((float) $discountAmount, 2) }} {{ config('aldawy.currency', 'EGP') }}</span>
                                </div>
                            @endif
                            @if ($cities->isNotEmpty())
                                @php
                                    $selectedCityId = (string) old('city_id', $defaultCityId ?? $cities->first()?->id);
                                    $selectedCity = $cities->firstWhere('id', (int) $selectedCityId) ?? $cities->first();
                                @endphp
                                <div class="flex justify-between gap-4 text-slate-600 dark:text-slate-300">
                                    <span>{{ __('aldawy.checkout_shipping') }}</span>
                                    <span class="font-bold text-slate-800 dark:text-white">
                                        <span class="aldawy-checkout-shipping-display" id="aldawy-checkout-shipping-sidebar">{{ number_format((float) ($selectedCity->shipping_fee ?? 0), 2) }}</span>
                                        {{ config('aldawy.currency', 'EGP') }}
                                    </span>
                                </div>
                                <div class="border-t border-slate-100 pt-3 dark:border-slate-800">
                                    <div class="flex justify-between gap-4">
                                        <span class="font-bold text-slate-800 dark:text-white">{{ __('aldawy.checkout_grand_total') }}</span>
                                        <span class="text-2xl font-extrabold text-brand">
                                            <span class="aldawy-checkout-total-display" id="aldawy-checkout-total-sidebar">{{ number_format(max(0, (float) $subtotal - (float) ($discountAmount ?? 0) + (float) ($selectedCity->shipping_fee ?? 0)), 2) }}</span>
                                            <span class="text-sm font-semibold text-slate-500">{{ config('aldawy.currency', 'EGP') }}</span>
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div class="border-t border-slate-100 pt-3 dark:border-slate-800">
                                    <div class="flex justify-between gap-4">
                                        <span class="font-bold text-slate-800 dark:text-white">{{ __('aldawy.cart_subtotal') }}</span>
                                        <span class="text-2xl font-extrabold text-brand">{{ number_format((float) $subtotal, 2) }} {{ config('aldawy.currency', 'EGP') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="border-t border-slate-100 bg-surface/80 px-5 py-3 dark:border-slate-800 dark:bg-slate-800/40">
                            <p class="flex items-center gap-2 text-xs font-semibold text-brand dark:text-brand-light">
                                <span aria-hidden="true">💵</span>
                                {{ __('aldawy.checkout_cod') }}
                            </p>
                        </div>
                    </div>

                    {{-- Coupon desktop --}}
                    <div class="aldawy-card hidden p-5 lg:block">
                        @include('store.partials.cart-coupon', [
                            'appliedCoupon' => $appliedCoupon,
                            'couponCode' => $couponCode,
                            'discountAmount' => $discountAmount,
                        ])
                    </div>

                    {{-- Checkout --}}
                    @if ($cities->isEmpty())
                        <div class="rounded-2xl border border-amber-200 bg-amber-50/90 p-5 text-sm text-amber-900 dark:border-amber-800 dark:bg-amber-950/50 dark:text-amber-100">
                            {{ __('aldawy.checkout_no_cities') }}
                        </div>
                    @else
                        <div class="aldawy-card p-5">
                            <h2 class="text-lg font-bold text-slate-800 dark:text-white">{{ __('aldawy.checkout_title') }}</h2>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('aldawy.checkout_sub') }}</p>
                            <form id="aldawy-checkout-form" method="post" action="{{ route('store.checkout.store') }}" class="mt-5 space-y-4">
                                @csrf
                                <input type="hidden" name="checkout_nonce" value="{{ session('checkout_nonce') }}">
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-slate-700 dark:text-slate-200" for="city_id">{{ __('aldawy.checkout_city') }}</label>
                                    <select id="city_id" name="city_id" required class="aldawy-input">
                                        @foreach ($cities as $city)
                                            <option
                                                value="{{ $city->id }}"
                                                data-fee-cents="{{ $shippingCentsByCity[$city->id] ?? 0 }}"
                                                data-fee="{{ $city->shipping_fee }}"
                                                @selected($selectedCityId === (string) $city->id)
                                            >
                                                {{ $city->getTranslation('name', app()->getLocale()) }}
                                                — {{ number_format((float) $city->shipping_fee, 2) }} {{ config('aldawy.currency', 'EGP') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-xs text-slate-500">{{ __('aldawy.checkout_city_help') }}</p>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-slate-700 dark:text-slate-200" for="shipping_address_line1">{{ __('aldawy.checkout_address1') }}</label>
                                    <input id="shipping_address_line1" name="shipping_address_line1" type="text" required value="{{ $defaultAddressLine1 }}" class="aldawy-input">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-slate-700 dark:text-slate-200" for="shipping_address_line2">{{ __('aldawy.checkout_address2') }}</label>
                                    <input id="shipping_address_line2" name="shipping_address_line2" type="text" value="{{ $defaultAddressLine2 }}" class="aldawy-input">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-slate-700 dark:text-slate-200" for="customer_phone">{{ __('aldawy.checkout_phone') }}</label>
                                    <input id="customer_phone" name="customer_phone" type="text" required value="{{ $defaultPhone }}" class="aldawy-input">
                                </div>
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-sm font-semibold text-slate-700 dark:text-slate-200" for="customer_name">{{ __('aldawy.checkout_name') }}</label>
                                        <input id="customer_name" name="customer_name" type="text" value="{{ $defaultName }}" class="aldawy-input">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-semibold text-slate-700 dark:text-slate-200" for="customer_email">{{ __('aldawy.checkout_email') }}</label>
                                        <input id="customer_email" name="customer_email" type="email" value="{{ $defaultEmail }}" class="aldawy-input">
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-slate-700 dark:text-slate-200" for="notes">{{ __('aldawy.checkout_notes') }}</label>
                                    <textarea id="notes" name="notes" rows="2" class="aldawy-input">{{ old('notes') }}</textarea>
                                </div>
                                @if (count($paymentOptions) > 1)
                                    <fieldset>
                                        <legend class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('aldawy.checkout_payment') }}</legend>
                                        <div class="mt-2 space-y-2">
                                            @foreach ($paymentOptions as $id => $label)
                                                <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm transition has-[:checked]:border-brand has-[:checked]:bg-brand/5 dark:border-slate-700 dark:has-[:checked]:bg-brand/10">
                                                    <input type="radio" name="payment_method" value="{{ $id }}" @checked(old('payment_method', 'cod') === $id) required class="text-brand focus:ring-brand">
                                                    <span>{{ $label }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </fieldset>
                                @else
                                    <input type="hidden" name="payment_method" value="cod">
                                @endif
                                <button id="aldawy-checkout-submit" type="submit" class="aldawy-btn-primary w-full !py-3.5 disabled:cursor-not-allowed disabled:opacity-60">
                                    {{ __('aldawy.checkout_submit') }}
                                </button>
                            </form>
                        </div>

                        {{-- Hidden elements for JS sync (legacy ids) --}}
                        <span id="aldawy-checkout-subtotal" class="hidden" data-cents="{{ $subtotalCents }}" data-currency="{{ config('aldawy.currency', 'EGP') }}"></span>
                        <span id="aldawy-checkout-shipping" class="hidden"></span>
                        <span id="aldawy-checkout-total" class="hidden"></span>

                        <script>
                            (function () {
                                var form = document.getElementById('aldawy-checkout-form');
                                var btn = document.getElementById('aldawy-checkout-submit');
                                if (form && btn) {
                                    form.addEventListener('submit', function () {
                                        btn.disabled = true;
                                        btn.setAttribute('aria-disabled', 'true');
                                    });
                                }
                                var sel = document.getElementById('city_id');
                                var subEl = document.getElementById('aldawy-checkout-subtotal');
                                if (!sel || !subEl) return;
                                var formatCents = window.aldawyFormatCents || function (c) { return (c / 100).toFixed(2); };
                                var sumCents = window.aldawySumCents || function () {
                                    return Array.prototype.slice.call(arguments).reduce(function (a, b) { return a + (Number(b) || 0); }, 0);
                                };
                                var subCents = parseInt(subEl.getAttribute('data-cents') || '0', 10) || 0;
                                var discountCents = {{ (int) ($discountCents ?? 0) }};
                                function sync() {
                                    var opt = sel.options[sel.selectedIndex];
                                    var feeCents = opt ? parseInt(opt.getAttribute('data-fee-cents') || '0', 10) : 0;
                                    if (isNaN(feeCents)) feeCents = 0;
                                    var feeText = formatCents(feeCents);
                                    document.querySelectorAll('.aldawy-checkout-shipping-display').forEach(function (el) {
                                        el.textContent = feeText;
                                    });
                                    var totalCents = Math.max(0, sumCents(subCents, -discountCents, feeCents));
                                    var totalText = formatCents(totalCents);
                                    document.querySelectorAll('.aldawy-checkout-total-display').forEach(function (el) {
                                        el.textContent = totalText;
                                    });
                                }
                                sel.addEventListener('change', sync);
                                sync();
                            })();
                        </script>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
