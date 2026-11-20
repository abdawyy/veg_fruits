<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProduceBox;
use App\Models\Product;
use Illuminate\Http\Response;

final class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = [
            route('store.home'),
            route('store.shop'),
            route('store.fruits'),
            route('store.vegetables'),
            route('store.boxes'),
            route('store.services'),
        ];

        Category::query()->where('is_active', true)->orderBy('id')->each(function (Category $category) use (&$urls): void {
            $slug = (string) $category->getTranslation('slug', 'en');
            $urls[] = match ($slug) {
                'fruits' => route('store.fruits'),
                'vegetables' => route('store.vegetables'),
                default => route('store.shop').'#cat-'.$category->id,
            };
        });

        Product::query()->where('is_active', true)->orderBy('id')->each(function (Product $product) use (&$urls): void {
            $urls[] = route('store.product', $product);
        });

        ProduceBox::query()->where('is_active', true)->orderBy('id')->each(function (ProduceBox $box) use (&$urls): void {
            $urls[] = route('store.boxes.show', $box);
        });

        $body = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach (array_unique($urls) as $url) {
            $body .= '<url><loc>'.e($url).'</loc></url>';
        }

        $body .= '</urlset>';

        return response($body, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
