@extends('layouts.store')

@php
    $slugEn = (string) $category->getTranslation('slug', 'en');
    $heroModifier = in_array($slugEn, ['fruits', 'vegetables'], true) ? 'aldawy-category-hero--'.$slugEn : '';
    $heroClass = trim('aldawy-category-hero '.$heroModifier);
    $categoryIcons = ['fruits' => '🍎', 'vegetables' => '🥬'];
@endphp

@section('content')
    @include('store.partials.trust-bar')

    <div class="{{ $heroClass }} py-16 sm:py-20">
        <div class="aldawy-category-hero-inner mx-auto max-w-6xl px-4 text-center">
            <p class="aldawy-kicker mb-4 !border-white/30 !bg-white/10 !text-white">{{ __('aldawy.badge_fresh') }}</p>
            <div class="mx-auto flex max-w-2xl items-center justify-center gap-4">
                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/15 text-3xl backdrop-blur-sm" aria-hidden="true">{{ $categoryIcons[$slugEn] ?? '🛒' }}</span>
                <h1 class="text-3xl font-extrabold text-white drop-shadow-sm sm:text-4xl md:text-5xl">
                    {{ $category->getTranslation('name', app()->getLocale()) }}
                </h1>
            </div>
            <p class="mx-auto mt-4 max-w-2xl text-base text-white/90 drop-shadow sm:text-lg">
                {{ $slugEn === 'fruits' ? __('aldawy.fruits_subtitle') : ($slugEn === 'vegetables' ? __('aldawy.vegetables_subtitle') : __('aldawy.category_shop_sub')) }}
            </p>
            @if (trim((string) ($q ?? '')) !== '')
                <div class="mx-auto mt-6 max-w-md rounded-2xl border border-white/20 bg-white/10 px-5 py-4 backdrop-blur-sm">
                    <p class="text-sm font-semibold text-amber-100">{{ __('aldawy.search_results_for', ['q' => $q]) }}</p>
                    <a href="{{ request()->url() }}" class="mt-2 inline-flex text-sm font-semibold text-white underline-offset-2 hover:underline">
                        {{ __('aldawy.search_clear_category') }}
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="mx-auto max-w-6xl px-4 py-12 lg:py-16">
        @if ($products->isEmpty())
            @include('store.partials.shop-empty', ['type' => trim((string) ($q ?? '')) !== '' ? 'search' : 'catalog', 'q' => $q ?? ''])
        @else
            <div class="aldawy-shop-toolbar mb-8">
                <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">
                    {{ __('aldawy.category_products_count', ['count' => $products->total()]) }}
                </p>
                <a href="{{ route('store.shop') }}" class="text-sm font-semibold text-brand hover:underline">{{ __('aldawy.view_full_catalog') }}</a>
            </div>
            <div class="aldawy-product-grid">
                @foreach ($products as $product)
                    @include('store.partials.product-card', ['product' => $product])
                @endforeach
            </div>
            <div class="mt-12 flex justify-center [&_nav]:max-w-full">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection
