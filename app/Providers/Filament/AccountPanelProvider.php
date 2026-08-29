<?php

namespace App\Providers\Filament;

use App\Filament\Account\Pages\CustomerDashboard;
use App\Http\Middleware\EnsureEmailIsVerified;
use App\Http\Middleware\FilamentAuthenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AccountPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('account')
            ->path('my')
            ->brandName('AL-DAWY')
            ->brandLogo(new HtmlString(
                '<span class="flex items-center gap-2 leading-none">'
                .'<span class="text-xl" aria-hidden="true">🥬</span>'
                .'<span class="font-extrabold tracking-tight">AL-DAWY</span>'
                .'</span>'
            ))
            ->brandLogoHeight('2.25rem')
            ->font(
                'Instrument Sans',
                'https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|noto-sans-arabic:400,500,600,700&display=swap',
            )
            ->login(null)
            ->colors([
                'primary' => Color::hex('#16a34a'),
                'gray' => Color::Slate,
                'success' => Color::hex('#10b981'),
                'warning' => Color::hex('#f97316'),
            ])
            ->maxContentWidth(Width::SevenExtraLarge)
            ->sidebarCollapsibleOnDesktop()
            ->spa(false)
            ->discoverResources(in: app_path('Filament/Account/Resources'), for: 'App\Filament\Account\Resources')
            ->pages([
                CustomerDashboard::class,
            ])
            ->widgets([])
            ->profile()
            ->navigationItems([
                NavigationItem::make(__('aldawy.account_back_store'))
                    ->url(fn (): string => route('store.home'))
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->sort(-100),
            ])
            ->userMenuItems([
                'storefront' => MenuItem::make()
                    ->label(__('aldawy.account_tile_shop'))
                    ->url(fn (): string => route('store.shop'))
                    ->icon(Heroicon::OutlinedShoppingBag)
                    ->sort(5),
                'cart' => MenuItem::make()
                    ->label(__('aldawy.account_tile_cart'))
                    ->url(fn (): string => route('store.cart'))
                    ->icon(Heroicon::OutlinedShoppingCart)
                    ->sort(6),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware(array_values(array_filter([
                FilamentAuthenticate::class,
                config('aldawy.require_email_verification')
                    ? EnsureEmailIsVerified::class
                    : null,
            ])));
    }
}
