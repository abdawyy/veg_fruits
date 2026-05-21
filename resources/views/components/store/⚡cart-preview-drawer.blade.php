<?php

use App\Support\StoreCart;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public bool $open = false;

    #[On('cart-updated')]
    public function refreshCart(): void
    {
        //
    }

    #[On('open-cart-preview')]
    public function openDrawer(): void
    {
        $this->open = true;
    }

    public function close(): void
    {
        $this->open = false;
    }

    public function getRowsProperty()
    {
        return StoreCart::resolved();
    }

    public function getSubtotalProperty(): string
    {
        return StoreCart::subtotal();
    }
}; ?>

<div>
    @if ($open)
        <div
            class="fixed inset-0 z-[80] bg-black/40"
            wire:click="close"
            aria-hidden="true"
        ></div>
        <div
            class="fixed inset-y-0 end-0 z-[85] flex w-full max-w-md flex-col border-s border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900"
            role="dialog"
            aria-modal="true"
            aria-label="{{ __('aldawy.cart_preview_title') }}"
        >
            <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3 dark:border-slate-800">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('aldawy.cart_preview_title') }}</h2>
                <button type="button" wire:click="close" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800" aria-label="{{ __('aldawy.cart_preview_close') }}">✕</button>
            </div>
            <div class="flex-1 overflow-y-auto px-4 py-4">
                @if ($this->rows->isEmpty())
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('aldawy.cart_empty') }}</p>
                @else
                    <ul class="space-y-3">
                        @foreach ($this->rows as $row)
                            @php $locale = app()->getLocale(); @endphp
                            <li class="rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-700">
                                @if (($row['kind'] ?? 'product') === 'box')
                                    <span class="font-semibold">{{ $row['produce_box']->getTranslation('name', $locale) }}</span>
                                    <span class="block text-xs text-slate-500">1 {{ __('aldawy.per_box') }}</span>
                                @else
                                    <span class="font-semibold">{{ $row['product']->getTranslation('name', $locale) }}</span>
                                    <span class="block text-xs text-slate-500">
                                        {{ $row['quantity'] }}
                                        {{ $row['unit'] === 'piece' ? __('aldawy.per_piece') : __('aldawy.per_kg') }}
                                    </span>
                                @endif
                                <span class="mt-1 block font-semibold text-brand">{{ number_format((float) $row['line_subtotal'], 2) }} {{ config('aldawy.currency', 'EGP') }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="border-t border-slate-200 px-4 py-4 dark:border-slate-800">
                <p class="flex justify-between text-sm font-semibold text-slate-800 dark:text-slate-100">
                    <span>{{ __('aldawy.cart_subtotal') }}</span>
                    <span>{{ number_format((float) $this->subtotal, 2) }} {{ config('aldawy.currency', 'EGP') }}</span>
                </p>
                <a href="{{ route('store.cart') }}" wire:navigate class="mt-4 block w-full rounded-xl bg-brand py-3 text-center text-sm font-bold text-white hover:bg-brand-dark">
                    {{ __('aldawy.cart_preview_full') }}
                </a>
            </div>
        </div>
    @endif
</div>
