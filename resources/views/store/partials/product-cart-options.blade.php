@php
    $locale = app()->getLocale();
    $prepEnabled = $product->relationLoaded('preparationServices')
        ? $product->preparationServices->filter(fn ($p) => (bool) $p->pivot->is_enabled)
        : collect();
    $packEnabled = $product->relationLoaded('packagingTypes')
        ? $product->packagingTypes->filter(fn ($p) => (bool) $p->pivot->is_enabled)
        : collect();
@endphp
@if ($prepEnabled->isNotEmpty() || $packEnabled->isNotEmpty())
    <details class="mt-2 w-full rounded-lg border border-slate-200 bg-surface/80 p-2 text-start text-xs dark:border-slate-700 dark:bg-slate-800/80">
        <summary class="cursor-pointer font-semibold text-slate-700 dark:text-slate-200">{{ __('aldawy.cart_options_summary') }}</summary>
        <div class="mt-2 space-y-2">
            @foreach ($prepEnabled as $svc)
                <label class="flex cursor-pointer items-center gap-2 text-slate-600 dark:text-slate-300">
                    <input type="checkbox" name="preparation_service_ids[]" value="{{ $svc->id }}" class="rounded border-slate-300 text-brand focus:ring-brand">
                    <span>{{ $svc->getTranslation('name', $locale) }}</span>
                </label>
            @endforeach
            @if ($packEnabled->isNotEmpty())
                <div class="pt-1">
                    <span class="mb-1 block font-semibold text-slate-600 dark:text-slate-300">{{ __('aldawy.cart_packaging_select') }}</span>
                    <label class="sr-only" for="packaging-{{ $product->id }}">{{ __('aldawy.cart_packaging_select') }}</label>
                    <select id="packaging-{{ $product->id }}" name="packaging_type_id" class="w-full rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-xs dark:border-slate-600 dark:bg-slate-950 dark:text-slate-100">
                        <option value="">{{ __('aldawy.cart_packaging_none') }}</option>
                        @foreach ($packEnabled as $pkg)
                            <option value="{{ $pkg->id }}">{{ $pkg->getTranslation('name', $locale) }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    </details>
@endif
