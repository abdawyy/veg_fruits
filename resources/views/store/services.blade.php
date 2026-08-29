@extends('layouts.store')

@php
    use App\Support\ProduceStockPhoto;
@endphp

@section('content')
    @include('store.partials.trust-bar')

    <div class="aldawy-hero-bg border-b border-slate-200 py-12 dark:border-slate-800">
        <div class="aldawy-hero-inner mx-auto grid max-w-6xl items-center gap-8 px-4 lg:grid-cols-2">
            <div class="text-center lg:text-start">
                <p class="aldawy-kicker mb-4">{{ __('aldawy.trust_prep') }}</p>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white sm:text-4xl">{{ __('aldawy.services_title') }}</h1>
                <p class="mx-auto mt-4 max-w-2xl text-slate-600 dark:text-slate-300 lg:mx-0">{{ __('aldawy.services_intro') }}</p>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="aldawy-card overflow-hidden !shadow-card">
                    <img src="{{ ProduceStockPhoto::urlFor('Cucumber', 'DECO-CUC') }}" alt="" class="aspect-[4/3] w-full object-cover" loading="lazy">
                </div>
                <div class="aldawy-card overflow-hidden !shadow-card">
                    <img src="{{ ProduceStockPhoto::urlFor('Garlic', 'DECO-GARL') }}" alt="" class="aspect-[4/3] w-full object-cover" loading="lazy">
                </div>
                <div class="aldawy-card col-span-2 overflow-hidden !shadow-card">
                    <img src="{{ ProduceStockPhoto::urlFor('Cherry tomatoes', 'DECO-CHTOM') }}" alt="" class="aspect-[21/9] w-full object-cover" loading="lazy">
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-4xl px-4 py-8">
        <div class="aldawy-card border-brand/20 bg-brand/5 p-6 text-sm text-slate-700 dark:bg-brand/10 dark:text-slate-200">
            <h2 class="font-bold text-slate-900 dark:text-white">{{ __('aldawy.services_rules_title') }}</h2>
            <ul class="mt-3 list-inside list-disc space-y-2">
                <li>{{ __('aldawy.services_rules_catalog') }}</li>
                <li>{{ __('aldawy.services_rules_per_product') }}</li>
                <li>{{ __('aldawy.services_rules_checkout') }}</li>
            </ul>
        </div>
    </div>

    <div class="mx-auto max-w-4xl space-y-16 px-4 py-14">
        <section>
            <h2 class="aldawy-section-title border-b border-slate-200 pb-3 dark:border-slate-800">{{ __('aldawy.services_prep_section') }}</h2>
            <ul class="mt-6 space-y-3">
                @foreach ($prep as $svc)
                    <li class="aldawy-card flex flex-wrap items-center justify-between gap-3 px-4 py-3 !shadow-soft">
                        <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $svc->getTranslation('name', app()->getLocale()) }}</span>
                        <span class="rounded-full bg-brand/10 px-3 py-1 text-sm font-bold text-brand">
                            @if ($svc->surcharge_is_percent)
                                +{{ $svc->surcharge_amount }}%
                            @else
                                +{{ number_format((float) $svc->surcharge_amount, 2) }} {{ config('aldawy.currency', 'EGP') }}
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
        </section>
        <section>
            <h2 class="aldawy-section-title border-b border-slate-200 pb-3 dark:border-slate-800">{{ __('aldawy.services_pack_section') }}</h2>
            <ul class="mt-6 space-y-3">
                @foreach ($packaging as $pkg)
                    <li class="aldawy-card flex flex-wrap items-center justify-between gap-3 px-4 py-3 !shadow-soft">
                        <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $pkg->getTranslation('name', app()->getLocale()) }}</span>
                        <span class="rounded-full bg-accent/15 px-3 py-1 text-sm font-bold text-accent">
                            @if ($pkg->surcharge_is_percent)
                                +{{ $pkg->surcharge_amount }}%
                            @else
                                +{{ number_format((float) $pkg->surcharge_amount, 2) }} {{ config('aldawy.currency', 'EGP') }}
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
        </section>
        <p class="text-center text-sm text-slate-500 dark:text-slate-400">{{ __('aldawy.services_footer') }}</p>
        <div class="text-center">
            <a href="{{ route('store.shop') }}" class="aldawy-btn-primary mt-6 inline-flex">{{ __('aldawy.cta_shop') }}</a>
        </div>
    </div>
@endsection
