@php
    $locale = app()->getLocale();
    $line = (int) $row['line'];
    $isBox = ($row['kind'] ?? 'product') === 'box';
    $currency = config('aldawy.currency', 'EGP');
@endphp

<article class="aldawy-cart-line aldawy-card overflow-hidden !shadow-soft">
    @if ($isBox)
        @php /** @var \App\Models\ProduceBox $box */ $box = $row['produce_box']; @endphp
        <div class="flex flex-col gap-4 p-4 sm:flex-row sm:items-start sm:p-5">
            <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-brand/20 to-accent/15 text-3xl ring-1 ring-slate-200 dark:ring-slate-700" aria-hidden="true">
                📦
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-accent">{{ __('aldawy.cart_box_line') }}</p>
                        <h3 class="mt-0.5 text-lg font-bold text-slate-800 dark:text-white">{{ $box->getTranslation('name', $locale) }}</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('aldawy.box_items_count', ['count' => $box->items->count()]) }}</p>
                    </div>
                    <p class="text-xl font-extrabold text-brand">
                        {{ number_format((float) $row['line_subtotal'], 2) }}
                        <span class="text-sm font-semibold text-slate-500">{{ $currency }}</span>
                    </p>
                </div>
                @if (! empty($row['price_changed']))
                    <p class="mt-2 inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800 dark:bg-amber-950 dark:text-amber-200">{{ __('aldawy.cart_line_price_changed') }}</p>
                @endif
                <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4 dark:border-slate-800">
                    <span class="text-sm text-slate-500">1 {{ __('aldawy.per_box') }}</span>
                    <form method="post" action="{{ route('store.cart.remove') }}" onsubmit="return confirm('{{ __('aldawy.cart_remove_confirm') }}');">
                        @csrf
                        <input type="hidden" name="line" value="{{ $line }}">
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-semibold text-danger transition hover:bg-danger/10">
                            <span aria-hidden="true">✕</span>
                            {{ __('aldawy.cart_remove') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @else
        @php /** @var \App\Models\Product $product */ $product = $row['product']; @endphp
        <div class="flex flex-col gap-4 p-4 sm:flex-row sm:items-start sm:p-5">
            <a href="{{ route('store.product', $product) }}" class="relative shrink-0 overflow-hidden rounded-2xl ring-1 ring-slate-200 dark:ring-slate-700">
                @if ($product->display_image_url)
                    <img src="{{ $product->display_image_url }}" alt="{{ $product->getTranslation('name', $locale) }}" class="h-24 w-24 object-cover sm:h-28 sm:w-28" loading="lazy">
                @else
                    <div class="flex h-24 w-24 items-center justify-center bg-surface text-3xl sm:h-28 sm:w-28 dark:bg-slate-800">
                        {{ ($product->category?->getTranslation('slug', 'en')) === 'fruits' ? '🍎' : '🥬' }}
                    </div>
                @endif
            </a>
            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div class="min-w-0">
                        <a href="{{ route('store.product', $product) }}" class="text-lg font-bold text-slate-800 hover:text-brand dark:text-white dark:hover:text-brand-light">
                            {{ $product->getTranslation('name', $locale) }}
                        </a>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            {{ number_format((float) $product->price_per_kg, 2) }} {{ $currency }} / {{ __('aldawy.per_kg') }}
                        </p>
                    </div>
                    <p class="text-xl font-extrabold text-brand">
                        {{ number_format((float) $row['line_subtotal'], 2) }}
                        <span class="text-sm font-semibold text-slate-500">{{ $currency }}</span>
                    </p>
                </div>

                @if (bccomp((string) $row['services_surcharge'], '0', 4) > 0 || bccomp((string) $row['packaging_surcharge'], '0', 4) > 0)
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                        {{ __('aldawy.cart_line_base') }} {{ number_format((float) $row['line_base'], 2) }}
                        @if (bccomp((string) $row['services_surcharge'], '0', 4) > 0)
                            + {{ __('aldawy.cart_line_svc') }} {{ number_format((float) $row['services_surcharge'], 2) }}
                        @endif
                        @if (bccomp((string) $row['packaging_surcharge'], '0', 4) > 0)
                            + {{ __('aldawy.cart_line_pkg_fee') }} {{ number_format((float) $row['packaging_surcharge'], 2) }}
                        @endif
                    </p>
                @endif

                @if (! empty($row['price_changed']))
                    <p class="mt-2 inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800 dark:bg-amber-950 dark:text-amber-200">{{ __('aldawy.cart_line_price_changed') }}</p>
                @endif

                @php
                    $prepEnabled = $product->preparationServices->filter(fn ($p) => (bool) $p->pivot->is_enabled && $p->is_active);
                    $packEnabled = $product->packagingTypes->filter(fn ($p) => (bool) $p->pivot->is_enabled && $p->is_active);
                    $hasOptions = $prepEnabled->isNotEmpty() || $packEnabled->isNotEmpty();
                @endphp

                @if ($hasOptions)
                    <details class="mt-4 rounded-xl border border-slate-200 bg-surface/80 dark:border-slate-700 dark:bg-slate-800/50">
                        <summary class="cursor-pointer list-none px-4 py-2.5 text-sm font-semibold text-slate-700 marker:hidden dark:text-slate-200 [&::-webkit-details-marker]:hidden">
                            {{ __('aldawy.cart_options_summary') }}
                        </summary>
                        <div class="border-t border-slate-200 px-4 py-3 dark:border-slate-700">
                            @include('store.partials.cart-line-options-form', [
                                'product' => $product,
                                'row' => $row,
                                'line' => $line,
                            ])
                        </div>
                    </details>
                @endif

                <div class="mt-4 flex flex-wrap items-end justify-between gap-4 border-t border-slate-100 pt-4 dark:border-slate-800">
                    <form method="post" action="{{ route('store.cart.update') }}" class="flex flex-wrap items-end gap-2">
                        @csrf
                        <input type="hidden" name="line" value="{{ $line }}">
                        <input type="hidden" name="unit" value="{{ $row['unit'] }}">
                        <label class="block">
                            <span class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('aldawy.cart_qty') }}</span>
                            <div class="flex items-center gap-2">
                                <input
                                    type="number"
                                    name="quantity"
                                    step="{{ $row['unit'] === 'piece' ? '1' : '0.25' }}"
                                    min="{{ $row['unit'] === 'piece' ? '1' : '0.25' }}"
                                    value="{{ $row['quantity'] }}"
                                    class="w-28 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold dark:border-slate-600 dark:bg-slate-950 dark:text-slate-100"
                                >
                                <span class="text-sm text-slate-500">{{ $row['unit'] === 'piece' ? __('aldawy.per_piece') : __('aldawy.per_kg') }}</span>
                            </div>
                        </label>
                        <button type="submit" class="rounded-xl border border-brand/30 bg-brand/10 px-4 py-2 text-xs font-bold text-brand transition hover:bg-brand/20 dark:bg-brand/20">
                            {{ __('aldawy.cart_update') }}
                        </button>
                    </form>
                    <form method="post" action="{{ route('store.cart.remove') }}" onsubmit="return confirm('{{ __('aldawy.cart_remove_confirm') }}');">
                        @csrf
                        <input type="hidden" name="line" value="{{ $line }}">
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl px-3 py-2 text-sm font-semibold text-danger transition hover:bg-danger/10">
                            <span aria-hidden="true">✕</span>
                            {{ __('aldawy.cart_remove') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif
</article>
