<footer class="aldawy-footer mt-auto">
    {{-- CTA band --}}
    <div class="aldawy-footer-cta">
        <div class="mx-auto flex max-w-6xl flex-col items-center gap-6 px-4 py-12 text-center sm:flex-row sm:text-start">
            <div class="flex-1">
                <h2 class="text-2xl font-extrabold text-white sm:text-3xl">{{ __('aldawy.footer_cta_title') }}</h2>
                <p class="mt-2 max-w-xl text-sm text-white/80 sm:text-base">{{ __('aldawy.footer_cta_sub') }}</p>
            </div>
            <div class="flex shrink-0 flex-wrap justify-center gap-3">
                <a href="{{ route('store.shop') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 text-sm font-bold text-brand shadow-lg transition hover:bg-brand-light hover:text-white">
                    {{ __('aldawy.cta_shop') }}
                    <span aria-hidden="true">→</span>
                </a>
                <a href="{{ route('store.cart') }}" class="inline-flex items-center gap-2 rounded-xl border-2 border-white/30 px-6 py-3 text-sm font-bold text-white transition hover:bg-white/10">
                    {{ __('aldawy.nav_cart') }}
                </a>
            </div>
        </div>
    </div>

    <div class="aldawy-footer-main">
        <div class="mx-auto max-w-6xl px-4 py-14">
            <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-12 lg:gap-8">
                {{-- Brand --}}
                <div class="lg:col-span-4">
                    <div class="flex items-center gap-3">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-brand to-brand-dark text-2xl shadow-lg">🥬</span>
                        <div>
                            <span class="block text-xl font-extrabold text-white">AL-DAWY</span>
                            <span class="text-xs font-semibold text-brand-light">{{ __('aldawy.badge_fresh') }}</span>
                        </div>
                    </div>
                    <p class="mt-4 text-sm leading-relaxed text-slate-400">{{ __('aldawy.footer_tagline') }}</p>
                    <div class="mt-5 flex flex-wrap gap-2">
                        <span class="aldawy-footer-badge">💵 {{ __('aldawy.trust_cod') }}</span>
                        <span class="aldawy-footer-badge">🌐 {{ __('aldawy.trust_bilingual') }}</span>
                        <span class="aldawy-footer-badge">✨ {{ __('aldawy.trust_prep') }}</span>
                    </div>
                </div>

                {{-- Shop links --}}
                <div class="lg:col-span-2">
                    <h3 class="aldawy-footer-heading">{{ __('aldawy.footer_shop_heading') }}</h3>
                    <ul class="mt-4 space-y-2.5 text-sm">
                        <li><a href="{{ route('store.shop') }}" class="aldawy-footer-link">{{ __('aldawy.nav_shop') }}</a></li>
                        <li><a href="{{ route('store.fruits') }}" class="aldawy-footer-link">{{ __('aldawy.nav_fruits') }}</a></li>
                        <li><a href="{{ route('store.vegetables') }}" class="aldawy-footer-link">{{ __('aldawy.nav_vegetables') }}</a></li>
                        <li><a href="{{ route('store.boxes') }}" class="aldawy-footer-link">{{ __('aldawy.nav_boxes') }}</a></li>
                    </ul>
                </div>

                {{-- Support --}}
                <div class="lg:col-span-3">
                    <h3 class="aldawy-footer-heading">{{ __('aldawy.footer_support_heading') }}</h3>
                    <ul class="mt-4 space-y-2.5 text-sm">
                        <li><a href="{{ route('store.services') }}" class="aldawy-footer-link">{{ __('aldawy.nav_services') }}</a></li>
                        <li><a href="{{ route('store.cart') }}" class="aldawy-footer-link">{{ __('aldawy.nav_cart') }}</a></li>
                    </ul>
                    <p class="mt-4 text-xs leading-relaxed text-slate-500">{{ __('aldawy.footer_cod_note') }}</p>
                </div>

                {{-- Account --}}
                <div class="lg:col-span-3">
                    <h3 class="aldawy-footer-heading">{{ __('aldawy.footer_account_heading') }}</h3>
                    <ul class="mt-4 space-y-2.5 text-sm">
                        @auth
                            <li><a href="{{ url('/my') }}" class="aldawy-footer-link">{{ __('aldawy.nav_account') }}</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="post" class="inline">
                                    @csrf
                                    <button type="submit" class="aldawy-footer-link">{{ __('aldawy.nav_logout') }}</button>
                                </form>
                            </li>
                        @else
                            <li><a href="{{ route('login') }}" class="aldawy-footer-link">{{ __('aldawy.nav_login') }}</a></li>
                            <li><a href="{{ route('register') }}" class="aldawy-footer-link">{{ __('aldawy.nav_register') }}</a></li>
                        @endauth
                    </ul>
                    <p class="mt-4 text-xs text-slate-500">{{ __('aldawy.trust_products', ['count' => $activeProductCount ?? 115]) }}</p>
                </div>
            </div>

            <div class="aldawy-footer-bottom">
                <p>{{ __('aldawy.footer_note') }}</p>
                <p class="mt-1">{{ __('aldawy.photo_credit') }}</p>
                <p class="mt-4 font-semibold text-slate-600">&copy; {{ date('Y') }} AL-DAWY</p>
            </div>
        </div>
    </div>
</footer>
