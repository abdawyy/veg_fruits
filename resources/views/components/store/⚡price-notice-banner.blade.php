<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div
    role="status"
    class="relative overflow-hidden border-b border-slate-200 dark:border-slate-700"
>
    <div
        class="pointer-events-none absolute inset-0 bg-[url('https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=2000&q=75')] bg-cover bg-center opacity-[0.18] dark:opacity-[0.12]"
        aria-hidden="true"
    ></div>
    <div class="absolute inset-0 bg-gradient-to-r from-harvest/95 via-harvest/90 to-harvest/80 dark:from-yellow-950/90 dark:via-yellow-950/85 dark:to-yellow-950/70" aria-hidden="true"></div>
    <div class="relative px-4 py-2.5 text-center text-xs font-semibold text-slate-800 sm:text-sm dark:text-yellow-50 {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
        <span class="inline-flex items-center justify-center gap-2 sm:inline-flex">
            <svg class="h-4 w-4 shrink-0 text-accent" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            {{ __('aldawy.price_notice') }}
        </span>
    </div>
</div>
