@once
    <style>
        .home-banner__viewport { overflow: hidden; border-radius: 1.5rem; touch-action: pan-y; }
        .home-banner__track { display: flex; width: 100%; transition: transform 680ms cubic-bezier(0.22, 1, 0.36, 1); will-change: transform; }
        .home-banner__slide { display: grid; flex: 0 0 100%; grid-template-columns: 1fr; min-width: 100%; overflow: hidden; border: 1px solid rgb(226 232 240); background: var(--banner-gradient, #14532d); box-shadow: 0 12px 30px rgb(15 23 42 / 0.08); }
        .dark .home-banner__slide { border-color: rgb(51 65 85); }
        @media (min-width: 768px) { .home-banner__slide { grid-template-columns: minmax(0, 5fr) minmax(0, 7fr); } }
        .home-banner__media { position: relative; min-height: 16rem; background: rgb(20 83 45 / 0.35); }
        @media (min-width: 768px) { .home-banner__media { min-height: 22rem; align-self: stretch; } }
        .home-banner__media img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center; transform: scale(1.04); }
        .home-banner__slide.is-active .home-banner__media img { animation: home-banner-ken 7.2s ease-out forwards; }
        @keyframes home-banner-ken { from { transform: scale(1.06); } to { transform: scale(1); } }
        .home-banner__copy { position: relative; display: flex; flex-direction: column; justify-content: center; padding: 1.5rem; background: rgb(20 83 45 / 0.92); backdrop-filter: blur(6px); }
        @media (min-width: 768px) { .home-banner__copy { padding: 2.5rem; } }
        .home-banner__progress { position: relative; height: 3px; margin-top: 1rem; overflow: hidden; border-radius: 999px; background: rgb(203 213 225); }
        .dark .home-banner__progress { background: rgb(71 85 105); }
        .box-carousel__viewport { overflow: hidden; }
        .box-carousel__track { display: flex; transition: transform 560ms cubic-bezier(0.22, 1, 0.36, 1); }
        .box-carousel__card { flex: 0 0 calc(100% / var(--box-per-view, 1)); min-width: 0; padding-inline: 0.5rem; }
        .home-banner__progress-bar { display: block; height: 100%; width: 100%; transform-origin: left; background: #16a34a; animation: home-banner-progress var(--banner-ms, 6800ms) linear forwards; }
        [dir='rtl'] .home-banner__progress-bar { transform-origin: right; }
        @keyframes home-banner-progress { from { transform: scaleX(0); } to { transform: scaleX(1); } }
    </style>
    <script>
        document.addEventListener('alpine:init', () => {
            if (! window.Alpine || Alpine.__homeBannerCarousel) {
                return;
            }
            Alpine.__homeBannerCarousel = true;
            Alpine.data('boxCardCarousel', (total) => ({
                active: 0,
                total: Number(total) || 0,
                perView: 1,
                init() { this.measure(); },
                measure() {
                    const width = this.$el.clientWidth || window.innerWidth;
                    const next = width >= 1024 ? 3 : (width >= 640 ? 2 : 1);
                    this.perView = next;
                    if (this.active > this.maxIndex()) this.active = this.maxIndex();
                },
                maxIndex() { return Math.max(0, this.total - this.perView); },
                next() { this.active = this.active >= this.maxIndex() ? 0 : this.active + 1; },
                prev() { this.active = this.active <= 0 ? this.maxIndex() : this.active - 1; },
                trackStyle() {
                    const shift = (this.active * 100) / this.perView;
                    return `transform: translate3d(${shift * -1}%, 0, 0)`;
                },
            }));
            Alpine.data('homeBannerCarousel', (total) => ({
                active: 0,
                total: Number(total) || 1,
                interval: 6800,
                timer: null,
                dragging: false,
                startX: 0,
                init() { this.start(); },
                go(index) {
                    const next = ((index % this.total) + this.total) % this.total;
                    if (next === this.active) return;
                    this.active = next;
                    this.start();
                },
                next() { this.go(this.active + 1); },
                prev() { this.go(this.active - 1); },
                start() {
                    this.stop();
                    if (this.total < 2) return;
                    this.timer = setInterval(() => this.next(), this.interval);
                },
                stop() {
                    if (this.timer) { clearInterval(this.timer); this.timer = null; }
                },
                trackStyle() { return `transform: translate3d(${this.active * -100}%, 0, 0)`; },
                pointerDown(event) {
                    this.dragging = true;
                    this.startX = event.clientX ?? event.touches?.[0]?.clientX ?? 0;
                    this.stop();
                },
                pointerUp(event) {
                    if (! this.dragging) return;
                    const x = event.clientX ?? event.changedTouches?.[0]?.clientX ?? this.startX;
                    const delta = x - this.startX;
                    this.dragging = false;
                    if (delta > 56) this.prev();
                    else if (delta < -56) this.next();
                    else this.start();
                },
            }));
        });
    </script>
@endonce
@php
    $slideCount = $banners->count();
    $outerBoxes = $boxes ?? collect();
@endphp
@if ($slideCount > 0 || $outerBoxes->isNotEmpty())
    <section
        class="border-b border-slate-200 bg-surface dark:border-slate-800 dark:bg-slate-900/60"
        aria-label="{{ __('aldawy.promo_banners') }}"
        @if ($slideCount > 0)
            aria-roledescription="carousel"
            x-data="homeBannerCarousel({{ $slideCount }})"
            x-init="init()"
            @mouseenter="stop()"
            @mouseleave="start()"
            @keydown.left.window.prevent="prev()"
            @keydown.right.window.prevent="next()"
        @endif
    >
        <div class="relative mx-auto max-w-6xl px-4 py-10">
            @if ($slideCount > 0)
            <div
                class="home-banner__viewport"
                @pointerdown="pointerDown($event)"
                @pointerup="pointerUp($event)"
                @pointercancel="pointerUp($event)"
            >
                <div class="home-banner__track" :style="trackStyle()" dir="ltr">
                    @foreach ($banners as $banner)
                        @php
                            $ctaProducts = $banner->ctaProducts();
                            $hots = $ctaProducts->isNotEmpty() ? $ctaProducts : $banner->hotProducts();
                            $loc = app()->getLocale();
                            $badge = (string) (data_get($banner->badge_text, $loc) ?: data_get($banner->badge_text, 'en', ''));
                            $title = (string) (data_get($banner->title, $loc) ?: data_get($banner->title, 'en', ''));
                            $ctaButton = $banner->resolvedCtaButton();
                            $cta = ($ctaButton['url'] ?? null) ?: route('store.shop', absolute: false);
                            $ctaHref = \Illuminate\Support\Str::startsWith($cta, ['http://', 'https://']) ? $cta : url($cta);
                            $ctaText = (string) (data_get($ctaButton, "label.{$loc}") ?: data_get($ctaButton, 'label.en', ''));
                            if ($ctaText === '') {
                                $ctaText = __('aldawy.cta_shop');
                            }
                        @endphp
                        <article
                            class="home-banner__slide"
                            :class="active === {{ $loop->index }} ? 'is-active' : ''"
                            style="--banner-gradient: {{ $banner->gradientCss() }};"
                            dir="{{ $loc === 'ar' ? 'rtl' : 'ltr' }}"
                            aria-hidden="{{ $loop->first ? 'false' : 'true' }}"
                            :aria-hidden="active === {{ $loop->index }} ? 'false' : 'true'"
                        >
                            <div class="home-banner__media">
                                @if ($banner->displayImageUrl())
                                    <img
                                        src="{{ $banner->displayImageUrl() }}"
                                        alt="{{ $title }}"
                                        loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                    >
                                @else
                                    <span class="absolute inset-0 flex items-center justify-center text-6xl" aria-hidden="true">🥭</span>
                                @endif
                            </div>
                            <div class="home-banner__copy">
                                @if ($badge !== '')
                                    <p class="mb-3 inline-flex w-fit rounded-full border border-accent/40 bg-accent/20 px-3 py-1 text-xs font-bold uppercase tracking-wider text-harvest">
                                        {{ $badge }}
                                    </p>
                                @endif
                                <h2 class="text-2xl font-bold leading-tight text-white sm:text-3xl">{{ $title }}</h2>
                                <p class="mt-3 max-w-xl text-sm leading-relaxed text-white/90 sm:text-base">
                                    {{ data_get($banner->subtitle, $loc) ?: data_get($banner->subtitle, 'en', '') }}
                                </p>
                                <div class="mt-6 flex flex-wrap items-center gap-4">
                                    <a
                                        href="{{ $ctaHref }}"
                                        class="inline-flex items-center justify-center rounded-2xl bg-brand px-6 py-3 text-sm font-bold text-white shadow-md transition hover:bg-brand-dark"
                                    >
                                        {{ $ctaText }}
                                    </a>
                                </div>
                                @if ($hots->isNotEmpty())
                                    <div class="mt-8">
                                        <p class="text-xs font-bold uppercase tracking-widest text-white/70">{{ __('aldawy.hot_picks') }}</p>
                                        <div class="mt-3 flex gap-3 overflow-x-auto pb-1 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                                            @foreach ($hots as $hp)
                                                <a
                                                    href="{{ route('store.product', $hp) }}"
                                                    class="flex w-28 shrink-0 flex-col overflow-hidden rounded-2xl border border-white/20 bg-white/95 text-left shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
                                                >
                                                    <span class="relative aspect-square w-full bg-surface">
                                                        @if ($hp->display_image_url)
                                                            <img src="{{ $hp->display_image_url }}" alt="" class="h-full w-full object-cover" loading="lazy">
                                                        @else
                                                            <span class="flex h-full w-full items-center justify-center text-2xl" aria-hidden="true">🛒</span>
                                                        @endif
                                                    </span>
                                                    <span class="line-clamp-2 px-2 py-2 text-[11px] font-semibold leading-tight text-slate-800">{{ $hp->getTranslation('name', $loc) }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            @if ($slideCount > 1)
                <div class="home-banner__progress" aria-hidden="true">
                    <template x-for="tick in [active]" :key="tick">
                        <span class="home-banner__progress-bar" style="--banner-ms: 6800ms"></span>
                    </template>
                </div>
                <div class="mt-4 flex items-center justify-center gap-3">
                    <button
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-lg text-slate-700 shadow-sm transition hover:bg-surface dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200"
                        @click="prev()"
                        aria-label="{{ __('Previous banner') }}"
                    >
                        <span aria-hidden="true">‹</span>
                    </button>
                    <div class="flex gap-2" role="tablist" aria-label="{{ __('Banner slides') }}">
                        @foreach ($banners as $banner)
                            <button
                                type="button"
                                role="tab"
                                class="h-2.5 rounded-full transition-all duration-300"
                                :class="active === {{ $loop->index }} ? 'w-8 bg-brand' : 'w-2.5 bg-slate-300 dark:bg-slate-600'"
                                @click="go({{ $loop->index }})"
                                :aria-selected="(active === {{ $loop->index }}).toString()"
                                aria-label="{{ data_get($banner->title, app()->getLocale()) ?: data_get($banner->title, 'en', __('Banner')) }} {{ $loop->iteration }}"
                            ></button>
                        @endforeach
                    </div>
                    <button
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-lg text-slate-700 shadow-sm transition hover:bg-surface dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200"
                        @click="next()"
                        aria-label="{{ __('Next banner') }}"
                    >
                        <span aria-hidden="true">›</span>
                    </button>
                </div>
            @endif
            @endif

            @if ($outerBoxes->isNotEmpty())
                @php $boxCount = $outerBoxes->count(); @endphp
                <div
                    class="{{ $slideCount > 0 ? 'mt-8 border-t border-slate-200 pt-8 dark:border-slate-700' : '' }}"
                    data-banner-outer-boxes
                    data-box-carousel
                    x-data="boxCardCarousel({{ $boxCount }})"
                    x-init="init()"
                    @resize.window="measure()"
                >
                    <div class="flex items-end justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('aldawy.banner_outer_boxes') }}</h2>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('aldawy.banner_outer_boxes_sub') }}</p>
                        </div>
                        <div class="flex shrink-0 items-center gap-2">
                            <a href="{{ route('store.boxes') }}" class="text-sm font-semibold text-brand hover:underline">
                                {{ __('aldawy.banner_outer_boxes_link') }}
                            </a>
                            <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-lg text-slate-700 shadow-sm transition hover:bg-surface dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200" @click="prev()" x-show="maxIndex() > 0" aria-label="{{ __('aldawy.banner_boxes_prev') }}">
                                <span aria-hidden="true">‹</span>
                            </button>
                            <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-lg text-slate-700 shadow-sm transition hover:bg-surface dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200" @click="next()" x-show="maxIndex() > 0" aria-label="{{ __('aldawy.banner_boxes_next') }}">
                                <span aria-hidden="true">›</span>
                            </button>
                        </div>
                    </div>
                    <div class="box-carousel__viewport mt-4" :style="`--box-per-view: ${perView}`">
                        <div class="box-carousel__track" dir="ltr" :style="trackStyle()">
                            @foreach ($outerBoxes as $box)
                                @php $boxLocale = app()->getLocale(); @endphp
                                <article class="box-carousel__card" data-box-card dir="{{ $boxLocale === 'ar' ? 'rtl' : 'ltr' }}">
                                    <div class="flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white text-start shadow-sm dark:border-slate-700 dark:bg-slate-900">
                                        <a href="{{ route('store.boxes.show', $box) }}" class="block">
                                            <span class="relative block aspect-[16/10] w-full bg-surface dark:bg-slate-800">
                                                <img src="{{ $box->cover_image_url }}" alt="" class="h-full w-full object-cover" loading="lazy">
                                            </span>
                                            <span class="flex flex-col px-4 pt-3">
                                                <span class="font-bold text-slate-800 dark:text-white">{{ $box->getTranslation('name', $boxLocale) }}</span>
                                                @if ($boxText = $box->getTranslation('description', $boxLocale))
                                                    <span class="mt-1 line-clamp-2 text-xs leading-relaxed text-slate-500 dark:text-slate-400">{{ $boxText }}</span>
                                                @endif
                                                <span class="mt-3 text-sm font-extrabold text-brand">
                                                    {{ number_format((float) $box->price, 2) }}
                                                    <span class="text-xs font-semibold text-slate-500">{{ config('aldawy.currency', 'EGP') }}</span>
                                                </span>
                                            </span>
                                        </a>
                                        <form method="post" action="{{ route('store.boxes.cart', $box) }}" data-ajax-cart class="mt-auto flex items-end gap-2 px-4 py-3">
                                            @csrf
                                            @if ($box->show_card_input)
                                                <div class="min-w-0 flex-1">
                                                    <label class="mb-1 block text-xs font-semibold text-slate-600 dark:text-slate-300" for="box-qty-{{ $box->id }}">{{ __('aldawy.banner_boxes_qty') }}</label>
                                                    <input id="box-qty-{{ $box->id }}" name="quantity" type="number" min="1" max="99" step="1" value="1" class="aldawy-input !w-full !py-2 !text-sm">
                                                </div>
                                            @else
                                                <input type="hidden" name="quantity" value="1">
                                            @endif
                                            <button type="submit" class="aldawy-btn-primary !px-3 !py-2.5 !text-xs">{{ __('aldawy.box_add_to_cart') }}</button>
                                        </form>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endif
