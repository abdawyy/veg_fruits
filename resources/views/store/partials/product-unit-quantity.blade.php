@php
    use App\Support\StoreCart;

    $units = $product->availableUnits();
    $defaultUnit = $product->defaultOrderUnit();
    $defaultOrigin = $product->defaultOrigin();
    $origins = $product->availableOrigins();
    $minKg = $product->minimumOrderQuantity('kg');
    $minGram = $product->minimumOrderQuantity('gram');
    $minPiece = $product->minimumOrderQuantity('piece');
    $defaultMin = match ($defaultUnit) {
        'gram' => $minGram,
        'piece' => $minPiece,
        default => $minKg,
    };
    $defaultQty = $defaultUnit === 'kg'
        ? (string) max((float) $defaultMin, 1)
        : $defaultMin;
    $qtyValue = StoreCart::formatQuantity($defaultQty, $defaultUnit);
    $qtyStep = $defaultUnit === 'kg' ? '0.25' : '1';
    $qtyMin = StoreCart::formatQuantity($defaultMin, $defaultUnit);
    $priceMap = [];
    foreach ($units as $unitOption) {
        $priceMap[$unitOption] = ['_label' => StoreCart::unitLabel($unitOption)];
        foreach ($origins as $originOption) {
            $priceMap[$unitOption][$originOption] = StoreCart::formatAmount(
                StoreCart::unitPriceForProduct($product, $unitOption, $originOption) ?? '0'
            );
        }
    }
@endphp
<div
    class="flex flex-wrap items-end gap-2"
    data-unit-quantity
    data-min-kg="{{ $minKg }}"
    data-min-gram="{{ $minGram }}"
    data-min-piece="{{ $minPiece }}"
    data-prices='@json($priceMap)'
>
    @if (count($origins) > 1)
        <div>
            <label class="mb-1 block text-xs font-semibold text-slate-600 dark:text-slate-300" for="origin-{{ $product->id }}">{{ __('aldawy.cart_origin') }}</label>
            <select id="origin-{{ $product->id }}" name="origin" class="rounded-lg border border-slate-200 px-2 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 aldawy-input !w-auto !py-2 !text-sm">
                @foreach ($origins as $origin)
                    <option value="{{ $origin }}" @selected($origin === $defaultOrigin)>{{ StoreCart::originLabel($origin) }}</option>
                @endforeach
            </select>
        </div>
    @else
        <input type="hidden" name="origin" value="{{ $defaultOrigin }}">
    @endif
    @if (count($units) > 1)
        <div>
            <label class="mb-1 block text-xs font-semibold text-slate-600 dark:text-slate-300" for="unit-{{ $product->id }}">{{ __('aldawy.cart_unit') }}</label>
            <select id="unit-{{ $product->id }}" name="unit" class="rounded-lg border border-slate-200 px-2 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 aldawy-input !w-auto !py-2 !text-sm">
                @foreach ($units as $unit)
                    <option value="{{ $unit }}" @selected($unit === $defaultUnit)>{{ StoreCart::unitLabel($unit) }}</option>
                @endforeach
            </select>
        </div>
    @else
        <input type="hidden" name="unit" value="{{ $defaultUnit }}">
    @endif
    <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600 dark:text-slate-300" for="qty-{{ $product->id }}" data-qty-heading data-qty-template="{{ __('aldawy.cart_qty', ['unit' => ':unit']) }}">{{ __('aldawy.cart_qty', ['unit' => StoreCart::unitLabel($defaultUnit)]) }}</label>
        <input
            id="qty-{{ $product->id }}"
            name="quantity"
            type="number"
            step="{{ $qtyStep }}"
            min="{{ $qtyMin }}"
            value="{{ $qtyValue }}"
            class="w-28 rounded-lg border border-slate-200 px-2 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 aldawy-input !w-28 !py-2 !text-sm"
        >
    </div>
</div>
@if (count($units) > 1)
    <script>
        (function () {
            var root = document.getElementById('qty-{{ $product->id }}')?.closest('[data-unit-quantity]');
            if (!root) return;
            var unitSel = root.querySelector('[name="unit"]');
            var qty = root.querySelector('[name="quantity"]');
            var heading = root.querySelector('[data-qty-heading]');
            if (!unitSel || !qty) return;
            var minKg = parseFloat(root.dataset.minKg || '0.25');
            var minGram = parseFloat(root.dataset.minGram || '1');
            var minPiece = parseFloat(root.dataset.minPiece || '1');
            var prices = {};
            try { prices = JSON.parse(root.dataset.prices || '{}'); } catch (e) { prices = {}; }
            var offer = root.closest('[data-product-offer]');
            function apply(reset) {
                var unit = unitSel.value;
                var info = prices[unit] || {};
                if (heading) {
                    var template = heading.getAttribute('data-qty-template') || 'Quantity (:unit)';
                    heading.textContent = template.replace(':unit', info._label || unit);
                }
                if (offer) {
                    offer.querySelectorAll('[data-origin-amount]').forEach(function (el) {
                        var origin = el.getAttribute('data-origin-amount');
                        if (info[origin]) el.textContent = info[origin];
                    });
                    offer.querySelectorAll('[data-origin-unit]').forEach(function (el) {
                        if (info._label) el.textContent = info._label;
                    });
                    offer.querySelectorAll('[data-piece-badge]').forEach(function (el) {
                        el.hidden = unit === 'piece';
                    });
                }
                if (unit === 'piece') {
                    qty.step = '1';
                    qty.min = String(minPiece);
                    if (reset) qty.value = String(minPiece);
                } else if (unit === 'gram') {
                    qty.step = '1';
                    qty.min = String(minGram);
                    if (reset) qty.value = String(Math.max(1, Math.round(minGram)));
                } else {
                    qty.step = '0.25';
                    qty.min = String(minKg);
                    if (reset) qty.value = String(Math.max(minKg, 1));
                }
                if (!reset) {
                    var current = parseFloat(qty.value);
                    var min = parseFloat(qty.min);
                    if (!current || current < min) qty.value = qty.min;
                }
            }
            unitSel.addEventListener('change', function () { apply(true); });
            apply(false);
        })();
    </script>
@endif
