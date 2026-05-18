@extends('layouts.store')

@php
    $locale = app()->getLocale();
@endphp

@section('content')
    <div class="aldawy-hero-bg border-b border-slate-200 py-10 dark:border-slate-800">
        <div class="mx-auto max-w-4xl px-4">
            <nav class="text-sm text-slate-500 dark:text-slate-400">
                <a href="{{ route('store.shop') }}" class="font-semibold text-brand hover:underline">{{ __('aldawy.nav_shop') }}</a>
                <span class="mx-1">/</span>
                <span class="text-slate-800 dark:text-slate-200">{{ $product->getTranslation('name', $locale) }}</span>
            </nav>
            <h1 class="mt-4 text-3xl font-bold text-slate-900 dark:text-white sm:text-4xl">{{ $product->getTranslation('name', $locale) }}</h1>
            <p class="mt-2 font-mono text-xs text-slate-500 dark:text-slate-400">{{ $product->sku }}</p>
        </div>
    </div>

    <div class="mx-auto max-w-4xl px-4 py-12">
        <div class="grid gap-10 lg:grid-cols-2">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-canvas shadow-sm dark:border-slate-800 dark:bg-slate-900">
                @if ($product->display_image_url)
                    <img src="{{ $product->display_image_url }}" alt="" class="aspect-square w-full object-cover">
                @else
                    <div class="flex aspect-square items-center justify-center bg-surface text-7xl dark:bg-slate-800">
                        {{ ($product->category?->getTranslation('slug', 'en')) === 'fruits' ? '🍎' : '🥬' }}
                    </div>
                @endif
            </div>
            <div>
                <p class="text-sm leading-relaxed text-slate-600 dark:text-slate-300">
                    {{ $product->getTranslation('description', $locale) }}
                </p>
                <div class="mt-6 rounded-xl border border-slate-200 bg-surface p-4 dark:border-slate-800 dark:bg-slate-950">
                    <p class="text-2xl font-bold text-brand dark:text-brand-light">
                        {{ number_format((float) $product->price_per_kg, 2) }}
                        <span class="text-base font-semibold text-slate-500 dark:text-slate-400">{{ config('aldawy.currency', 'EGP') }} / {{ __('aldawy.per_kg') }}</span>
                    </p>
                    @if ($product->sell_by_piece && $product->price_per_piece)
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                            {{ __('aldawy.piece_price', ['price' => number_format((float) $product->price_per_piece, 2)]) }}
                        </p>
                    @endif
                </div>

                @include('store.partials.product-add-form', ['product' => $product])

                <div class="mt-10 space-y-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('aldawy.product_prep_heading') }}</h2>
                        <ul class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                            @php $prepRows = $product->preparationServices->filter(fn ($s) => $s->pivot->is_enabled && $s->is_active)->sortBy('sort_order'); @endphp
                            @forelse ($prepRows as $svc)
                                <li class="flex justify-between gap-4 rounded-lg border border-slate-200 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-900">
                                    <span>{{ $svc->getTranslation('name', $locale) }}</span>
                                    <span class="shrink-0 font-mono text-xs text-slate-500">
                                        @if ($svc->surcharge_is_percent)
                                            {{ $svc->surcharge_amount }}%
                                        @else
                                            {{ number_format((float) $svc->surcharge_amount, 2) }} EGP
                                        @endif
                                    </span>
                                </li>
                            @empty
                                <li class="text-slate-500">{{ __('aldawy.product_prep_empty') }}</li>
                            @endforelse
                        </ul>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('aldawy.product_pack_heading') }}</h2>
                        <ul class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                            @php $packRows = $product->packagingTypes->filter(fn ($p) => $p->pivot->is_enabled && $p->is_active)->sortBy('sort_order'); @endphp
                            @forelse ($packRows as $pkg)
                                <li class="flex justify-between gap-4 rounded-lg border border-slate-200 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-900">
                                    <span>{{ $pkg->getTranslation('name', $locale) }}</span>
                                    <span class="shrink-0 font-mono text-xs text-slate-500">
                                        @if ($pkg->surcharge_is_percent)
                                            {{ $pkg->surcharge_amount }}%
                                        @else
                                            {{ number_format((float) $pkg->surcharge_amount, 2) }} EGP
                                        @endif
                                    </span>
                                </li>
                            @empty
                                <li class="text-slate-500">{{ __('aldawy.product_pack_empty') }}</li>
                            @endforelse
                        </ul>
                    </div>
                    <a href="{{ route('store.services') }}" class="inline-block text-sm font-semibold text-brand hover:underline">{{ __('aldawy.product_services_link') }} →</a>
                </div>
            </div>
        </div>
    </div>
@endsection
