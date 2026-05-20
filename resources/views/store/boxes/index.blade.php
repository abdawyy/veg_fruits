@extends('layouts.store')

@section('content')
    <div class="aldawy-hero-bg border-b border-slate-200 py-10 dark:border-slate-800">
        <div class="mx-auto max-w-6xl px-4">
            <h1 class="text-3xl font-bold text-slate-800 dark:text-white">{{ __('aldawy.boxes_title') }}</h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400">{{ __('aldawy.boxes_sub') }}</p>
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
                    <article class="flex flex-col rounded-2xl border border-slate-200 bg-white/90 p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/90">
                        <h2 class="text-xl font-bold text-slate-800 dark:text-white">
                            {{ $box->getTranslation('name', app()->getLocale()) }}
                        </h2>
                        <p class="mt-2 text-2xl font-bold text-brand">
                            {{ number_format((float) $box->price, 2) }}
                            <span class="text-sm font-semibold text-slate-500">{{ config('aldawy.currency', 'EGP') }}</span>
                        </p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                            {{ __('aldawy.box_items_count', ['count' => $box->items_count]) }}
                        </p>
                        <a href="{{ route('store.boxes.show', $box) }}" class="mt-6 inline-block rounded-xl bg-brand px-4 py-2 text-sm font-bold text-white hover:bg-brand-dark">
                            {{ __('aldawy.box_view') }}
                        </a>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
@endsection
