@php
    $locale = app()->getLocale();
@endphp
<form method="post" action="{{ route('store.cart.add') }}" data-ajax-cart class="mt-6 flex flex-wrap items-end gap-2">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <label class="sr-only" for="kg-detail-{{ $product->id }}">{{ __('aldawy.cart_qty') }}</label>
    <input id="kg-detail-{{ $product->id }}" name="kg" type="number" step="0.25" min="0.25" value="1"
        class="w-28 rounded-lg border border-slate-200 px-2 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
    @include('store.partials.product-cart-options', ['product' => $product])
    <button type="submit" class="rounded-xl bg-brand px-5 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-brand-dark">
        {{ __('aldawy.add_to_cart') }}
    </button>
</form>
