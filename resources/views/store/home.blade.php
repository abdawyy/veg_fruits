@extends('layouts.store')

@php
    use App\Support\ProduceStockPhoto;
@endphp

@section('content')
    @include('store.partials.banners', ['banners' => $banners ?? collect()])

    @include('store.partials.trust-bar', ['productCount' => $featuredCount])

    <section class="aldawy-hero-bg relative px-4 pb-20 pt-14 sm:pt-24">
        <div class="aldawy-hero-inner mx-auto grid max-w-6xl items-center gap-12 lg:grid-cols-2 lg:gap-16">
            <div class="text-center lg:text-start">
                <p class="aldawy-kicker mb-6">
                    <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-brand"></span>
                    {{ $cms('home.hero_kicker', __('aldawy.hero_kicker')) }}
                </p>
                <h1 class="text-4xl font-extrabold leading-[1.1] tracking-tight text-slate-800 sm:text-5xl lg:text-6xl dark:text-white">
                    {!! __('aldawy.hero_headline') !!}
                </h1>
                <p class="mt-6 text-lg leading-relaxed text-slate-600 sm:text-xl dark:text-slate-400">
                    {{ __('aldawy.hero_sub') }}
                </p>
                <div class="mt-10 flex flex-wrap items-center justify-center gap-4 lg:justify-start">
                    <a href="{{ route('store.shop') }}" class="aldawy-btn-primary">
                        {{ __('aldawy.cta_shop') }}
                    </a>
                    <a href="#catalog" class="aldawy-btn-secondary">
                        {{ __('aldawy.cta_browse') }}
                    </a>
                </div>
                <p class="mt-10 inline-flex items-center gap-2 rounded-full border border-brand/20 bg-brand/5 px-4 py-2 text-sm font-semibold text-brand dark:border-brand/30 dark:bg-brand/10 dark:text-brand-light">
                    <span aria-hidden="true">✓</span>
                    {{ __('aldawy.hero_stat', ['count' => $featuredCount]) }}
                </p>
            </div>

            <div class="relative mx-auto w-full max-w-lg lg:max-w-none" aria-hidden="true">
                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    <div class="space-y-3 sm:space-y-4">
                        <div class="aldawy-card overflow-hidden !shadow-card">
                            <img src="{{ ProduceStockPhoto::urlFor('Mango', 'DECO-MANGO') }}" alt="" class="aspect-[4/5] w-full object-cover" loading="eager">
                        </div>
                        <div class="aldawy-card overflow-hidden !shadow-card">
                            <img src="{{ ProduceStockPhoto::urlFor('Tomato', 'DECO-TOMATO') }}" alt="" class="aspect-square w-full object-cover" loading="lazy">
                        </div>
                    </div>
                    <div class="space-y-3 pt-8 sm:space-y-4 sm:pt-12">
                        <div class="aldawy-card overflow-hidden !shadow-card">
                            <img src="{{ ProduceStockPhoto::urlFor('Strawberry', 'DECO-STRAW') }}" alt="" class="aspect-square w-full object-cover" loading="lazy">
                        </div>
                        <div class="aldawy-card overflow-hidden !shadow-card">
                            <img src="{{ ProduceStockPhoto::urlFor('Broccoli', 'DECO-BROCC') }}" alt="" class="aspect-[4/5] w-full object-cover" loading="lazy">
                        </div>
                    </div>
                </div>
                <div class="pointer-events-none absolute -end-4 -top-4 h-24 w-24 rounded-full bg-accent/20 blur-2xl"></div>
                <div class="pointer-events-none absolute -bottom-4 -start-4 h-28 w-28 rounded-full bg-brand/20 blur-2xl"></div>
            </div>
        </div>
    </section>

    @include('store.partials.category-tiles')

    <section id="catalog" class="border-t border-slate-200 bg-surface/60 py-16 dark:border-slate-800 dark:bg-slate-900/40">
        <div class="mx-auto max-w-6xl px-4">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="aldawy-section-title">{{ __('aldawy.section_pick') }}</h2>
                    <p class="mt-2 text-slate-500 dark:text-slate-400">{{ __('aldawy.section_pick_sub') }}</p>
                </div>
                <a href="{{ route('store.shop') }}" class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-brand hover:text-brand-dark sm:mt-0">
                    {{ __('aldawy.view_full_catalog') }}
                    <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="mt-10 grid gap-8 lg:grid-cols-2">
                @forelse ($categories as $category)
                    <div class="aldawy-card overflow-hidden">
                        <div class="flex items-center justify-between gap-4 border-b border-slate-100 bg-gradient-to-r from-brand/5 to-transparent px-6 py-4 dark:border-slate-800 dark:from-brand/10">
                            <h3 class="text-xl font-bold text-slate-800 dark:text-white">{{ $category->getTranslation('name', app()->getLocale()) }}</h3>
                            <span class="rounded-full bg-accent/15 px-3 py-1 text-xs font-bold text-accent">{{ $category->products->count() }}+</span>
                        </div>
                        <div class="grid gap-4 p-4 sm:grid-cols-2 sm:p-6">
                            @foreach ($category->products as $product)
                                @include('store.partials.product-card', ['product' => $product])
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-canvas p-12 text-center text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400">
                        {{ __('aldawy.empty_catalog') }}
                    </p>
                @endforelse
            </div>
        </div>
    </section>

    @include('store.partials.home-marketing')
@endsection
