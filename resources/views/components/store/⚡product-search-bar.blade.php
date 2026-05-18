<?php

use App\Models\Product;
use Livewire\Component;

new class extends Component
{
    public string $q = '';

    public function mount(): void
    {
        $this->q = trim((string) request()->query('q', ''));
    }

    public function getSuggestionsProperty()
    {
        $term = trim($this->q);
        if (mb_strlen($term) < 2) {
            return collect();
        }

        return Product::query()
            ->active()
            ->search($term)
            ->with('category')
            ->orderBy('name->en')
            ->limit(10)
            ->get();
    }

    public function search(): void
    {
        $this->q = trim($this->q);
        if ($this->q === '') {
            $this->redirect(route('store.shop'), navigate: true);

            return;
        }

        $this->redirect(route('store.shop', ['q' => $this->q]), navigate: true);
    }

    public function clearQuery(): void
    {
        $this->q = '';
    }
};
?>

<div class="relative w-full min-w-[12rem] sm:min-w-[16rem]">
    <form wire:submit.prevent="search" class="flex w-full min-w-0 items-stretch gap-2 sm:gap-3" role="search">
        <label class="sr-only" for="store-search-q">{{ __('aldawy.search_label') }}</label>
        <div class="relative min-w-0 flex-1">
            <span class="pointer-events-none absolute start-3 top-1/2 z-10 -translate-y-1/2 text-slate-400 dark:text-slate-500" aria-hidden="true">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </span>
            <input
                id="store-search-q"
                type="text"
                role="searchbox"
                inputmode="search"
                enterkeyhint="search"
                name="q"
                wire:model.live.debounce.350ms="q"
                wire:keydown.escape="clearQuery"
                autocomplete="off"
                autocorrect="off"
                autocapitalize="off"
                spellcheck="false"
                placeholder="{{ __('aldawy.search_placeholder') }}"
                class="h-11 w-full min-w-0 rounded-xl border-2 border-slate-200/90 bg-white py-2.5 ps-10 pe-10 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 hover:border-slate-300 focus:border-brand focus:ring-4 focus:ring-brand/20 dark:border-slate-500 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-400 dark:hover:border-slate-400 dark:focus:border-brand dark:focus:ring-brand/25"
            >
            @if (trim($this->q) !== '')
                <button
                    type="button"
                    wire:click.stop="clearQuery"
                    class="absolute end-2 top-1/2 z-10 flex h-7 w-7 -translate-y-1/2 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200"
                    title="{{ __('aldawy.search_clear_input') }}"
                    aria-label="{{ __('aldawy.search_clear_input') }}"
                >
                    <span class="text-lg leading-none" aria-hidden="true">&times;</span>
                </button>
            @endif
            @if (mb_strlen(trim($this->q)) >= 2 && $this->suggestions->isNotEmpty())
                <ul
                    onmousedown="event.preventDefault()"
                    class="absolute start-0 end-0 top-full z-[200] mt-1.5 max-h-80 overflow-auto rounded-xl border border-slate-200 bg-white py-1 shadow-lg ring-1 ring-black/5 dark:border-slate-600 dark:bg-slate-900 dark:ring-white/10"
                    role="listbox"
                >
                    @foreach ($this->suggestions as $product)
                        @php
                            $loc = app()->getLocale();
                            $img = $product->display_image_url;
                            $name = $product->getTranslation('name', $loc);
                            $href = route('store.product', $product);
                        @endphp
                        <li role="option">
                            <a
                                href="{{ $href }}"
                                wire:navigate
                                class="flex items-center gap-3 px-3 py-2 text-start text-sm transition hover:bg-slate-50 active:bg-slate-100 dark:hover:bg-slate-800 dark:active:bg-slate-700"
                            >
                                <span class="relative h-12 w-12 shrink-0 overflow-hidden rounded-lg bg-slate-100 ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
                                    @if ($img)
                                        <img src="{{ $img }}" alt="" class="h-full w-full object-cover" loading="lazy">
                                    @else
                                        <span class="flex h-full w-full items-center justify-center text-lg" aria-hidden="true">{{ ($product->category?->getTranslation('slug', 'en')) === 'fruits' ? '🍎' : '🥬' }}</span>
                                    @endif
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="block truncate font-semibold text-slate-900 dark:text-white">{{ $name }}</span>
                                    <span class="block truncate text-xs text-slate-500 dark:text-slate-400">{{ $product->sku }}</span>
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        <button
            type="submit"
            class="inline-flex h-11 shrink-0 grow-0 basis-auto items-center justify-center rounded-xl bg-brand px-4 text-xs font-bold text-white shadow-sm transition hover:bg-brand-dark sm:px-5 sm:text-sm"
        >
            {{ __('aldawy.search_button') }}
        </button>
    </form>
</div>
