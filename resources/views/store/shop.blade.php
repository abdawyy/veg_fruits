@extends('layouts.store')

@section('content')
    <div class="aldawy-hero-bg border-b border-slate-200 py-12 dark:border-slate-800">
        <div class="relative z-10 mx-auto max-w-6xl px-4 text-center">
            <h1 class="text-3xl font-bold text-slate-800 sm:text-4xl dark:text-white">{{ __('aldawy.shop_title') }}</h1>
            <p class="mx-auto mt-3 max-w-2xl text-slate-500 dark:text-slate-400">{{ __('aldawy.shop_subtitle') }}</p>
            @if (trim((string) ($q ?? '')) !== '')
                <p class="mt-4 text-sm font-medium text-accent">
                    {{ __('aldawy.search_results_for', ['q' => $q]) }}
                </p>
                <p class="mt-2">
                    <a href="{{ route('store.shop') }}" class="text-sm font-semibold text-brand underline-offset-2 hover:text-brand-dark hover:underline">{{ __('aldawy.search_clear') }}</a>
                </p>
            @endif
        </div>
    </div>

    <div class="mx-auto max-w-6xl bg-canvas px-4 py-16 dark:bg-slate-950">
        @if (trim((string) ($q ?? '')) !== '')
            @if (($results ?? collect())->isEmpty())
                <p class="rounded-2xl border border-dashed border-slate-200 bg-surface p-16 text-center text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400">
                    {{ __('aldawy.search_no_results', ['q' => $q]) }}
                </p>
            @else
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($results as $product)
                        @include('store.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
                <div class="mt-10 flex justify-center [&_nav]:max-w-full">
                    {{ $results->links() }}
                </div>
            @endif
        @else
            <div class="space-y-16">
                @forelse ($categories as $category)
                    @php($productsPage = $categoryProductPages[$category->id] ?? null)
                    <section aria-labelledby="cat-{{ $category->id }}">
                        <div class="flex flex-wrap items-center gap-4 border-b border-slate-200 pb-4 dark:border-slate-800">
                            <h2 id="cat-{{ $category->id }}" class="text-2xl font-bold text-slate-800 dark:text-white">
                                {{ $category->getTranslation('name', app()->getLocale()) }}
                            </h2>
                            <span class="rounded-full border border-accent/25 bg-accent/10 px-3 py-1 text-xs font-bold uppercase tracking-wide text-accent">
                                {{ $productsPage?->total() ?? 0 }} {{ __('aldawy.items') }}
                            </span>
                        </div>
                        @if ($productsPage && $productsPage->isNotEmpty())
                            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                @foreach ($productsPage as $product)
                                    @include('store.partials.product-card', ['product' => $product])
                                @endforeach
                            </div>
                            <div class="mt-8 flex justify-center [&_nav]:max-w-full">
                                {{ $productsPage->links() }}
                            </div>
                        @endif
                    </section>
                @empty
                    <p class="rounded-2xl border border-dashed border-slate-200 bg-surface p-16 text-center text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400">
                        {{ __('aldawy.empty_catalog') }}
                    </p>
                @endforelse
            </div>
        @endif
    </div>
@endsection
