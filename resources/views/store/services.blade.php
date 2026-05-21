@extends('layouts.store')

@section('content')
    <div class="aldawy-hero-bg border-b border-slate-200 py-12 dark:border-slate-800">
        <div class="mx-auto max-w-4xl px-4 text-center">
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white sm:text-4xl">{{ __('aldawy.services_title') }}</h1>
            <p class="mx-auto mt-4 max-w-2xl text-slate-600 dark:text-slate-300">{{ __('aldawy.services_intro') }}</p>
        </div>
    </div>

    <div class="mx-auto max-w-4xl px-4 py-8">
        <div class="rounded-2xl border border-brand/30 bg-brand/5 p-6 text-sm text-slate-700 dark:bg-brand/10 dark:text-slate-200">
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
            <h2 class="border-b border-slate-200 pb-3 text-xl font-bold text-slate-900 dark:border-slate-800 dark:text-white">{{ __('aldawy.services_prep_section') }}</h2>
            <ul class="mt-6 space-y-3">
                @foreach ($prep as $svc)
                    <li class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-slate-200 bg-canvas px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
                        <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $svc->getTranslation('name', app()->getLocale()) }}</span>
                        <span class="font-mono text-sm text-slate-500 dark:text-slate-400">
                            @if ($svc->surcharge_is_percent)
                                +{{ $svc->surcharge_amount }}%
                            @else
                                +{{ number_format((float) $svc->surcharge_amount, 2) }} EGP
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
        </section>
        <section>
            <h2 class="border-b border-slate-200 pb-3 text-xl font-bold text-slate-900 dark:border-slate-800 dark:text-white">{{ __('aldawy.services_pack_section') }}</h2>
            <ul class="mt-6 space-y-3">
                @foreach ($packaging as $pkg)
                    <li class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-slate-200 bg-canvas px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
                        <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $pkg->getTranslation('name', app()->getLocale()) }}</span>
                        <span class="font-mono text-sm text-slate-500 dark:text-slate-400">
                            @if ($pkg->surcharge_is_percent)
                                +{{ $pkg->surcharge_amount }}%
                            @else
                                +{{ number_format((float) $pkg->surcharge_amount, 2) }} EGP
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
        </section>
        <p class="text-center text-sm text-slate-500 dark:text-slate-400">{{ __('aldawy.services_footer') }}</p>
    </div>
@endsection
