<?php

namespace App\Providers;

use App\Contracts\Sms\SmsSenderInterface;
use App\Events\OrderCreated;
use App\Listeners\OnOrderCreatedGenerateInvoiceAndNotify;
use App\Models\Order;
use App\Models\SeoSetting;
use App\Observers\OrderObserver;
use App\Sms\HttpSmsSender;
use App\Sms\LogSmsSender;
use App\Support\Cms;
use App\Support\Pdf\PdfRenderer;
use App\Support\StoreCart;
use App\Support\StoreSeo;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PdfRenderer::class);

        $this->app->bind(SmsSenderInterface::class, function (): SmsSenderInterface {
            return match (config('aldawy.sms.driver')) {
                'http' => $this->app->make(HttpSmsSender::class),
                default => $this->app->make(LogSmsSender::class),
            };
        });
    }

    public function boot(): void
    {
        Order::observe(OrderObserver::class);

        Event::listen(OrderCreated::class, OnOrderCreatedGenerateInvoiceAndNotify::class);

        RateLimiter::for('cart', fn (Request $request) => Limit::perMinute(40)->by($request->ip()));
        RateLimiter::for('checkout', fn (Request $request) => Limit::perMinute(8)->by(
            $request->ip().'|'.($request->session()->getId() ?: 'guest')
        ));

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
                    'store.boxes' => __('aldawy.boxes_title'),
                    'store.boxes.show' => request()->route('produceBox')?->getTranslation('name', app()->getLocale()) ?? __('aldawy.boxes_title'),
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
                    'store.boxes' => __('aldawy.boxes_sub'),
                    'store.boxes.show' => __('aldawy.box_subscribe_sub'),
                    'store.cart' => __('aldawy.cart_sub'),
                    'store.checkout.thanks' => __('aldawy.checkout_thanks_meta'),
                    default => '',
                };
            $og = SeoSetting::current()->og_image_url;

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
            'store.boxes.index',
            'store.boxes.show',
        ], $storefrontComposer);
    }
}
