@extends('layouts.store')

@php
    $locale = app()->getLocale();
@endphp

@section('content')
    <div class="aldawy-hero-bg border-b border-slate-200 py-10 dark:border-slate-800">
        <div class="mx-auto max-w-4xl px-4">
            <nav class="text-sm text-slate-500">
                <a href="{{ route('store.boxes') }}" class="font-semibold text-brand hover:underline">{{ __('aldawy.boxes_title') }}</a>
                <span class="mx-1">/</span>
                <span class="text-slate-800 dark:text-slate-200">{{ $box->getTranslation('name', $locale) }}</span>
            </nav>
            <h1 class="mt-4 text-3xl font-bold text-slate-900 dark:text-white">{{ $box->getTranslation('name', $locale) }}</h1>
            <p class="mt-2 text-2xl font-bold text-brand">
                {{ number_format((float) $box->price, 2) }} {{ config('aldawy.currency', 'EGP') }}
            </p>
        </div>
    </div>

    <div class="mx-auto max-w-4xl px-4 py-12">
        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-danger/30 bg-danger/5 px-4 py-3 text-sm text-danger">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-2xl border border-slate-200 bg-white/90 p-6 dark:border-slate-800 dark:bg-slate-900/90">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white">{{ __('aldawy.box_contents') }}</h2>
            <ul class="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                @forelse ($box->items as $item)
                    @if ($item->product)
                        <li class="flex justify-between gap-4 rounded-lg border border-slate-100 px-3 py-2 dark:border-slate-800">
                            <span>{{ $item->product->getTranslation('name', $locale) }}</span>
                            <span class="font-mono text-xs text-slate-500">
                                {{ $item->quantity }} {{ $item->unit === 'piece' ? __('aldawy.per_piece') : __('aldawy.per_kg') }}
                            </span>
                        </li>
                    @endif
                @empty
                    <li class="text-slate-500">{{ __('aldawy.box_has_no_items') }}</li>
                @endforelse
            </ul>
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white/90 p-6 dark:border-slate-800 dark:bg-slate-900/90">
                <h2 class="text-lg font-bold">{{ __('aldawy.box_one_time') }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ __('aldawy.box_one_time_sub') }}</p>
                <form method="post" action="{{ route('store.boxes.cart', $box) }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full rounded-xl bg-brand py-3 text-sm font-bold text-white hover:bg-brand-dark" @disabled($box->items->isEmpty())>
                        {{ __('aldawy.box_add_to_cart') }}
                    </button>
                </form>
            </div>

            <div class="rounded-2xl border border-brand/30 bg-brand/5 p-6 dark:bg-brand/10">
                <h2 class="text-lg font-bold">{{ __('aldawy.box_subscribe') }}</h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ __('aldawy.box_subscribe_sub') }}</p>
                @auth
                    <form method="post" action="{{ route('store.boxes.subscribe', $box) }}" class="mt-6 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium" for="interval">{{ __('aldawy.subscription_interval') }}</label>
                            <select id="interval" name="interval" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950">
                                @foreach ($intervals as $interval)
                                    <option value="{{ $interval->value }}" @selected(old('interval') === $interval->value)>{{ $interval->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium" for="city_id">{{ __('aldawy.checkout_city') }}</label>
                            <select id="city_id" name="city_id" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950">
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" @selected((string) $defaultCityId === (string) $city->id)>
                                        {{ $city->getTranslation('name', $locale) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium" for="shipping_address_line1">{{ __('aldawy.checkout_address1') }}</label>
                            <input id="shipping_address_line1" name="shipping_address_line1" type="text" required value="{{ $defaultAddressLine1 }}" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950">
                        </div>
                        <div>
                            <label class="block text-sm font-medium" for="shipping_address_line2">{{ __('aldawy.checkout_address2') }}</label>
                            <input id="shipping_address_line2" name="shipping_address_line2" type="text" value="{{ $defaultAddressLine2 }}" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950">
                        </div>
                        <div>
                            <label class="block text-sm font-medium" for="customer_phone">{{ __('aldawy.checkout_phone') }}</label>
                            <input id="customer_phone" name="customer_phone" type="text" required value="{{ $defaultPhone }}" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950">
                        </div>
                        @if (count($paymentOptions) > 1)
                            <fieldset>
                                <legend class="text-sm font-medium">{{ __('aldawy.checkout_payment') }}</legend>
                                <div class="mt-2 space-y-2">
                                    @foreach ($paymentOptions as $id => $label)
                                        <label class="flex items-center gap-2 text-sm">
                                            <input type="radio" name="payment_method" value="{{ $id }}" @checked(old('payment_method', 'cod') === $id) required>
                                            {{ $label }}
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                        @else
                            <input type="hidden" name="payment_method" value="cod">
                        @endif
                        <button type="submit" class="w-full rounded-xl bg-brand py-3 text-sm font-bold text-white hover:bg-brand-dark" @disabled($box->items->isEmpty() || $cities->isEmpty())>
                            {{ __('aldawy.box_subscribe_submit') }}
                        </button>
                    </form>
                @else
                    <p class="mt-4 text-sm text-slate-600 dark:text-slate-300">{{ __('aldawy.subscription_login_required') }}</p>
                    <a href="{{ route('login', ['redirect' => route('store.boxes.show', $box)]) }}" class="mt-4 inline-block rounded-xl border border-brand px-4 py-2 text-sm font-bold text-brand">
                        {{ __('aldawy.nav_login') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>
@endsection
