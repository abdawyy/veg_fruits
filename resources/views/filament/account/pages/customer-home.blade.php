@php
    use App\Enums\OrderStatus;
    use App\Models\Order;

    $user = auth()->user();
    $currency = config('aldawy.currency', 'EGP');
    $ordersQuery = Order::query()->where('user_id', $user?->id);
    $orderCount = (clone $ordersQuery)->count();
    $pendingCount = (clone $ordersQuery)->where('status', OrderStatus::Pending->value)->count();
    $recentOrders = (clone $ordersQuery)->latest()->limit(5)->get();
    $isRtl = app()->getLocale() === 'ar';

    $navLinks = [
        [
            'href' => route('filament.account.resources.orders.index'),
            'wire' => true,
            'icon' => '📦',
            'label' => __('aldawy.account_tile_orders'),
            'desc' => trans_choice('aldawy.account_orders_count', $orderCount, ['count' => $orderCount]),
            'highlight' => true,
        ],
        [
            'href' => route('filament.account.auth.profile'),
            'wire' => true,
            'icon' => '👤',
            'label' => __('aldawy.account_tile_profile'),
            'desc' => __('aldawy.account_tile_profile_desc'),
        ],
        [
            'href' => route('store.shop'),
            'wire' => false,
            'icon' => '🛍️',
            'label' => __('aldawy.account_tile_shop'),
            'desc' => __('aldawy.account_tile_shop_desc'),
        ],
        [
            'href' => route('store.cart'),
            'wire' => false,
            'icon' => '🛒',
            'label' => __('aldawy.account_tile_cart'),
            'desc' => __('aldawy.trust_cod'),
        ],
        [
            'href' => route('store.services'),
            'wire' => false,
            'icon' => '✨',
            'label' => __('aldawy.account_tile_services'),
            'desc' => __('aldawy.trust_prep'),
        ],
        [
            'href' => route('store.boxes'),
            'wire' => false,
            'icon' => '🥬',
            'label' => __('aldawy.nav_boxes'),
            'desc' => __('aldawy.boxes_sub'),
        ],
        [
            'href' => route('store.home'),
            'wire' => false,
            'icon' => '🏠',
            'label' => __('aldawy.account_back_store_label'),
            'desc' => __('aldawy.account_back_store_desc'),
            'footer' => true,
        ],
    ];
@endphp

