@php
    $locale = app()->getLocale();
    $prepEnabled = $product->preparationServices->filter(fn ($p) => (bool) $p->pivot->is_enabled && $p->is_active);
    $packEnabled = $product->packagingTypes->filter(fn ($p) => (bool) $p->pivot->is_enabled && $p->is_active);
    $selectedPrepIds = $row['preparation_service_ids'] ?? [];
@endphp
<form method="post" action="{{ route('store.cart.options') }}" class="space-y-2 text-xs">
    @csrf
    <input type="hidden" name="line" value="{{ $line }}">
    <input type="hidden" name="kg" value="{{ $row['kg'] }}">
    @foreach ($prepEnabled as $svc)
        <label class="flex cursor-pointer items-center gap-2 text-slate-600 dark:text-slate-300">
            <input
                type="checkbox"
                name="preparation_service_ids[]"
                value="{{ $svc->id }}"
                @checked(in_array($svc->id, $selectedPrepIds, true))
                class="rounded border-slate-300 text-brand focus:ring-brand"
            >
            <span>{{ $svc->getTranslation('name', $locale) }}</span>
        </label>
    @endforeach
    @if ($packEnabled->isNotEmpty())
        <div>
            <span class="mb-1 block font-semibold text-slate-600 dark:text-slate-300">{{ __('aldawy.cart_packaging_select') }}</span>
            <select name="packaging_type_id" class="w-full rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-xs dark:border-slate-600 dark:bg-slate-950 dark:text-slate-100">
                <option value="">{{ __('aldawy.cart_packaging_none') }}</option>
                @foreach ($packEnabled as $pkg)
                    <option value="{{ $pkg->id }}" @selected((int) ($row['packaging_type_id'] ?? 0) === (int) $pkg->id)>
                        {{ $pkg->getTranslation('name', $locale) }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif
    @if ($prepEnabled->isNotEmpty() || $packEnabled->isNotEmpty())
        <button type="submit" class="rounded-lg bg-surface px-2 py-1 text-xs font-semibold text-brand hover:bg-brand/10 dark:bg-slate-800">
            {{ __('aldawy.cart_options_save') }}
        </button>
    @else
        <span class="text-slate-400">—</span>
    @endif
</form>
