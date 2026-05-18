@php
    $user = auth()->user();
@endphp
<div class="mx-auto w-full max-w-lg space-y-5">
    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-gray-900 sm:p-5">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('aldawy.account_hello') }}</p>
        <p class="mt-1 text-lg font-bold text-gray-950 dark:text-white">{{ $user?->name }}</p>
        <p class="mt-1 truncate text-sm text-gray-600 dark:text-gray-300">{{ $user?->email }}</p>
    </div>

    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
        <a
            href="{{ route('store.shop') }}"
            class="flex min-h-[3.5rem] items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-3 text-center text-sm font-semibold text-gray-900 shadow-sm transition hover:border-primary-500 hover:bg-primary-50 dark:border-white/10 dark:bg-gray-900 dark:text-white dark:hover:border-primary-400 dark:hover:bg-gray-800"
        >
            {{ __('aldawy.account_tile_shop') }}
        </a>
        <a
            href="{{ route('store.cart') }}"
            class="flex min-h-[3.5rem] items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-3 text-center text-sm font-semibold text-gray-900 shadow-sm transition hover:border-primary-500 hover:bg-primary-50 dark:border-white/10 dark:bg-gray-900 dark:text-white dark:hover:border-primary-400 dark:hover:bg-gray-800"
        >
            {{ __('aldawy.account_tile_cart') }}
        </a>
        <a
            href="{{ route('store.services') }}"
            class="flex min-h-[3.5rem] items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-3 text-center text-sm font-semibold text-gray-900 shadow-sm transition hover:border-primary-500 hover:bg-primary-50 dark:border-white/10 dark:bg-gray-900 dark:text-white dark:hover:border-primary-400 dark:hover:bg-gray-800"
        >
            {{ __('aldawy.account_tile_services') }}
        </a>
        <a
            href="{{ route('filament.account.auth.profile') }}"
            wire:navigate
            class="flex min-h-[3.5rem] items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-3 text-center text-sm font-semibold text-gray-900 shadow-sm transition hover:border-primary-500 hover:bg-primary-50 dark:border-white/10 dark:bg-gray-900 dark:text-white dark:hover:border-primary-400 dark:hover:bg-gray-800"
        >
            {{ __('aldawy.account_tile_profile') }}
        </a>
        <a
            href="{{ route('filament.account.resources.my-orders.index') }}"
            wire:navigate
            class="flex min-h-[3.5rem] items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-3 text-center text-sm font-semibold text-gray-900 shadow-sm transition hover:border-primary-500 hover:bg-primary-50 dark:border-white/10 dark:bg-gray-900 dark:text-white dark:hover:border-primary-400 dark:hover:bg-gray-800"
        >
            {{ __('aldawy.account_tile_orders') }}
        </a>
    </div>

    <a
        href="{{ route('store.home') }}"
        class="block w-full rounded-xl border border-dashed border-gray-300 py-3 text-center text-sm font-medium text-gray-600 transition hover:border-primary-400 hover:text-primary-700 dark:border-white/20 dark:text-gray-300 dark:hover:text-white"
    >
        {{ __('aldawy.account_back_store') }}
    </a>
</div>