<style>
    .fi-account-home {
        max-width: 48rem;
        margin-inline: auto;
        width: 100%;
    }

    .fi-account-home__card {
        overflow: hidden;
        border-radius: 1rem;
        border: 1px solid rgb(226 232 240);
        background: #fff;
        box-shadow: 0 1px 2px rgb(15 23 42 / 0.05);
    }

    .dark .fi-account-home__card {
        border-color: rgb(255 255 255 / 0.1);
        background: rgb(17 24 39);
    }

    .fi-account-home__hero {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgb(243 244 246);
        background: linear-gradient(135deg, rgb(240 253 244), #fff 55%);
    }

    .dark .fi-account-home__hero {
        border-bottom-color: rgb(255 255 255 / 0.1);
        background: linear-gradient(135deg, rgb(5 46 22 / 0.35), rgb(17 24 39) 55%);
    }

    .fi-account-home__kicker {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: rgb(21 128 61);
    }

    .dark .fi-account-home__kicker { color: rgb(134 239 172); }

    .fi-account-home__name {
        margin-top: 0.25rem;
        font-size: 1.5rem;
        font-weight: 800;
        color: rgb(3 7 18);
    }

    .dark .fi-account-home__name { color: #fff; }

    .fi-account-home__meta {
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: rgb(75 85 99);
    }

    .dark .fi-account-home__meta { color: rgb(209 213 219); }

    .fi-account-home__pills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .fi-account-home__pill {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        background: rgb(255 255 255 / 0.9);
        border: 1px solid rgb(187 247 208);
        color: rgb(22 101 52);
    }

    .dark .fi-account-home__pill {
        background: rgb(3 7 18 / 0.6);
        border-color: rgb(34 197 94 / 0.3);
        color: rgb(187 247 208);
    }

    .fi-account-home__nav { border-top: 1px solid rgb(243 244 246); }
    .dark .fi-account-home__nav { border-top-color: rgb(255 255 255 / 0.1); }

    .fi-account-home__row {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.5rem;
        text-decoration: none;
        color: inherit;
        cursor: pointer;
        border-bottom: 1px solid rgb(243 244 246);
        transition: background 0.15s ease;
    }

    .dark .fi-account-home__row { border-bottom-color: rgb(255 255 255 / 0.1); }

    .fi-account-home__row:hover { background: rgb(249 250 251); }
    .dark .fi-account-home__row:hover { background: rgb(31 41 55 / 0.6); }

    .fi-account-home__row--highlight {
        background: rgb(240 253 244 / 0.5);
    }

    .dark .fi-account-home__row--highlight { background: rgb(5 46 22 / 0.2); }

    .fi-account-home__row--footer {
        background: rgb(249 250 251 / 0.7);
        border-top: 1px solid rgb(226 232 240);
    }

    .dark .fi-account-home__row--footer {
        background: rgb(3 7 18 / 0.4);
        border-top-color: rgb(255 255 255 / 0.1);
    }

    .fi-account-home__icon {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 0.75rem;
        font-size: 1.25rem;
        background: rgb(243 244 246);
        border: 1px solid rgb(229 231 235);
    }

    .dark .fi-account-home__icon {
        background: rgb(31 41 55);
        border-color: rgb(255 255 255 / 0.1);
    }

    .fi-account-home__icon--primary {
        background: rgb(220 252 231);
        border-color: rgb(187 247 208);
    }

    .dark .fi-account-home__icon--primary {
        background: rgb(20 83 45 / 0.5);
        border-color: rgb(34 197 94 / 0.3);
    }

    .fi-account-home__body { flex: 1; min-width: 0; }

    .fi-account-home__label {
        display: block;
        font-weight: 600;
        color: rgb(3 7 18);
    }

    .dark .fi-account-home__label { color: #fff; }

    .fi-account-home__desc {
        display: block;
        margin-top: 0.125rem;
        font-size: 0.75rem;
        color: rgb(107 114 128);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .dark .fi-account-home__desc { color: rgb(156 163 175); }

    .fi-account-home__orders {
        border-top: 1px solid rgb(226 232 240);
        background: rgb(249 250 251 / 0.5);
    }

    .dark .fi-account-home__orders {
        border-top-color: rgb(255 255 255 / 0.1);
        background: rgb(3 7 18 / 0.3);
    }

    .fi-account-home__orders-head {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.875rem 1.5rem;
        border-bottom: 1px solid rgb(243 244 246);
    }

    .dark .fi-account-home__orders-head { border-bottom-color: rgb(255 255 255 / 0.1); }

    .fi-account-home__section-title {
        font-size: 0.875rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: rgb(107 114 128);
    }

    .fi-account-home__link {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: rgb(22 163 74);
        text-decoration: none;
        cursor: pointer;
    }

    .fi-account-home__cta {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 700;
        color: #fff;
        background: rgb(22 163 74);
    }

    .fi-account-home__badge {
        border-radius: 9999px;
        padding: 0.125rem 0.625rem;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .fi-account-home__badge--warning {
        background: rgb(254 243 199);
        color: rgb(146 64 14);
    }

    .fi-account-home__badge--primary {
        background: rgb(220 252 231);
        color: rgb(22 101 52);
    }

    .fi-account-home__badge--success {
        background: rgb(209 250 229);
        color: rgb(6 95 70);
    }

    .fi-account-home__badge--muted {
        background: rgb(243 244 246);
        color: rgb(75 85 99);
    }

    .fi-account-home__price {
        font-size: 0.875rem;
        font-weight: 800;
        color: rgb(21 128 61);
    }

    .fi-account-chevron {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: rgb(156 163 175);
    }

    .fi-account-chevron svg {
        display: block;
        flex-shrink: 0;
    }

    .fi-account-chevron--lg svg { width: 1.25rem; height: 1.25rem; }
    .fi-account-chevron--md svg { width: 1rem; height: 1rem; }
    .fi-account-chevron--sm svg { width: 0.875rem; height: 0.875rem; }

    .fi-account-chevron--animated svg { transition: transform 0.15s ease; }

    .fi-account-home .group:hover .fi-account-chevron--animated svg {
        transform: translateX(2px);
    }

    .fi-account-home[dir='rtl'] .fi-account-chevron svg {
        transform: rotate(180deg);
    }

    .fi-account-home[dir='rtl'] .group:hover .fi-account-chevron--animated svg {
        transform: rotate(180deg) translateX(-2px);
    }

    .fi-account-chevron--white { color: #fff; }
</style>

<div class="fi-account-home" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
    <div class="fi-account-home__card">
        <div class="fi-account-home__hero">
            <p class="fi-account-home__kicker">{{ __('aldawy.account_hello') }}</p>
            <h2 class="fi-account-home__name">{{ $user?->name ?: $user?->email }}</h2>
            @if ($user?->name && $user?->email)
                <p class="fi-account-home__meta">{{ $user->email }}</p>
            @endif
            @if ($user?->phone_number)
                <p class="fi-account-home__meta">{{ $user->phone_number }}</p>
            @endif

            <div class="fi-account-home__pills">
                <span class="fi-account-home__pill">
                    📦 {{ trans_choice('aldawy.account_orders_count', $orderCount, ['count' => $orderCount]) }}
                </span>
                @if ($pendingCount > 0)
                    <span class="fi-account-home__pill">
                        ⏳ {{ trans_choice('aldawy.account_pending_orders', $pendingCount, ['count' => $pendingCount]) }}
                    </span>
                @endif
                <span class="fi-account-home__pill">💵 {{ __('aldawy.trust_cod') }}</span>
            </div>
        </div>

        <nav class="fi-account-home__nav" aria-label="{{ __('aldawy.account_quick_actions') }}">
            @foreach ($navLinks as $link)
                <a
                    href="{{ $link['href'] }}"
                    @if ($link['wire'] ?? false) wire:navigate @endif
                    @class([
                        'fi-account-home__row group',
                        'fi-account-home__row--highlight' => ($link['highlight'] ?? false),
                        'fi-account-home__row--footer' => ($link['footer'] ?? false),
                    ])
                >
                    <span @class([
                        'fi-account-home__icon',
                        'fi-account-home__icon--primary' => ($link['highlight'] ?? false),
                    ]) aria-hidden="true">{{ $link['icon'] }}</span>

                    <span class="fi-account-home__body">
                        <span class="fi-account-home__label">{{ $link['label'] }}</span>
                        <span class="fi-account-home__desc">{{ $link['desc'] }}</span>
                    </span>

                    @include('filament.account.partials.chevron', ['size' => 'lg'])
                </a>
            @endforeach
        </nav>

        <div class="fi-account-home__orders">
            <div class="fi-account-home__orders-head">
                <h3 class="fi-account-home__section-title">{{ __('aldawy.account_recent_orders') }}</h3>
                @if ($orderCount > 0)
                    <a href="{{ route('filament.account.resources.orders.index') }}" wire:navigate class="fi-account-home__link group">
                        {{ __('aldawy.account_view_all_orders') }}
                        @include('filament.account.partials.chevron', ['size' => 'md', 'animated' => false])
                    </a>
                @endif
            </div>

            @if ($recentOrders->isEmpty())
                <a href="{{ route('store.shop') }}" class="fi-account-home__row group">
                    <span class="fi-account-home__icon" aria-hidden="true">🛍️</span>
                    <span class="fi-account-home__body">
                        <span class="fi-account-home__label">{{ __('aldawy.account_no_orders') }}</span>
                        <span class="fi-account-home__desc">{{ __('aldawy.account_no_orders_sub') }}</span>
                    </span>
                    <span class="fi-account-home__cta">
                        {{ __('aldawy.cta_shop') }}
                        @include('filament.account.partials.chevron', ['size' => 'sm', 'animated' => false, 'wrapClass' => 'fi-account-chevron fi-account-chevron--sm fi-account-chevron--white'])
                    </span>
                </a>
            @else
                @foreach ($recentOrders as $order)
                    @php
                        $status = $order->status instanceof OrderStatus ? $order->status : OrderStatus::tryFrom((string) $order->status);
                    @endphp
                    <a href="{{ route('filament.account.resources.orders.view', $order) }}" wire:navigate class="fi-account-home__row group">
                        <span class="fi-account-home__icon" aria-hidden="true">📄</span>
                        <span class="fi-account-home__body">
                            <span class="fi-account-home__label" style="font-family: ui-monospace, monospace; font-size: 0.875rem;">{{ $order->reference }}</span>
                            <span class="fi-account-home__desc">{{ $order->created_at?->translatedFormat('M j, Y · H:i') }}</span>
                        </span>
                        <span style="display:flex; align-items:center; gap:0.75rem; flex-shrink:0;">
                            @if ($status)
                                <span @class([
                                    'fi-account-home__badge',
                                    'fi-account-home__badge--warning' => in_array($status, [OrderStatus::Pending, OrderStatus::Processing], true),
                                    'fi-account-home__badge--primary' => in_array($status, [OrderStatus::Confirmed, OrderStatus::Shipped], true),
                                    'fi-account-home__badge--success' => $status === OrderStatus::Delivered,
                                    'fi-account-home__badge--muted' => $status === OrderStatus::Cancelled,
                                ])>{{ $status->label() }}</span>
                            @endif
                            <span class="fi-account-home__price">{{ number_format((float) $order->total, 2) }} {{ $currency }}</span>
                            @include('filament.account.partials.chevron', ['size' => 'md'])
                        </span>
                    </a>
                @endforeach
            @endif
        </div>
    </div>
</div>
