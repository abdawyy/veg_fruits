@extends('layouts.store')

@php
    $locale = app()->getLocale();
@endphp

@section('content')
    @include('store.partials.trust-bar')

    <div class="aldawy-hero-bg border-b border-slate-200 py-10 dark:border-slate-800">
        <div class="mx-auto max-w-6xl px-4">
            <nav class="aldawy-breadcrumb">
                <a href="{{ route('store.boxes') }}">{{ __('aldawy.boxes_title') }}</a>
                <span aria-hidden="true">/</span>
                <span class="text-slate-800 dark:text-slate-200">{{ $box->getTranslation('name', $locale) }}</span>
            </nav>
            <div class="mt-4 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="aldawy-kicker mb-3">{{ __('aldawy.nav_boxes') }}</p>
                    <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white sm:text-4xl">{{ $box->getTranslation('name', $locale) }}</h1>
                </div>
                <p class="text-3xl font-extrabold text-brand">
                    {{ number_format((float) $box->price, 2) }}
                    <span class="text-base font-semibold text-slate-500">{{ config('aldawy.currency', 'EGP') }}</span>
                </p>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-6xl px-4 py-12">
        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-danger/30 bg-danger/5 px-5 py-4 text-sm text-danger dark:bg-danger/10">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-8 lg:grid-cols-5">
            <div class="aldawy-card overflow-hidden !p-0 lg:col-span-2">
                <img src="{{ $box->cover_image_url }}" alt="" class="aspect-[4/3] w-full object-cover">
            </div>
            <div class="lg:col-span-3">
                <div class="aldawy-card p-6">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white">{{ __('aldawy.box_contents') }}</h2>
                    <ul class="mt-4 space-y-2">
                        @forelse ($box->items as $item)
                            @if ($item->product)
                                <li class="flex justify-between gap-4 rounded-xl bg-surface px-4 py-3 text-sm dark:bg-slate-800">
                                    <span class="font-medium text-slate-800 dark:text-slate-100">{{ $item->product->getTranslation('name', $locale) }}</span>
                                    <span class="shrink-0 font-bold text-brand">
                                        {{ $item->quantity }} {{ $item->unit === 'piece' ? __('aldawy.per_piece') : __('aldawy.per_kg') }}
                                    </span>
                                </li>
                            @endif
                        @empty
                            <li class="text-slate-500">{{ __('aldawy.box_has_no_items') }}</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-10 grid gap-6 lg:grid-cols-2">
            <div class="aldawy-card p-6">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white">{{ __('aldawy.box_one_time') }}</h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ __('aldawy.box_one_time_sub') }}</p>
                <form method="post" action="{{ route('store.boxes.cart', $box) }}" class="mt-6">
                    @csrf
                    <button type="submit" class="aldawy-btn-primary w-full !py-3" @disabled($box->items->isEmpty())>
                        {{ __('aldawy.box_add_to_cart') }}
                    </button>
                </form>
            </div>

            <div class="aldawy-card border-brand/25 bg-gradient-to-br from-brand/5 to-transparent p-6 dark:from-brand/10">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white">{{ __('aldawy.box_subscribe') }}</h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ __('aldawy.box_subscribe_sub') }}</p>
                @auth
                    <form method="post" action="{{ route('store.boxes.subscribe', $box) }}" class="mt-6 space-y-4">
                        @csrf
                        <div>
                            <label class="mb-1 block text-sm font-semibold" for="interval">{{ __('aldawy.subscription_interval') }}</label>
                            <select id="interval" name="interval" required class="aldawy-input">
                                @foreach ($intervals as $interval)
                                    <option value="{{ $interval->value }}" @selected(old('interval') === $interval->value)>{{ $interval->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold" for="city_id">{{ __('aldawy.checkout_city') }}</label>
                            <select id="city_id" name="city_id" required class="aldawy-input">
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" @selected((string) $defaultCityId === (string) $city->id)>
                                        {{ $city->getTranslation('name', $locale) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold" for="shipping_address_line1">{{ __('aldawy.checkout_address1') }}</label>
                            <input id="shipping_address_line1" name="shipping_address_line1" type="text" required value="{{ $defaultAddressLine1 }}" class="aldawy-input">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold" for="shipping_address_line2">{{ __('aldawy.checkout_address2') }}</label>
                            <input id="shipping_address_line2" name="shipping_address_line2" type="text" value="{{ $defaultAddressLine2 }}" class="aldawy-input">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold" for="customer_phone">{{ __('aldawy.checkout_phone') }}</label>
                            <input id="customer_phone" name="customer_phone" type="text" required value="{{ $defaultPhone }}" class="aldawy-input">
                        </div>
                        @if (count($paymentOptions) > 1)
                            <fieldset>
                                <legend class="text-sm font-semibold">{{ __('aldawy.checkout_payment') }}</legend>
                                <div class="mt-2 space-y-2">
                                    @foreach ($paymentOptions as $id => $label)
                                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm has-[:checked]:border-brand has-[:checked]:bg-brand/5 dark:border-slate-700">
                                            <input type="radio" name="payment_method" value="{{ $id }}" @checked(old('payment_method', 'cod') === $id) required class="text-brand">
                                            {{ $label }}
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                        @else
                            <input type="hidden" name="payment_method" value="cod">
                        @endif
                        <button type="submit" class="aldawy-btn-primary w-full !py-3" @disabled($box->items->isEmpty() || $cities->isEmpty())>
                            {{ __('aldawy.box_subscribe_submit') }}
                        </button>
                    </form>
                @else
                    <p class="mt-4 text-sm text-slate-600 dark:text-slate-300">{{ __('aldawy.subscription_login_required') }}</p>
                    <a href="{{ route('login', ['redirect' => route('store.boxes.show', $box)]) }}" class="aldawy-btn-secondary mt-4 inline-flex">
                        {{ __('aldawy.nav_login') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>
@endsection
