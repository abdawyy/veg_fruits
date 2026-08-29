@extends('layouts.store')

@php
    use App\Support\ProduceStockPhoto;
    $locale = app()->getLocale();
    $categoryIcons = ['fruits' => '🍎', 'vegetables' => '🥬'];
    $categoryViewRoutes = [
        'fruits' => ['route' => route('store.fruits'), 'label' => __('aldawy.shop_view_all_fruits')],
        'vegetables' => ['route' => route('store.vegetables'), 'label' => __('aldawy.shop_view_all_vegetables')],
    ];
@endphp

@section('content')
    @include('store.partials.trust-bar')

    {{-- Hero --}}
    <div class="aldawy-hero-bg border-b border-slate-200 dark:border-slate-800" id="shop-top">
        <div class="aldawy-hero-inner mx-auto grid max-w-6xl items-center gap-10 px-4 py-12 lg:grid-cols-[1fr_auto] lg:py-16">
            <div class="text-center lg:text-start">
                <p class="aldawy-kicker mb-4">{{ __('aldawy.badge_fresh') }}</p>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-800 sm:text-4xl lg:text-5xl dark:text-white">{{ __('aldawy.shop_title') }}</h1>
                <p class="mx-auto mt-4 max-w-xl text-slate-600 dark:text-slate-400 lg:mx-0">{{ __('aldawy.shop_subtitle') }}</p>
                <p class="mt-6 inline-flex items-center gap-2 rounded-full border border-brand/20 bg-brand/5 px-4 py-2 text-sm font-semibold text-brand dark:border-brand/30 dark:bg-brand/10 dark:text-brand-light">
                    <span aria-hidden="true">✓</span>
                    {{ __('aldawy.hero_stat', ['count' => $activeProductCount ?? 115]) }}
                </p>

                @if (trim((string) ($q ?? '')) !== '')
                    <div class="mt-8 rounded-2xl border border-accent/30 bg-accent/5 px-5 py-4 dark:bg-accent/10">
                        <p class="text-sm font-semibold text-accent">{{ __('aldawy.search_results_for', ['q' => $q]) }}</p>
                        <a href="{{ route('store.shop') }}" class="mt-2 inline-flex text-sm font-semibold text-brand underline-offset-2 hover:underline">{{ __('aldawy.search_clear') }}</a>
                    </div>
                @else
                    <nav class="mt-8 flex flex-wrap items-center justify-center gap-2 lg:justify-start" aria-label="{{ __('aldawy.shop_browse_categories') }}">
                        @foreach ($categories as $category)
                            <a
                                href="#cat-{{ $category->id }}"
                                class="aldawy-shop-chip"
                            >
                                {{ $categoryIcons[(string) $category->getTranslation('slug', 'en')] ?? '•' }}
                                {{ $category->getTranslation('name', $locale) }}
                            </a>
                        @endforeach
                    </nav>
                @endif
            </div>

            <div class="hidden shrink-0 sm:grid sm:grid-cols-2 sm:gap-2 lg:grid" aria-hidden="true">
                @foreach ([['Mango', 'DECO-MANGO'], ['Tomato', 'DECO-TOMATO'], ['Strawberry', 'DECO-STRAW'], ['Broccoli', 'DECO-BROCC']] as [$name, $sku])
                    <div class="aldawy-card overflow-hidden !p-0 !shadow-soft">
                        <img src="{{ ProduceStockPhoto::urlFor($name, $sku) }}" alt="" class="aspect-square w-28 object-cover sm:w-32 lg:w-36" loading="lazy">
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Catalog --}}
    <div class="mx-auto max-w-6xl px-4 py-12 lg:py-16">
        @if (trim((string) ($q ?? '')) !== '')
            {{-- Search results --}}
            @if (($results ?? collect())->isEmpty())
                @include('store.partials.shop-empty', ['type' => 'search', 'q' => $q])
            @else
                <div class="aldawy-shop-toolbar mb-8">
                    <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">
                        {{ trans_choice('aldawy.shop_results_count', $results->total(), ['count' => $results->total()]) }}
                    </p>
                </div>
                <div class="aldawy-product-grid">
                    @foreach ($results as $product)
                        @include('store.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
                <div class="mt-12 flex justify-center [&_nav]:max-w-full">
                    {{ $results->links() }}
                </div>
            @endif
        @else
            <div class="lg:grid lg:grid-cols-[240px_minmax(0,1fr)] lg:gap-10 xl:grid-cols-[260px_minmax(0,1fr)]">
                @include('store.partials.shop-sidebar', ['categories' => $categories, 'categoryProductPages' => $categoryProductPages])

                <div class="min-w-0 space-y-14 lg:space-y-16">
                    @forelse ($categories as $category)
                        @php
                            $productsPage = $categoryProductPages[$category->id] ?? null;
                            $slugEn = (string) $category->getTranslation('slug', 'en');
                            $viewAll = $categoryViewRoutes[$slugEn] ?? null;
                        @endphp
                        <section id="cat-{{ $category->id }}" aria-labelledby="cat-heading-{{ $category->id }}" class="scroll-mt-28">
                            <header class="aldawy-shop-cat-header">
                                <div class="flex min-w-0 flex-1 items-start gap-4">
                                    <span class="aldawy-shop-cat-icon" aria-hidden="true">{{ $categoryIcons[$slugEn] ?? '🛒' }}</span>
                                    <div class="min-w-0">
                                        <h2 id="cat-heading-{{ $category->id }}" class="aldawy-section-title !text-2xl">
                                            {{ $category->getTranslation('name', $locale) }}
                                        </h2>
                                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                            {{ __('aldawy.category_products_count', ['count' => $productsPage?->total() ?? 0]) }}
                                        </p>
                                    </div>
                                </div>
                                @if ($viewAll)
                                    <a href="{{ $viewAll['route'] }}" class="aldawy-btn-secondary shrink-0 !px-4 !py-2 !text-xs">
                                        {{ $viewAll['label'] }}
                                        <span aria-hidden="true">→</span>
                                    </a>
                                @endif
                            </header>

                            @if ($productsPage && $productsPage->isNotEmpty())
                                <div class="aldawy-product-grid mt-8">
                                    @foreach ($productsPage as $product)
                                        @include('store.partials.product-card', ['product' => $product])
                                    @endforeach
                                </div>
                                <div class="mt-10 flex justify-center [&_nav]:max-w-full">
                                    {{ $productsPage->links() }}
                                </div>
                            @endif
                        </section>
                    @empty
                        @include('store.partials.shop-empty', ['type' => 'catalog'])
                    @endforelse
                </div>
            </div>
        @endif
    </div>

    @if (trim((string) ($q ?? '')) === '' && $categories->isNotEmpty())
        <section class="aldawy-cta-band py-14 text-white">
            <div class="mx-auto max-w-6xl px-4 text-center">
                <h2 class="text-2xl font-extrabold sm:text-3xl">{{ __('aldawy.home_cta_band_title') }}</h2>
                <p class="mx-auto mt-3 max-w-xl text-white/85">{{ __('aldawy.home_cta_band_sub') }}</p>
                <a href="{{ route('store.cart') }}" class="mt-8 inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 text-sm font-bold text-brand shadow-lg transition hover:bg-brand-light hover:text-white">
                    {{ __('aldawy.nav_cart') }}
                    <span aria-hidden="true">→</span>
                </a>
            </div>
        </section>
    @endif
@endsection
