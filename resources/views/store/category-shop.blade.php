@extends('layouts.store')

@php
    $slugEn = (string) $category->getTranslation('slug', 'en');
    $heroModifier = in_array($slugEn, ['fruits', 'vegetables'], true) ? 'aldawy-category-hero--'.$slugEn : '';
    $heroClass = trim('aldawy-category-hero '.$heroModifier);
@endphp

@section('content')
    <div class="{{ $heroClass }} py-16 sm:py-20">
        <div class="aldawy-category-hero-inner mx-auto max-w-6xl px-4 text-center">
            <h1 class="text-3xl font-bold text-white drop-shadow-sm sm:text-4xl md:text-5xl">
                {{ $category->getTranslation('name', app()->getLocale()) }}
            </h1>
            <p class="mx-auto mt-4 max-w-2xl text-base text-white/90 drop-shadow sm:text-lg">
                {{ __('aldawy.category_shop_sub') }}
            </p>
            @if (trim((string) ($q ?? '')) !== '')
                <p class="mt-5 text-sm font-medium text-amber-200">
                    {{ __('aldawy.search_results_for', ['q' => $q]) }}
                </p>
                <p class="mt-2">
                    <a href="{{ request()->url() }}" class="text-sm font-semibold text-white underline-offset-2 hover:text-brand-light hover:underline">
                        {{ __('aldawy.search_clear_category') }}
                    </a>
                </p>
            @endif
        </div>
    </div>

    <div class="mx-auto max-w-6xl bg-canvas px-4 py-16 dark:bg-slate-950">
        @if ($products->isEmpty())
            <p class="rounded-2xl border border-dashed border-slate-200 bg-surface p-16 text-center text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400">
                {{ trim((string) ($q ?? '')) !== '' ? __('aldawy.search_no_results', ['q' => $q]) : __('aldawy.empty_catalog') }}
            </p>
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($products as $product)
                    @include('store.partials.product-card', ['product' => $product])
                @endforeach
            </div>
            <div class="mt-10 flex justify-center [&_nav]:max-w-full">
                {{ $products->links() }}
            </div>
        @endif
        <p class="mt-10 text-center text-sm text-slate-500 dark:text-slate-400">
            <a href="{{ route('store.shop') }}" class="font-semibold text-brand hover:underline">{{ __('aldawy.view_full_catalog') }}</a>
        </p>
    </div>
@endsection
