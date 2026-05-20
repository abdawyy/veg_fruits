@php
    $sellPiece = $product->sell_by_piece && $product->price_per_piece;
    $defaultUnit = $sellPiece ? 'kg' : 'kg';
@endphp
<div class="flex flex-wrap items-end gap-2" data-unit-quantity>
    @if ($sellPiece)
        <div>
            <label class="mb-1 block text-xs font-semibold text-slate-600 dark:text-slate-300" for="unit-{{ $product->id }}">{{ __('aldawy.cart_unit') }}</label>
            <select id="unit-{{ $product->id }}" name="unit" class="rounded-lg border border-slate-200 px-2 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                <option value="kg">{{ __('aldawy.per_kg') }}</option>
                <option value="piece">{{ __('aldawy.per_piece') }}</option>
            </select>
        </div>
    @else
        <input type="hidden" name="unit" value="kg">
    @endif
    <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600 dark:text-slate-300" for="qty-{{ $product->id }}">{{ __('aldawy.cart_qty') }}</label>
        <input
            id="qty-{{ $product->id }}"
            name="quantity"
            type="number"
            step="0.25"
            min="0.25"
            value="1"
            data-qty-kg
            class="w-28 rounded-lg border border-slate-200 px-2 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
        >
    </div>
</div>
@if ($sellPiece)
    <script>
        (function () {
            var root = document.getElementById('qty-{{ $product->id }}')?.closest('[data-unit-quantity]');
            if (!root) return;
            var unitSel = root.querySelector('[name="unit"]');
            var qty = root.querySelector('[name="quantity"]');
            if (!unitSel || !qty) return;
            function sync() {
                if (unitSel.value === 'piece') {
                    qty.step = '1';
                    qty.min = '1';
                    qty.value = Math.max(1, Math.round(parseFloat(qty.value) || 1));
                } else {
                    qty.step = '0.25';
                    qty.min = '0.25';
                }
            }
            unitSel.addEventListener('change', sync);
            sync();
        })();
    </script>
@endif
