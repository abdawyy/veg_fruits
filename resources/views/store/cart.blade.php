@extends('layouts.store')

@section('content')
    <div class="aldawy-hero-bg border-b border-slate-200 py-10 dark:border-slate-800">
        <div class="mx-auto max-w-6xl px-4">
            <h1 class="text-3xl font-bold text-slate-800 dark:text-white">{{ __('aldawy.cart_title') }}</h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400">{{ __('aldawy.cart_sub') }}</p>
        </div>
    </div>

    <div class="mx-auto max-w-6xl px-4 py-12">
        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-danger/30 bg-danger/5 px-4 py-3 text-sm text-danger dark:bg-danger/10">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if ($rows->isEmpty())
            <p class="rounded-2xl border border-dashed border-slate-200 bg-surface p-12 text-center text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400">
                {{ __('aldawy.cart_empty') }}
                <br>
                <a href="{{ route('store.shop') }}" class="mt-4 inline-block font-semibold text-brand hover:text-brand-dark">{{ __('aldawy.cta_shop') }}</a>
            </p>
        @else
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white/90 shadow-sm backdrop-blur-md dark:border-slate-800 dark:bg-slate-900/90">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                    <thead class="bg-surface dark:bg-slate-800/80">
                        <tr>
                            <th class="px-4 py-3 text-start text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('aldawy.cart_product') }}</th>
                            <th class="px-4 py-3 text-start text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('aldawy.cart_options_col') }}</th>
                            <th class="px-4 py-3 text-end text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('aldawy.cart_qty') }}</th>
                            <th class="px-4 py-3 text-end text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('aldawy.cart_line') }}</th>
                            <th class="px-4 py-3 text-end text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($rows as $row)
                            @php
                                /** @var \App\Models\Product $product */
                                $product = $row['product'];
                                $line = (int) $row['line'];
                            @endphp
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($product->display_image_url)
                                            <img src="{{ $product->display_image_url }}" alt="" class="h-14 w-14 rounded-lg object-cover ring-1 ring-slate-200 dark:ring-slate-700">
                                        @endif
                                        <div>
                                            <p class="font-semibold text-slate-800 dark:text-white">{{ $product->getTranslation('name', app()->getLocale()) }}</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $product->sku }}</p>
                                            <p class="mt-1 text-xs text-slate-600 dark:text-slate-300">{{ number_format((float) $product->price_per_kg, 2) }} {{ config('aldawy.currency', 'EGP') }} / {{ __('aldawy.per_kg') }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="max-w-[14rem] px-4 py-4 align-top text-xs text-slate-600 dark:text-slate-300">
                                    @if ($row['preparation_services']->isNotEmpty())
                                        <p class="font-semibold text-slate-700 dark:text-slate-200">{{ __('aldawy.cart_line_prep') }}</p>
                                        <ul class="mt-1 list-inside list-disc text-slate-500 dark:text-slate-400">
                                            @foreach ($row['preparation_services'] as $svc)
                                                <li>{{ $svc->getTranslation('name', app()->getLocale()) }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    @if ($row['packaging_type'])
                                        <p class="mt-2 font-semibold text-slate-700 dark:text-slate-200">{{ __('aldawy.cart_line_pack') }}</p>
                                        <p class="text-slate-500 dark:text-slate-400">{{ $row['packaging_type']->getTranslation('name', app()->getLocale()) }}</p>
                                    @endif
                                    @if ($row['preparation_services']->isEmpty() && ! $row['packaging_type'])
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-end">
                                    <form method="post" action="{{ route('store.cart.update') }}" class="inline-flex items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="line" value="{{ $line }}">
                                        <input type="number" name="kg" step="0.25" min="0.25" value="{{ $row['kg'] }}"
                                            class="w-24 rounded-lg border border-slate-200 px-2 py-1 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                        <button type="submit" class="rounded-lg bg-surface px-2 py-1 text-xs font-semibold text-brand hover:bg-brand/10 dark:bg-slate-800">{{ __('aldawy.cart_update') }}</button>
                                    </form>
                                </td>
                                <td class="px-4 py-4 text-end align-top">
                                    <p class="font-semibold text-brand">{{ number_format((float) $row['line_subtotal'], 2) }}</p>
                                    @if (bccomp((string) $row['services_surcharge'], '0', 4) > 0 || bccomp((string) $row['packaging_surcharge'], '0', 4) > 0)
                                        <p class="mt-1 text-[10px] text-slate-400">
                                            {{ __('aldawy.cart_line_base') }} {{ number_format((float) $row['line_base'], 2) }}
                                            @if (bccomp((string) $row['services_surcharge'], '0', 4) > 0)
                                                + {{ __('aldawy.cart_line_svc') }} {{ number_format((float) $row['services_surcharge'], 2) }}
                                            @endif
                                            @if (bccomp((string) $row['packaging_surcharge'], '0', 4) > 0)
                                                + {{ __('aldawy.cart_line_pkg_fee') }} {{ number_format((float) $row['packaging_surcharge'], 2) }}
                                            @endif
                                        </p>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-end">
                                    <form method="post" action="{{ route('store.cart.remove') }}" onsubmit="return confirm('{{ __('aldawy.cart_remove_confirm') }}');">
                                        @csrf
                                        <input type="hidden" name="line" value="{{ $line }}">
                                        <button type="submit" class="text-sm font-semibold text-danger hover:underline">{{ __('aldawy.cart_remove') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="flex flex-wrap items-center justify-between gap-4 border-t border-slate-200 bg-surface px-4 py-4 dark:border-slate-800 dark:bg-slate-800/50">
                    <form method="post" action="{{ route('store.cart.clear') }}" onsubmit="return confirm('{{ __('aldawy.cart_clear_confirm') }}');">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-danger hover:underline">{{ __('aldawy.cart_clear') }}</button>
                    </form>
                    <p class="text-lg font-bold text-slate-800 dark:text-white">
                        {{ __('aldawy.cart_subtotal') }}:
                        <span class="text-brand">{{ number_format((float) $subtotal, 2) }} {{ config('aldawy.currency', 'EGP') }}</span>
                    </p>
                </div>
            </div>

            <div class="mt-10 grid gap-8 lg:grid-cols-2">
                @if ($cities->isEmpty())
                    <div class="rounded-2xl border border-amber-200 bg-amber-50/90 p-6 text-sm text-amber-900 backdrop-blur-md dark:border-amber-800 dark:bg-amber-950/50 dark:text-amber-100 lg:col-span-2">
                        {{ __('aldawy.checkout_no_cities') }}
                    </div>
                @else
                    <div class="rounded-2xl border border-slate-200 bg-white/90 p-6 backdrop-blur-md dark:border-slate-800 dark:bg-slate-900/90">
                        <h2 class="text-lg font-bold text-slate-800 dark:text-white">{{ __('aldawy.checkout_title') }}</h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('aldawy.checkout_sub') }}</p>
                        <form id="aldawy-checkout-form" method="post" action="{{ route('store.checkout.store') }}" class="mt-6 space-y-4">
                            @csrf
                            <input type="hidden" name="checkout_nonce" value="{{ session('checkout_nonce') }}">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200" for="city_id">{{ __('aldawy.checkout_city') }}</label>
                                <select
                                    id="city_id"
                                    name="city_id"
                                    required
                                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                                >
                                    @foreach ($cities as $city)
                                        <option
                                            value="{{ $city->id }}"
                                            data-fee="{{ $city->shipping_fee }}"
                                            @selected((string) old('city_id', $cities->first()->id) === (string) $city->id)
                                        >
                                            {{ $city->getTranslation('name', app()->getLocale()) }}
                                            — {{ __('aldawy.checkout_shipping') }} {{ number_format((float) $city->shipping_fee, 2) }} {{ config('aldawy.currency', 'EGP') }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ __('aldawy.checkout_city_help') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200" for="shipping_address_line1">{{ __('aldawy.checkout_address1') }}</label>
                                <input id="shipping_address_line1" name="shipping_address_line1" type="text" required value="{{ old('shipping_address_line1') }}"
                                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200" for="shipping_address_line2">{{ __('aldawy.checkout_address2') }}</label>
                                <input id="shipping_address_line2" name="shipping_address_line2" type="text" value="{{ old('shipping_address_line2') }}"
                                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200" for="customer_phone">{{ __('aldawy.checkout_phone') }}</label>
                                <input id="customer_phone" name="customer_phone" type="text" required value="{{ old('customer_phone', auth()->user()?->phone_number ?? '') }}"
                                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200" for="customer_name">{{ __('aldawy.checkout_name') }}</label>
                                <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name', auth()->user()?->name ?? '') }}"
                                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200" for="customer_email">{{ __('aldawy.checkout_email') }}</label>
                                <input id="customer_email" name="customer_email" type="email" value="{{ old('customer_email', auth()->user()?->email ?? '') }}"
                                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200" for="notes">{{ __('aldawy.checkout_notes') }}</label>
                                <textarea id="notes" name="notes" rows="3" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">{{ old('notes') }}</textarea>
                            </div>
                            <button id="aldawy-checkout-submit" type="submit" class="w-full rounded-xl bg-brand py-3 text-sm font-bold text-white shadow-sm transition hover:bg-brand-dark disabled:cursor-not-allowed disabled:opacity-60">
                                {{ __('aldawy.checkout_submit') }}
                            </button>
                        </form>
                        <script>
                            (function () {
                                var form = document.getElementById('aldawy-checkout-form');
                                var btn = document.getElementById('aldawy-checkout-submit');
                                if (!form || !btn) return;
                                form.addEventListener('submit', function () {
                                    btn.disabled = true;
                                    btn.setAttribute('aria-disabled', 'true');
                                });
                            })();
                        </script>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white/85 p-6 backdrop-blur-md dark:border-slate-800 dark:bg-slate-900/85">
                        <h2 class="text-lg font-bold text-slate-800 dark:text-white">{{ __('aldawy.checkout_summary') }}</h2>
                        <dl class="mt-4 space-y-2 text-sm">
                            <div class="flex justify-between gap-4 text-slate-600 dark:text-slate-300">
                                <dt>{{ __('aldawy.checkout_items_subtotal') }}</dt>
                                <dd class="font-semibold text-slate-800 dark:text-slate-100">
                                    <span id="aldawy-checkout-subtotal" data-amount="{{ (float) $subtotal }}" data-currency="{{ config('aldawy.currency', 'EGP') }}">{{ number_format((float) $subtotal, 2) }}</span>
                                    {{ config('aldawy.currency', 'EGP') }}
                                </dd>
                            </div>
                            <div class="flex justify-between gap-4 text-slate-600 dark:text-slate-300">
                                <dt>{{ __('aldawy.checkout_shipping') }}</dt>
                                <dd class="font-semibold text-slate-800 dark:text-slate-100">
                                    <span class="aldawy-checkout-shipping-display" id="aldawy-checkout-shipping">{{ number_format((float) ($cities->first()->shipping_fee ?? 0), 2) }}</span>
                                    {{ config('aldawy.currency', 'EGP') }}
                                </dd>
                            </div>
                        </dl>
                        <p class="mt-6 text-2xl font-bold text-brand">
                            {{ __('aldawy.checkout_grand_total') }}:
                            <span class="aldawy-checkout-total-display" id="aldawy-checkout-total">{{ number_format((float) $subtotal + (float) ($cities->first()->shipping_fee ?? 0), 2) }}</span>
                            <span class="text-base font-semibold text-slate-500 dark:text-slate-400">{{ config('aldawy.currency', 'EGP') }}</span>
                        </p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ __('aldawy.checkout_cod') }}</p>
                    </div>
                    <script>
                        (function () {
                            var sel = document.getElementById('city_id');
                            var subEl = document.getElementById('aldawy-checkout-subtotal');
                            var shipEl = document.getElementById('aldawy-checkout-shipping');
                            var totEl = document.getElementById('aldawy-checkout-total');
                            if (!sel || !subEl || !shipEl || !totEl) return;
                            var sub = parseFloat(subEl.getAttribute('data-amount') || '0', 10);
                            function sync() {
                                var opt = sel.options[sel.selectedIndex];
                                var fee = opt ? parseFloat(String(opt.getAttribute('data-fee') || '0'), 10) : 0;
                                if (isNaN(fee)) fee = 0;
                                shipEl.textContent = fee.toFixed(2);
                                document.querySelectorAll('.aldawy-checkout-shipping-display').forEach(function (el) {
                                    el.textContent = fee.toFixed(2);
                                });
                                var total = (sub + fee).toFixed(2);
                                totEl.textContent = total;
                                document.querySelectorAll('.aldawy-checkout-total-display').forEach(function (el) {
                                    el.textContent = total;
                                });
                            }
                            sel.addEventListener('change', sync);
                            sync();
                        })();
                    </script>
                @endif
            </div>
        @endif
    </div>
@endsection
