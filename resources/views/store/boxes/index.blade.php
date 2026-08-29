@extends('layouts.store')

@php
    use App\Support\ProduceStockPhoto;

    $boxHeroImages = [
        ['Mango', 'DECO-MANGO'],
        ['Tomato', 'DECO-TOMATO'],
        ['Strawberry', 'DECO-STRAW'],
        ['Broccoli', 'DECO-BROCC'],
        ['Carrot', 'DECO-CAR'],
        ['Avocado', 'DECO-AVO'],
    ];
@endphp

@section('content')
    @include('store.partials.trust-bar')

    <div class="aldawy-hero-bg border-b border-slate-200 py-12 dark:border-slate-800">
        <div class="aldawy-hero-inner mx-auto grid max-w-6xl items-center gap-8 px-4 lg:grid-cols-2">
            <div>
                <p class="aldawy-kicker mb-4">{{ __('aldawy.nav_boxes') }}</p>
                <h1 class="text-3xl font-extrabold text-slate-800 dark:text-white sm:text-4xl">{{ __('aldawy.boxes_title') }}</h1>
                <p class="mt-3 text-slate-600 dark:text-slate-400">{{ __('aldawy.boxes_sub') }}</p>
            </div>
            <div class="grid grid-cols-3 gap-2">
                @foreach ($boxHeroImages as [$name, $sku])
                    <div class="aldawy-card overflow-hidden !shadow-soft">
                        <img src="{{ ProduceStockPhoto::urlFor($name, $sku) }}" alt="" class="aspect-square w-full object-cover" loading="lazy">
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-6xl px-4 py-12">
        @if ($boxes->isEmpty())
            <p class="rounded-2xl border border-dashed border-slate-200 bg-surface p-12 text-center text-slate-500 dark:border-slate-700 dark:bg-slate-900">
                {{ __('aldawy.boxes_empty') }}
            </p>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($boxes as $box)
                    <article class="aldawy-card flex flex-col overflow-hidden !p-0">
                        <div class="relative aspect-[16/10] overflow-hidden bg-surface dark:bg-slate-800">
                            <img
                                src="{{ $box->cover_image_url }}"
                                alt=""
                                class="h-full w-full object-cover transition duration-500 hover:scale-105"
                                loading="lazy"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 to-transparent" aria-hidden="true"></div>
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <h2 class="text-xl font-bold text-slate-800 dark:text-white">
                                {{ $box->getTranslation('name', app()->getLocale()) }}
                            </h2>
                            <p class="mt-2 text-2xl font-extrabold text-brand">
                                {{ number_format((float) $box->price, 2) }}
                                <span class="text-sm font-semibold text-slate-500">{{ config('aldawy.currency', 'EGP') }}</span>
                            </p>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                {{ __('aldawy.box_items_count', ['count' => $box->items_count]) }}
                            </p>
                            <a href="{{ route('store.boxes.show', $box) }}" class="aldawy-btn-primary mt-6 w-full !py-2.5 !text-sm">
                                {{ __('aldawy.box_view') }}
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
@endsection
