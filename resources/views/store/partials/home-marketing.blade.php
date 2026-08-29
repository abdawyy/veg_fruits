<section class="py-16">
    <div class="mx-auto max-w-6xl px-4">
        <h2 class="aldawy-section-title text-center">{{ __('aldawy.home_value_title') }}</h2>
        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="aldawy-card p-6">
                <div class="aldawy-value-icon" aria-hidden="true">🌿</div>
                <h3 class="mt-4 font-bold text-slate-800 dark:text-white">{{ __('aldawy.value_fresh_title') }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">{{ __('aldawy.value_fresh_desc') }}</p>
            </div>
            <div class="aldawy-card p-6">
                <div class="aldawy-value-icon" aria-hidden="true">⚖️</div>
                <h3 class="mt-4 font-bold text-slate-800 dark:text-white">{{ __('aldawy.value_flexible_title') }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">{{ __('aldawy.value_flexible_desc') }}</p>
            </div>
            <div class="aldawy-card p-6">
                <div class="aldawy-value-icon" aria-hidden="true">🔪</div>
                <h3 class="mt-4 font-bold text-slate-800 dark:text-white">{{ __('aldawy.value_prep_title') }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">{{ __('aldawy.value_prep_desc') }}</p>
            </div>
            <div class="aldawy-card p-6">
                <div class="aldawy-value-icon" aria-hidden="true">🇪🇬</div>
                <h3 class="mt-4 font-bold text-slate-800 dark:text-white">{{ __('aldawy.value_trust_title') }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">{{ __('aldawy.value_trust_desc') }}</p>
            </div>
        </div>
    </div>
</section>

<section class="border-y border-slate-200 bg-white/60 py-14 dark:border-slate-800 dark:bg-slate-900/40">
    <div class="mx-auto max-w-6xl px-4">
        <h2 class="aldawy-section-title text-center">{{ __('aldawy.home_how_title') }}</h2>
        <ol class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                __('aldawy.home_how_step1'),
                __('aldawy.home_how_step2'),
                __('aldawy.home_how_step3'),
                __('aldawy.home_how_step4'),
            ] as $i => $step)
                <li class="flex items-start gap-4 rounded-2xl border border-slate-200 bg-canvas p-5 dark:border-slate-700 dark:bg-slate-900">
                    <span class="aldawy-step-num">{{ $i + 1 }}</span>
                    <p class="pt-1 text-sm font-medium leading-snug text-slate-700 dark:text-slate-200">{{ $step }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>

<section class="aldawy-cta-band py-16 text-white">
    <div class="mx-auto max-w-6xl px-4 text-center">
        <h2 class="text-2xl font-bold sm:text-3xl">{{ __('aldawy.home_cta_band_title') }}</h2>
        <p class="mx-auto mt-3 max-w-xl text-white/85">{{ __('aldawy.home_cta_band_sub') }}</p>
        <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('store.shop') }}" class="aldawy-btn-primary !bg-white !text-brand-dark !shadow-lg hover:!bg-surface">
                {{ __('aldawy.cta_shop') }}
            </a>
            <a href="{{ route('store.cart') }}" class="inline-flex items-center justify-center rounded-2xl border-2 border-white/40 px-8 py-3.5 text-base font-semibold text-white transition hover:border-white hover:bg-white/10">
                {{ __('aldawy.nav_cart') }}
            </a>
        </div>
    </div>
</section>
