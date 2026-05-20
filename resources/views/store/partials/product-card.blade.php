@php
    $locale = app()->getLocale();
@endphp
<article class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-canvas shadow-sm transition duration-300 hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900 dark:hover:border-slate-600">
    <div class="relative aspect-[4/3] w-full overflow-hidden bg-surface dark:bg-slate-800">
        <a href="{{ route('store.product', $product) }}" class="absolute inset-0 z-10" aria-label="{{ $product->getTranslation('name', $locale) }}"></a>
        @if ($product->display_image_url)
            <img
                src="{{ $product->display_image_url }}"
                alt="{{ $product->getTranslation('name', $locale) }}"
                class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.02]"
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
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-900/10 via-transparent to-transparent dark:from-slate-950/40" aria-hidden="true"></div>
    </div>
    <div class="relative flex flex-1 flex-col border-t border-slate-100 p-4 dark:border-slate-800 sm:p-5">
        <div class="mb-2 flex items-start justify-between gap-2">
            <h3 class="text-base font-bold leading-snug text-slate-800 group-hover:text-brand dark:text-white dark:group-hover:text-brand-light sm:text-lg">
                <a href="{{ route('store.product', $product) }}" class="hover:underline">{{ $product->getTranslation('name', $locale) }}</a>
            </h3>
            <span class="shrink-0 rounded-md border border-slate-200 bg-surface px-2 py-0.5 font-mono text-[10px] text-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400">{{ $product->sku }}</span>
        </div>
        <p class="line-clamp-2 text-xs text-slate-500 sm:text-sm dark:text-slate-400">
            {{ $product->getTranslation('description', $locale) }}
        </p>
        <div class="mt-4 flex flex-wrap items-baseline gap-2 border-t border-slate-100 pt-4 dark:border-slate-800">
            <span class="text-lg font-bold text-brand dark:text-brand-light">{{ number_format((float) $product->price_per_kg, 2) }}</span>
            <span class="text-sm text-slate-500 dark:text-slate-400">{{ config('aldawy.currency', 'EGP') }} / {{ __('aldawy.per_kg') }}</span>
        </div>
        @if ($product->sell_by_piece && $product->price_per_piece)
            <p class="mt-1 text-xs font-medium text-slate-600 dark:text-slate-300">
                {{ __('aldawy.piece_price', ['price' => number_format((float) $product->price_per_piece, 2)]) }}
            </p>
        @endif
        <form method="post" action="{{ route('store.cart.add') }}" data-ajax-cart class="mt-4 flex flex-col gap-2 border-t border-slate-100 pt-4 dark:border-slate-800">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <div class="flex flex-wrap items-end gap-2">
                @include('store.partials.product-unit-quantity', ['product' => $product])
                <button type="submit" class="rounded-xl bg-brand px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-brand-dark">
                    {{ __('aldawy.add_to_cart') }}
                </button>
            </div>
            @include('store.partials.product-cart-options', ['product' => $product])
        </form>
    </div>
</article>
