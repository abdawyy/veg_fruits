@extends('layouts.store')

@section('content')
    @include('store.partials.banners', ['banners' => $banners ?? collect()])

    <section class="aldawy-hero-bg relative px-4 pb-24 pt-12 sm:pt-20">
        <div class="relative z-10 mx-auto max-w-6xl text-center">
            <p class="mb-4 inline-flex items-center gap-2 rounded-full border border-accent/25 bg-accent/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-accent">
                <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-brand"></span>
                {{ $cms('home.hero_kicker', __('aldawy.hero_kicker')) }}
            </p>
            <h1 class="mx-auto max-w-4xl text-4xl font-bold leading-tight tracking-tight text-slate-800 sm:text-5xl lg:text-6xl dark:text-white">
                {!! __('aldawy.hero_headline') !!}
            </h1>
            <p class="mx-auto mt-6 max-w-2xl text-lg text-slate-500 sm:text-xl dark:text-slate-400">
                {{ __('aldawy.hero_sub') }}
            </p>
            <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('store.shop') }}" class="inline-flex items-center justify-center rounded-2xl bg-brand px-8 py-4 text-base font-bold text-white shadow-md shadow-brand/25 transition hover:bg-brand-dark hover:shadow-lg">
                    {{ __('aldawy.cta_shop') }}
                </a>
                <a href="{{ route('store.shop') }}#catalog" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-8 py-4 text-base font-semibold text-slate-800 shadow-sm transition hover:border-slate-300 hover:bg-surface hover:shadow-md dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800">
                    {{ __('aldawy.cta_browse') }}
                </a>
            </div>
            <p class="mt-10 text-sm font-medium text-brand">
                {{ __('aldawy.hero_stat', ['count' => $featuredCount]) }}
            </p>
        </div>
    </section>

    <section id="catalog" class="border-t border-slate-200 bg-surface/80 py-16 dark:border-slate-800 dark:bg-slate-900/40">
        <div class="mx-auto max-w-6xl px-4">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 sm:text-3xl dark:text-white">{{ __('aldawy.section_pick') }}</h2>
                    <p class="mt-1 text-slate-500 dark:text-slate-400">{{ __('aldawy.section_pick_sub') }}</p>
                </div>
                <a href="{{ route('store.shop') }}" class="mt-4 text-sm font-semibold text-brand hover:text-brand-dark sm:mt-0">
                    {{ __('aldawy.view_full_catalog') }} →
                </a>
            </div>

            <div class="mt-10 grid gap-10 lg:grid-cols-2">
                @forelse ($categories as $category)
                    <div class="rounded-3xl border border-slate-200 bg-canvas p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-center justify-between gap-4">
                            <h3 class="text-xl font-bold text-slate-800 dark:text-white">{{ $category->getTranslation('name', app()->getLocale()) }}</h3>
                            <span class="rounded-full border border-accent/20 bg-accent/10 px-3 py-1 text-xs font-bold text-accent">{{ $category->products->count() }}+</span>
                        </div>
                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            @foreach ($category->products as $product)
                                @include('store.partials.product-card', ['product' => $product])
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-canvas p-12 text-center text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400">
                        {{ __('aldawy.empty_catalog') }}
                        <br>
                        <code class="mt-4 inline-block rounded border border-slate-200 bg-surface px-3 py-1 text-sm text-brand-dark dark:border-slate-700 dark:bg-slate-800 dark:text-brand-light">php artisan db:seed</code>
                    </p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
