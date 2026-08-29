@php
    $productCount = $productCount ?? ($activeProductCount ?? 115);
@endphp
<section class="border-b border-slate-200/80 bg-white/70 py-3 backdrop-blur-sm dark:border-slate-800 dark:bg-slate-900/70" aria-label="{{ __('aldawy.badge_fresh') }}">
    <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-center gap-2 px-4 sm:gap-3">
        <span class="aldawy-trust-pill">
            <span aria-hidden="true">💵</span>
            {{ __('aldawy.trust_cod') }}
        </span>
        <span class="aldawy-trust-pill">
            <span aria-hidden="true">🌐</span>
            {{ __('aldawy.trust_bilingual') }}
        </span>
        <span class="aldawy-trust-pill">
            <span aria-hidden="true">🛒</span>
            {{ __('aldawy.trust_products', ['count' => $productCount]) }}
        </span>
        <span class="aldawy-trust-pill hidden sm:inline-flex">
            <span aria-hidden="true">✨</span>
            {{ __('aldawy.trust_prep') }}
        </span>
    </div>
</section>
