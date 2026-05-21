@php
    $locale = app()->getLocale();
@endphp
@if ($product->isOutOfStock())
    <p class="mt-6 rounded-xl border border-danger/30 bg-danger/5 px-4 py-3 text-sm font-semibold text-danger">{{ __('aldawy.stock_out') }}</p>
@else
<form
    method="post"
    action="{{ route('store.cart.add') }}"
    data-ajax-cart
    data-product-estimate="{{ route('store.product.estimate', $product) }}"
    class="mt-6 flex flex-col gap-3"
>
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    @include('store.partials.product-unit-quantity', ['product' => $product])
    <div class="flex flex-wrap items-end gap-2">
        @include('store.partials.product-cart-options', ['product' => $product])
        <button type="submit" class="rounded-xl bg-brand px-5 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-brand-dark">
            {{ __('aldawy.add_to_cart') }}
        </button>
    </div>
    <p class="text-sm text-slate-600 dark:text-slate-300" data-estimate-output aria-live="polite">
        <span class="font-semibold text-slate-700 dark:text-slate-200">{{ __('aldawy.product_estimate_label') }}:</span>
        <span data-estimate-value>—</span>
    </p>
</form>
@endif
