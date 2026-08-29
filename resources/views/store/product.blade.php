@extends('layouts.store')

@php
    $locale = app()->getLocale();
@endphp

@section('content')
    @include('store.partials.trust-bar')

    <div class="aldawy-hero-bg border-b border-slate-200 py-8 dark:border-slate-800 sm:py-10">
        <div class="aldawy-hero-inner mx-auto max-w-6xl px-4">
            <nav class="aldawy-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('store.shop') }}">{{ __('aldawy.nav_shop') }}</a>
                <span aria-hidden="true">/</span>
                @if ($product->category)
                    @php
                        $catSlug = (string) $product->category->getTranslation('slug', 'en');
                        $catUrl = match ($catSlug) {
                            'fruits' => route('store.fruits'),
                            'vegetables' => route('store.vegetables'),
                            default => route('store.shop').'#cat-'.$product->category_id,
                        };
                    @endphp
                    <a href="{{ $catUrl }}">
                        {{ $product->category->getTranslation('name', $locale) }}
                    </a>
                    <span aria-hidden="true">/</span>
                @endif
                <span class="text-slate-800 dark:text-slate-200">{{ $product->getTranslation('name', $locale) }}</span>
            </nav>
        </div>
    </div>

    <div class="mx-auto max-w-6xl px-4 py-10 lg:py-14">
        <div class="grid gap-10 lg:grid-cols-2 lg:gap-14">
            <div class="aldawy-card overflow-hidden !p-0">
                @if ($product->display_image_url)
                    <img src="{{ $product->display_image_url }}" alt="{{ $product->getTranslation('name', $locale) }}" class="aspect-square w-full object-cover">
                @else
                    <div class="flex aspect-square items-center justify-center bg-surface text-7xl dark:bg-slate-800">
                        {{ ($product->category?->getTranslation('slug', 'en')) === 'fruits' ? '🍎' : '🥬' }}
                    </div>
                @endif
            </div>

            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <span class="aldawy-product-badge !static inline-flex">{{ __('aldawy.product_fresh_badge') }}</span>
                    @if ($product->category)
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                            {{ $product->category->getTranslation('name', $locale) }}
                        </span>
                    @endif
                </div>
                <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">
                    {{ $product->getTranslation('name', $locale) }}
                </h1>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ __('aldawy.product_bilingual_hint') }}</p>

                <p class="mt-6 text-base leading-relaxed text-slate-600 dark:text-slate-300">
                    {{ $product->getTranslation('description', $locale) }}
                </p>

                <div class="aldawy-card mt-6 border-brand/20 bg-gradient-to-br from-brand/5 to-transparent p-5 dark:from-brand/10">
                    <div class="flex flex-wrap items-end justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-slate-500">{{ __('aldawy.per_kg') }}</p>
                            <p class="text-3xl font-extrabold text-brand dark:text-brand-light">
                                {{ number_format((float) $product->price_per_kg, 2) }}
                                <span class="text-base font-semibold text-slate-500 dark:text-slate-400">{{ config('aldawy.currency', 'EGP') }}</span>
                            </p>
                        </div>
                        @if ($product->sell_by_piece && $product->price_per_piece)
                            <span class="rounded-xl bg-accent/10 px-3 py-2 text-sm font-bold text-accent">
                                {{ __('aldawy.piece_price', ['price' => number_format((float) $product->price_per_piece, 2)]) }}
                            </span>
                        @endif
                    </div>
                </div>

                @include('store.partials.product-add-form', ['product' => $product])

                <div class="mt-10 grid gap-6 sm:grid-cols-2">
                    <div class="aldawy-card p-5">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">{{ __('aldawy.product_prep_heading') }}</h2>
                        <ul class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                            @php $prepRows = $product->preparationServices->filter(fn ($s) => $s->pivot->is_enabled && $s->is_active)->sortBy('sort_order'); @endphp
                            @forelse ($prepRows as $svc)
                                <li class="flex justify-between gap-3 rounded-lg bg-surface px-3 py-2 dark:bg-slate-800">
                                    <span>{{ $svc->getTranslation('name', $locale) }}</span>
                                    <span class="shrink-0 font-semibold text-brand">
                                        @if ($svc->surcharge_is_percent)
                                            {{ $svc->surcharge_amount }}%
                                        @else
                                            +{{ number_format((float) $svc->surcharge_amount, 2) }}
                                        @endif
                                    </span>
                                </li>
                            @empty
                                <li class="text-slate-500">{{ __('aldawy.product_prep_empty') }}</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="aldawy-card p-5">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">{{ __('aldawy.product_pack_heading') }}</h2>
                        <ul class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                            @php $packRows = $product->packagingTypes->filter(fn ($p) => $p->pivot->is_enabled && $p->is_active)->sortBy('sort_order'); @endphp
                            @forelse ($packRows as $pkg)
                                <li class="flex justify-between gap-3 rounded-lg bg-surface px-3 py-2 dark:bg-slate-800">
                                    <span>{{ $pkg->getTranslation('name', $locale) }}</span>
                                    <span class="shrink-0 font-semibold text-accent">
                                        @if ($pkg->surcharge_is_percent)
                                            {{ $pkg->surcharge_amount }}%
                                        @else
                                            +{{ number_format((float) $pkg->surcharge_amount, 2) }}
                                        @endif
                                    </span>
                                </li>
                            @empty
                                <li class="text-slate-500">{{ __('aldawy.product_pack_empty') }}</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <a href="{{ route('store.services') }}" class="mt-6 inline-flex items-center gap-1 text-sm font-semibold text-brand hover:underline">
                    {{ __('aldawy.product_services_link') }} <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>

        @if ($previousProduct || $nextProduct)
            <nav class="mt-14 flex flex-col gap-3 border-t border-slate-200 pt-10 sm:flex-row sm:items-stretch sm:justify-between dark:border-slate-800" aria-label="{{ __('aldawy.nav_browse') }}">
                @if ($previousProduct)
                    <a href="{{ route('store.product', $previousProduct) }}" class="aldawy-card group flex min-w-0 flex-1 items-center gap-3 !p-4 transition hover:!border-brand/30">
                        <span class="shrink-0 text-lg text-brand" aria-hidden="true">←</span>
                        <span class="min-w-0">
                            <span class="block text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('aldawy.nav_previous_product') }}</span>
                            <span class="block truncate font-semibold text-slate-800 group-hover:text-brand dark:text-white">{{ $previousProduct->getTranslation('name', $locale) }}</span>
                        </span>
                    </a>
                @else
                    <span class="hidden flex-1 sm:block"></span>
                @endif
                @if ($nextProduct)
                    <a href="{{ route('store.product', $nextProduct) }}" class="aldawy-card group flex min-w-0 flex-1 items-center justify-end gap-3 !p-4 text-end transition hover:!border-brand/30">
                        <span class="min-w-0">
                            <span class="block text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('aldawy.nav_next_product') }}</span>
                            <span class="block truncate font-semibold text-slate-800 group-hover:text-brand dark:text-white">{{ $nextProduct->getTranslation('name', $locale) }}</span>
                        </span>
                        <span class="shrink-0 text-lg text-brand" aria-hidden="true">→</span>
                    </a>
                @endif
            </nav>
        @endif
    </div>
@endsection
