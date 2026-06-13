@if ($banners->isNotEmpty())

    @php

        $slideCount = $banners->count();

    @endphp

    <section

        class="border-b border-slate-200 bg-surface dark:border-slate-800 dark:bg-slate-900/60"

        aria-label="{{ __('aldawy.promo_banners') }}"

        x-data="{

            active: 0,

            total: {{ $slideCount }},

            timer: null,

            go(i) { this.active = (i + this.total) % this.total },

            next() { this.go(this.active + 1) },

            prev() { this.go(this.active - 1) },

            start() {

                if (this.total < 2) return;

                this.stop();

                this.timer = setInterval(() => this.next(), 7000);

            },

            stop() { if (this.timer) { clearInterval(this.timer); this.timer = null } },

        }"

        x-init="start()"

        @mouseenter="stop()"

        @mouseleave="start()"

    >

        <div class="relative mx-auto max-w-6xl px-4 py-10">

            <div class="relative overflow-hidden rounded-3xl">

                @foreach ($banners as $banner)

                    @php

                        $cta = $banner->cta_url ?: route('store.shop');

                        $ctaHref = \Illuminate\Support\Str::startsWith($cta, ['http://', 'https://']) ? $cta : url($cta);

                        $hots = $banner->hotProducts();

                    @endphp

                    <article

                        x-show="active === {{ $loop->index }}"

                        x-cloak

                        @if ($slideCount > 1)

                            x-transition:enter="transition ease-out duration-500"

                            x-transition:enter-start="opacity-0 translate-x-4"

                            x-transition:enter-end="opacity-100 translate-x-0"

                            x-transition:leave="transition ease-in duration-300"

                            x-transition:leave-start="opacity-100 translate-x-0"

                            x-transition:leave-end="opacity-0 -translate-x-4"

                        @endif

                        class="overflow-hidden border border-slate-200 shadow-md dark:border-slate-700"

                        style="background: {{ $banner->gradientCss() }};"

                        @if ($loop->first) aria-hidden="false" @else aria-hidden="true" @endif

                    >

                        <div class="grid gap-0 md:grid-cols-12">

                            <div class="relative md:col-span-5">

                                <div class="absolute inset-0 bg-brand-dark/10" aria-hidden="true"></div>

                                @if ($banner->displayImageUrl())

                                    <img

                                        src="{{ $banner->displayImageUrl() }}"

                                        alt=""

                                        class="h-56 w-full object-cover md:h-full md:min-h-[280px]"

                                        loading="lazy"

                                    >

                                @else

                                    <div class="flex h-56 items-center justify-center bg-surface text-6xl dark:bg-slate-800 md:h-full md:min-h-[280px]" aria-hidden="true">🥭</div>

                                @endif

                            </div>

                            <div class="relative flex flex-col justify-center bg-brand-dark/92 p-6 backdrop-blur-sm md:col-span-7 md:p-10">

                                @php

                                    $loc = app()->getLocale();

                                    $badge = (string) (data_get($banner->badge_text, $loc) ?: data_get($banner->badge_text, 'en', ''));

                                @endphp

                                @if ($badge !== '')

                                    <p class="mb-3 inline-flex w-fit rounded-full border border-accent/40 bg-accent/20 px-3 py-1 text-xs font-bold uppercase tracking-wider text-harvest">

                                        {{ $badge }}

                                    </p>

                                @endif

                                <h2 class="text-2xl font-bold leading-tight text-white sm:text-3xl">

                                    {{ data_get($banner->title, $loc) ?: data_get($banner->title, 'en', '') }}

                                </h2>

                                <p class="mt-3 max-w-xl text-sm leading-relaxed text-white/90 sm:text-base">

                                    {{ data_get($banner->subtitle, $loc) ?: data_get($banner->subtitle, 'en', '') }}

                                </p>

                                <div class="mt-6 flex flex-wrap items-center gap-4">

                                    <a

                                        href="{{ $ctaHref }}"

                                        class="inline-flex items-center justify-center rounded-2xl bg-brand px-6 py-3 text-sm font-bold text-white shadow-md transition hover:bg-brand-dark"

                                    >

                                        {{ (string) (data_get($banner->cta_label, $loc) ?: data_get($banner->cta_label, 'en', '')) ?: __('aldawy.cta_shop') }}

                                    </a>

                                </div>

                                @if ($hots->isNotEmpty())

                                    <div class="mt-8">

                                        <p class="text-xs font-bold uppercase tracking-widest text-white/70">{{ __('aldawy.hot_picks') }}</p>

                                        <div class="mt-3 flex gap-3 overflow-x-auto pb-1 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">

                                            @foreach ($hots as $hp)

                                                <a

                                                    href="{{ route('store.shop', ['q' => $hp->getTranslation('name', app()->getLocale())]) }}"

                                                    class="flex w-28 shrink-0 flex-col overflow-hidden rounded-2xl border border-white/20 bg-white/95 text-left shadow-sm transition hover:shadow-md"

                                                >

                                                    <span class="relative aspect-square w-full bg-surface dark:bg-slate-800">

                                                        @if ($hp->display_image_url)

                                                            <img src="{{ $hp->display_image_url }}" alt="" class="h-full w-full object-cover" loading="lazy">

                                                        @else

                                                            <span class="flex h-full w-full items-center justify-center text-2xl" aria-hidden="true">🛒</span>

                                                        @endif

                                                    </span>

                                                    <span class="line-clamp-2 px-2 py-2 text-[11px] font-semibold leading-tight text-slate-800">{{ $hp->getTranslation('name', app()->getLocale()) }}</span>

                                                </a>

                                            @endforeach

                                        </div>

                                    </div>

                                @endif

                            </div>

                        </div>

                    </article>

                @endforeach

            </div>



            @if ($slideCount > 1)

                <div class="mt-4 flex items-center justify-center gap-3">

                    <button

                        type="button"

                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:bg-surface dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200"

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

                                class="h-2.5 rounded-full transition-all"

                                :class="active === {{ $loop->index }} ? 'w-8 bg-brand' : 'w-2.5 bg-slate-300 dark:bg-slate-600'"

                                @click="go({{ $loop->index }})"

                                :aria-selected="(active === {{ $loop->index }}).toString()"

                                aria-label="{{ data_get($banner->title, app()->getLocale()) ?: data_get($banner->title, 'en', __('Banner')) }} {{ $loop->iteration }}"

                            ></button>

                        @endforeach

                    </div>

                    <button

                        type="button"

                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:bg-surface dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200"

                        @click="next()"

                        aria-label="{{ __('Next banner') }}"

                    >

                        <span aria-hidden="true">›</span>

                    </button>

                </div>

            @endif

        </div>

    </section>

@endif

