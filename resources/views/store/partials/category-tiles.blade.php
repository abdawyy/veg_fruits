@php
    use App\Support\ProduceStockPhoto;

    $tiles = [
        [
            'route' => route('store.fruits'),
            'title' => __('aldawy.nav_fruits'),
            'desc' => __('aldawy.cat_tile_fruits_desc'),
            'image' => ProduceStockPhoto::urlFor('Mango', 'DECO-MANGO'),
        ],
        [
            'route' => route('store.vegetables'),
            'title' => __('aldawy.nav_vegetables'),
            'desc' => __('aldawy.cat_tile_veg_desc'),
            'image' => ProduceStockPhoto::urlFor('Tomato', 'DECO-TOMATO'),
        ],
        [
            'route' => route('store.boxes'),
            'title' => __('aldawy.nav_boxes'),
            'desc' => __('aldawy.cat_tile_boxes_desc'),
            'image' => ProduceStockPhoto::urlFor('Strawberry', 'DECO-STRAW'),
        ],
        [
            'route' => route('store.services'),
            'title' => __('aldawy.nav_services'),
            'desc' => __('aldawy.cat_tile_services_desc'),
            'image' => ProduceStockPhoto::urlFor('Broccoli', 'DECO-BROCC'),
        ],
    ];
@endphp
<section class="border-b border-slate-200 bg-surface/50 py-14 dark:border-slate-800 dark:bg-slate-900/30">
    <div class="mx-auto max-w-6xl px-4">
        <div class="text-center">
            <h2 class="aldawy-section-title">{{ __('aldawy.home_categories_title') }}</h2>
            <p class="mx-auto mt-2 max-w-2xl text-slate-500 dark:text-slate-400">{{ __('aldawy.home_categories_sub') }}</p>
        </div>
        <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($tiles as $tile)
                <a href="{{ $tile['route'] }}" class="aldawy-category-tile group block overflow-hidden !p-0">
                    <div class="relative aspect-[4/3] overflow-hidden">
                        <img
                            src="{{ $tile['image'] }}"
                            alt=""
                            class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                            loading="lazy"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/75 via-slate-900/20 to-transparent" aria-hidden="true"></div>
                        <div class="absolute inset-x-0 bottom-0 p-4">
                            <h3 class="text-lg font-bold text-white">{{ $tile['title'] }}</h3>
                            <p class="mt-0.5 line-clamp-2 text-sm text-white/85">{{ $tile['desc'] }}</p>
                            <span class="mt-2 inline-flex text-sm font-semibold text-brand-light">→</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
