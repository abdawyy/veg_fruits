<?php

namespace App\Providers;

use App\Contracts\Payments\PaymentGatewayInterface;
use App\Contracts\Sms\SmsSenderInterface;
use App\Events\OrderCreated;
use App\Listeners\OnOrderCreatedGenerateInvoiceAndNotify;
use App\Payments\CashOnDeliveryGateway;
use App\Sms\LogSmsSender;
use App\Support\Cms;
use App\Support\StoreCart;
use App\Support\StoreSeo;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PaymentGatewayInterface::class, CashOnDeliveryGateway::class);
        $this->app->bind(SmsSenderInterface::class, LogSmsSender::class);
    }

    public function boot(): void
    {
        Event::listen(OrderCreated::class, OnOrderCreatedGenerateInvoiceAndNotify::class);

        $storefrontComposer = function (\Illuminate\View\View $view): void {
            $view->with('cartLineCount', StoreCart::lineCount());

            $route = request()->route()?->getName();
            $meta = StoreSeo::pageMeta($route);
            $pageTitle = $meta['title'] !== '' && $meta['title'] !== null
                ? (string) $meta['title']
                : match ($route) {
                    'store.home' => __('aldawy.hero_title'),
                    'store.shop' => __('aldawy.shop_title'),
                    'store.fruits' => __('aldawy.fruits_title'),
                    'store.vegetables' => __('aldawy.vegetables_title'),
                    'store.services' => __('aldawy.services_title'),
                    'store.cart' => __('aldawy.cart_title'),
                    'store.checkout.thanks' => __('aldawy.checkout_thanks_title'),
                    'store.product' => request()->route('product')?->getTranslation('name', app()->getLocale()) ?? config('app.name'),
                    default => config('app.name'),
                };
            $pageDesc = $meta['description'] !== '' && $meta['description'] !== null
                ? (string) $meta['description']
                : match ($route) {
                    'store.home' => __('aldawy.hero_sub'),
                    'store.shop' => __('aldawy.shop_subtitle'),
                    'store.fruits' => __('aldawy.fruits_subtitle'),
                    'store.vegetables' => __('aldawy.vegetables_subtitle'),
                    'store.services' => __('aldawy.services_intro'),
                    'store.cart' => __('aldawy.cart_sub'),
                    'store.checkout.thanks' => __('aldawy.checkout_thanks_meta'),
                    default => '',
                };
            $og = \App\Models\SeoSetting::current()->og_image_url;

            $view->with('htmlTitle', trim($pageTitle).' — AL-DAWY');
            $view->with('pageMetaDescription', $pageDesc);
            $view->with('ogImageUrl', $og);
            $view->with('cms', fn (string $key, string $fallback = ''): string => Cms::text($key, $fallback));
        };

        View::composer([
            'layouts.store',
            'store.home',
            'store.shop',
            'store.category-shop',
            'store.cart',
            'store.checkout-thanks',
            'store.product',
            'store.services',
        ], $storefrontComposer);
    }
}
