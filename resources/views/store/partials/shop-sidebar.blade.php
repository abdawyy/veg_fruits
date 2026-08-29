@php
    $locale = app()->getLocale();
    $categoryRoutes = [
        'fruits' => route('store.fruits'),
        'vegetables' => route('store.vegetables'),
    ];
    $categoryIcons = [
        'fruits' => '🍎',
        'vegetables' => '🥬',
    ];
@endphp
<aside class="aldawy-shop-sidebar hidden lg:block" aria-label="{{ __('aldawy.shop_sidebar_title') }}">
    <div class="aldawy-card !p-0 overflow-hidden">
        <div class="border-b border-slate-100 bg-gradient-to-r from-brand/5 to-transparent px-5 py-4 dark:border-slate-800 dark:from-brand/10">
            <h2 class="text-sm font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('aldawy.shop_sidebar_title') }}</h2>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ __('aldawy.shop_products_available', ['count' => $activeProductCount ?? 115]) }}</p>
        </div>
        <nav class="p-3">
            <ul class="space-y-1">
                @foreach ($categories as $category)
                    @php
                        $slugEn = (string) $category->getTranslation('slug', 'en');
                        $count = ($categoryProductPages[$category->id] ?? null)?->total() ?? 0;
                        $href = $categoryRoutes[$slugEn] ?? '#cat-'.$category->id;
                        $icon = $categoryIcons[$slugEn] ?? '🛒';
                    @endphp
                    <li>
                        <a
                            href="{{ $href }}"
                            class="aldawy-shop-cat-link group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-brand/5 hover:text-brand dark:text-slate-300 dark:hover:bg-brand/10 dark:hover:text-brand-light"
                        >
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-surface text-lg shadow-sm ring-1 ring-slate-100 transition group-hover:ring-brand/20 dark:bg-slate-800 dark:ring-slate-700" aria-hidden="true">{{ $icon }}</span>
                            <span class="min-w-0 flex-1 truncate">{{ $category->getTranslation('name', $locale) }}</span>
                            <span class="shrink-0 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-bold text-slate-500 dark:bg-slate-800 dark:text-slate-400">{{ $count }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
        <div class="border-t border-slate-100 px-5 py-4 dark:border-slate-800">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('aldawy.shop_sidebar_quick') }}</p>
            <ul class="mt-2 space-y-1 text-sm">
                <li><a href="{{ route('store.boxes') }}" class="font-medium text-brand hover:underline">{{ __('aldawy.nav_boxes') }}</a></li>
                <li><a href="{{ route('store.services') }}" class="font-medium text-brand hover:underline">{{ __('aldawy.nav_services') }}</a></li>
            </ul>
        </div>
    </div>
    <a href="#shop-top" class="aldawy-shop-back-top mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 transition hover:text-brand dark:text-slate-400 dark:hover:text-brand-light">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" /></svg>
        {{ __('aldawy.shop_jump_top') }}
    </a>
</aside>
