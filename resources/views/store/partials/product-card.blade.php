@php
    $locale = app()->getLocale();
    $categoryName = $product->category?->getTranslation('name', $locale);
@endphp
<article class="aldawy-product-card group relative flex flex-col overflow-hidden">
    <div class="relative aspect-[4/3] w-full overflow-hidden bg-surface dark:bg-slate-800">
        <span class="aldawy-product-badge">{{ __('aldawy.product_fresh_badge') }}</span>
        @if ($categoryName)
            <span class="aldawy-product-cat-chip">{{ $categoryName }}</span>
        @endif

        <a href="{{ route('store.product', $product) }}" class="absolute inset-0 z-10" aria-label="{{ $product->getTranslation('name', $locale) }}"></a>

        @if ($product->display_image_url)
            <img
                src="{{ $product->display_image_url }}"
                alt="{{ $product->getTranslation('name', $locale) }}"
                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                loading="lazy"
            >
        @else
            <div
                class="flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-light/20 via-surface to-accent-light/25 text-4xl dark:from-brand/20 dark:via-slate-800 dark:to-accent/20"
                aria-hidden="true"
            >
                {{ ($product->category?->getTranslation('slug', 'en')) === 'fruits' ? '🍎' : '🥬' }}
            </div>
        @endif

        <div class="aldawy-product-card-overlay pointer-events-none absolute inset-0 flex items-end justify-center bg-gradient-to-t from-slate-900/60 via-slate-900/10 to-transparent pb-4 opacity-0 transition duration-300 group-hover:opacity-100" aria-hidden="true">
            <span class="rounded-full bg-white/95 px-4 py-1.5 text-xs font-bold text-brand shadow-lg backdrop-blur-sm">{{ __('aldawy.view_product') }}</span>
        </div>
    </div>

    <div class="relative flex flex-1 flex-col p-4 sm:p-5">
        <h3 class="text-base font-bold leading-snug text-slate-800 group-hover:text-brand dark:text-white dark:group-hover:text-brand-light sm:text-lg">
            <a href="{{ route('store.product', $product) }}" class="hover:underline">{{ $product->getTranslation('name', $locale) }}</a>
        </h3>
        <p class="mt-1 line-clamp-2 text-xs text-slate-500 sm:text-sm dark:text-slate-400">
            {{ $product->getTranslation('description', $locale) }}
        </p>

        <div class="mt-auto pt-4">
            <div class="flex flex-wrap items-end justify-between gap-2 border-t border-slate-100 pt-4 dark:border-slate-800">
                <div>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-xl font-extrabold text-brand dark:text-brand-light">{{ number_format((float) $product->price_per_kg, 2) }}</span>
                        <span class="text-xs font-semibold text-slate-400">{{ config('aldawy.currency', 'EGP') }}</span>
                    </div>
                    <span class="text-xs text-slate-500 dark:text-slate-400">/ {{ __('aldawy.per_kg') }}</span>
                </div>
                @if ($product->sell_by_piece && $product->price_per_piece)
                    <span class="rounded-lg bg-accent/10 px-2 py-1 text-xs font-semibold text-accent">
                        {{ __('aldawy.piece_price', ['price' => number_format((float) $product->price_per_piece, 2)]) }}
                    </span>
                @endif
            </div>

            @if ($product->isOutOfStock())
                <p class="mt-4 text-xs font-semibold text-danger">{{ __('aldawy.stock_out') }}</p>
            @else
                <form method="post" action="{{ route('store.cart.add') }}" data-ajax-cart class="relative z-20 mt-4 flex flex-col gap-2">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="flex flex-wrap items-end gap-2">
                        @include('store.partials.product-unit-quantity', ['product' => $product])
                        <button type="submit" class="aldawy-btn-primary !rounded-xl !px-4 !py-2.5 !text-xs !shadow-md">
                            {{ __('aldawy.add_to_cart') }}
                        </button>
                    </div>
                    @include('store.partials.product-cart-options', ['product' => $product])
                </form>
            @endif
        </div>
    </div>
</article>
