<div class="aldawy-empty-state">
    <div class="aldawy-empty-state-icon" aria-hidden="true">
        @if (($type ?? '') === 'search')
            🔍
        @else
            🥬
        @endif
    </div>
    <h2 class="text-xl font-bold text-slate-800 dark:text-white">
        @if (($type ?? '') === 'search')
            {{ __('aldawy.shop_empty_search_title') }}
        @else
            {{ __('aldawy.empty_catalog') }}
        @endif
    </h2>
    <p class="mt-2 max-w-md text-sm text-slate-500 dark:text-slate-400">
        @if (($type ?? '') === 'search')
            {{ __('aldawy.shop_empty_search_hint', ['q' => $q ?? '']) }}
        @else
            {{ __('aldawy.shop_empty_catalog_hint') }}
        @endif
    </p>
    <div class="mt-8 flex flex-wrap justify-center gap-3">
        @if (($type ?? '') === 'search')
            <a href="{{ route('store.shop') }}" class="aldawy-btn-primary">{{ __('aldawy.search_clear') }}</a>
        @endif
        <a href="{{ route('store.home') }}" class="aldawy-btn-secondary">{{ __('aldawy.nav_home') }}</a>
    </div>
</div>
