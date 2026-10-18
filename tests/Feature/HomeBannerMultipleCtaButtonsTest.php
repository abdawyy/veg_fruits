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

    public function test_one_button_can_target_many_products_from_different_categories(): void
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
            'cta_label' => ['en' => 'Shop Citrus', 'ar' => 'تسوق الحمضيات'],
            'cta_product_ids' => [$tangerine->id, $tomato->id],
        ]));

        $banner->refresh();

        $this->assertSame(['en' => 'Shop Citrus', 'ar' => 'تسوق الحمضيات'], $banner->cta_label);
        $this->assertSame([$tangerine->id, $tomato->id], $banner->resolvedCtaProductIds());
        $this->assertSame(
            route('store.shop', ['products' => $tangerine->id.','.$tomato->id], absolute: false),
            $banner->cta_url,
        );
        $this->assertCount(1, $banner->resolvedCtaButtons());
        $this->assertCount(2, $banner->ctaProducts());

        $html = view('store.partials.banners', ['banners' => collect([$banner])])->render();

        $this->assertStringContainsString('Shop Citrus', $html);
        $this->assertEquals(1, substr_count($html, 'Shop Citrus'));
        $this->assertStringContainsString('products='.$tangerine->id.'%2C'.$tomato->id, $html);
        $this->assertStringContainsString(route('store.product', $tangerine), $html);
        $this->assertStringContainsString(route('store.product', $tomato), $html);

        $this->get($banner->cta_url)
            ->assertOk()
            ->assertSee('Tangerine')
            ->assertSee('Tomato');
    }

    public function test_single_product_button_links_to_the_product_page(): void
    {
        $category = Category::query()->create([
            'name' => ['en' => 'Fruits', 'ar' => 'فواكه'],
            'slug' => ['en' => 'fruits', 'ar' => 'fruits'],
            'is_active' => true,
        ]);
        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => ['en' => 'Tangerine', 'ar' => 'يوسفي'],
            'slug' => ['en' => 'tangerine', 'ar' => 'tangerine'],
            'sku' => 'FRU-037',
            'price_per_kg' => 40,
            'is_active' => true,
        ]);

        $banner = HomeBanner::query()->create(HomeBannerAttributes::fromFormRow([
            'title' => ['en' => 'Citrus', 'ar' => 'حمضيات'],
            'cta_label' => ['en' => 'Buy now', 'ar' => 'اشتري'],
            'cta_product_ids' => [$product->id],
        ]));

        $this->assertSame(route('store.product', $product, absolute: false), $banner->cta_url);
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

        $button = $banner->resolvedCtaButton();

        $this->assertNotNull($button);
        $this->assertSame('Shop now', $button['label']['en']);
        $this->assertSame('/shop', $button['url']);
    }
}
