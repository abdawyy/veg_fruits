<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\HomeBanner;
use App\Models\Product;
use App\Support\HomeBannerAttributes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class HomeBannerMultipleCtaButtonsTest extends TestCase
{
    use RefreshDatabase;

    public function test_banner_stores_and_renders_multiple_product_buttons(): void
    {
        $fruit = Category::query()->create([
            'name' => ['en' => 'Fruits', 'ar' => 'فواكه'],
            'slug' => ['en' => 'fruits', 'ar' => 'fruits'],
            'is_active' => true,
        ]);
        $veg = Category::query()->create([
            'name' => ['en' => 'Vegetables', 'ar' => 'خضار'],
            'slug' => ['en' => 'vegetables', 'ar' => 'vegetables'],
            'is_active' => true,
        ]);

        $tangerine = Product::query()->create([
            'category_id' => $fruit->id,
            'name' => ['en' => 'Tangerine', 'ar' => 'يوسفي'],
            'slug' => ['en' => 'tangerine', 'ar' => 'tangerine'],
            'sku' => 'FRU-037',
            'price_per_kg' => 40,
            'is_active' => true,
        ]);
        $tomato = Product::query()->create([
            'category_id' => $veg->id,
            'name' => ['en' => 'Tomato', 'ar' => 'طماطم'],
            'slug' => ['en' => 'tomato', 'ar' => 'tomato'],
            'sku' => 'VEG-001',
            'price_per_kg' => 12,
            'is_active' => true,
        ]);

        $banner = HomeBanner::query()->create(HomeBannerAttributes::fromFormRow([
            'title' => ['en' => 'Fresh picks', 'ar' => 'اختيارات طازجة'],
            'subtitle' => ['en' => 'From every aisle', 'ar' => 'من كل قسم'],
            'cta_buttons' => [
                [
                    'label' => ['en' => 'Buy Tangerine', 'ar' => 'اشتري يوسفي'],
                    'product_id' => $tangerine->id,
                    'url' => route('store.product', $tangerine, absolute: false),
                ],
                [
                    'label' => ['en' => 'Buy Tomato', 'ar' => 'اشتري طماطم'],
                    'product_id' => $tomato->id,
                    'url' => route('store.product', $tomato, absolute: false),
                ],
            ],
        ]));

        $banner->refresh();

        $this->assertCount(2, $banner->resolvedCtaButtons());
        $this->assertSame('Buy Tangerine', $banner->cta_label['en']);
        $this->assertSame(route('store.product', $tangerine, absolute: false), $banner->cta_url);
        $this->assertSame($tangerine->id, $banner->cta_buttons[0]['product_id']);
        $this->assertSame($tomato->id, $banner->cta_buttons[1]['product_id']);

        $html = view('store.partials.banners', ['banners' => collect([$banner])])->render();

        $this->assertStringContainsString('Buy Tangerine', $html);
        $this->assertStringContainsString('Buy Tomato', $html);
        $this->assertStringContainsString(route('store.product', $tangerine), $html);
        $this->assertStringContainsString(route('store.product', $tomato), $html);
    }

    public function test_legacy_single_cta_still_resolves_as_one_button(): void
    {
        $banner = new HomeBanner([
            'title' => ['en' => 'Legacy', 'ar' => 'قديم'],
            'subtitle' => ['en' => '', 'ar' => ''],
            'cta_label' => ['en' => 'Shop now', 'ar' => 'تسوق الآن'],
            'cta_url' => '/shop',
            'cta_buttons' => null,
        ]);

        $buttons = $banner->resolvedCtaButtons();

        $this->assertCount(1, $buttons);
        $this->assertSame('Shop now', $buttons[0]['label']['en']);
        $this->assertSame('/shop', $buttons[0]['url']);
    }
}
