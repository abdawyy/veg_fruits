<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\HomeBanner;
use App\Models\ProduceBox;
use App\Models\ProduceBoxItem;
use App\Models\Product;
use App\Support\ProduceBoxSeoCopy;
use App\Support\StoreCart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProduceBoxSeoAndBannerTest extends TestCase
{
    use RefreshDatabase;

    public function test_weekly_box_page_publishes_its_seo_description(): void
    {
        $copy = ProduceBoxSeoCopy::forSlug('weekly-essentials');
        $this->assertNotNull($copy);
        $this->assertLessThanOrEqual(160, mb_strlen($copy['meta_description']['en']));
        $this->assertLessThanOrEqual(160, mb_strlen($copy['meta_description']['ar']));

        $box = ProduceBox::query()->create([
            'name' => ['en' => 'Weekly essentials box', 'ar' => 'صندوق الأسبوع الأساسي'],
            'slug' => ['en' => 'weekly-essentials', 'ar' => 'weekly-essentials'],
            'description' => $copy['description'],
            'meta_title' => $copy['meta_title'],
            'meta_description' => $copy['meta_description'],
            'price' => 199,
            'is_active' => true,
        ]);

        $this->get(route('store.boxes.show', $box))
            ->assertOk()
            ->assertSee($copy['meta_title']['en'].' — AL-DAWY', false)
            ->assertSee($copy['meta_description']['en'], false)
            ->assertSee($copy['description']['en'], false);

        $this->get(route('store.boxes.show', $box).'?locale=ar')
            ->assertOk()
            ->assertSee($copy['meta_description']['ar'], false)
            ->assertSee($copy['description']['ar'], false);
    }

    public function test_home_banner_outer_lists_active_produce_boxes(): void
    {
        HomeBanner::query()->create([
            'sort_order' => 0,
            'is_active' => true,
            'title' => ['en' => 'Mango season', 'ar' => 'موسم المانجو'],
            'subtitle' => ['en' => 'Sweet fruit', 'ar' => 'فاكهة حلوة'],
            'badge_text' => ['en' => 'Hot now', 'ar' => 'الأكثر طلباً'],
            'cta_label' => ['en' => 'Shop', 'ar' => 'تسوق'],
            'cta_url' => '/shop',
        ]);

        $copy = ProduceBoxSeoCopy::forSlug('weekly-essentials');
        $box = ProduceBox::query()->create([
            'name' => ['en' => 'Weekly essentials box', 'ar' => 'صندوق الأسبوع الأساسي'],
            'slug' => ['en' => 'weekly-essentials', 'ar' => 'weekly-essentials'],
            'description' => $copy['description'],
            'meta_description' => $copy['meta_description'],
            'price' => 199,
            'is_active' => true,
        ]);
        ProduceBox::query()->create([
            'name' => ['en' => 'Hidden pantry box', 'ar' => 'صندوق مخفي'],
            'slug' => ['en' => 'hidden-pantry', 'ar' => 'hidden-pantry'],
            'price' => 50,
            'is_active' => false,
        ]);

        $html = $this->get(route('store.home'))
            ->assertOk()
            ->assertSee('data-banner-outer-boxes', false)
            ->assertSee('Mango season', false)
            ->assertSee(route('store.boxes.show', $box), false)
            ->assertSee('Weekly essentials box', false)
            ->assertSee('199.00', false)
            ->assertDontSee('Hidden pantry box', false)
            ->getContent();

        $slideEnd = strpos($html, 'home-banner__viewport');
        $outer = strpos($html, 'data-banner-outer-boxes');
        $this->assertNotFalse($slideEnd);
        $this->assertNotFalse($outer);
        $this->assertGreaterThan($slideEnd, $outer);
        $this->assertStringContainsString('data-box-carousel', $html);
        $this->assertStringContainsString('name="quantity"', $html);
        $this->assertStringContainsString('type="number"', $html);
    }

    public function test_home_carousel_hides_the_quantity_input_when_the_box_turns_it_off(): void
    {
        $shown = $this->box('Shown box', true);
        $plain = $this->box('Plain box', false);

        $html = $this->get(route('store.home'))->assertOk()->getContent();

        $this->assertSame(2, substr_count($html, 'data-box-card'));
        $this->assertStringContainsString('id="box-qty-'.$shown->id.'"', $html);
        $this->assertStringNotContainsString('id="box-qty-'.$plain->id.'"', $html);
        $this->assertStringContainsString('Shown box', $html);
        $this->assertStringContainsString('Plain box', $html);
    }

    public function test_carousel_quantity_is_added_only_when_the_input_is_enabled(): void
    {
        $shown = $this->box('Shown box', true, '40.0000');
        $plain = $this->box('Plain box', false, '15.0000');

        $this->post(route('store.boxes.cart', $shown), ['quantity' => 3])
            ->assertRedirect(route('store.cart'));
        $this->post(route('store.boxes.cart', $plain), ['quantity' => 4])
            ->assertRedirect(route('store.cart'));

        $rows = StoreCart::resolved()->keyBy(fn (array $row): int => $row['produce_box']->id);
        $this->assertSame('3', $rows[$shown->id]['quantity']);
        $this->assertSame('1', $rows[$plain->id]['quantity']);
        $this->assertSame(0, bccomp('120.0000', $rows[$shown->id]['line_subtotal'], 4));
    }

    private function box(string $name, bool $showInput, string $price = '25.0000'): ProduceBox
    {
        $category = Category::query()->first() ?? Category::query()->create([
            'name' => ['en' => 'Produce', 'ar' => 'خضار'],
            'slug' => ['en' => 'produce-box-home', 'ar' => 'produce-box-home'],
            'is_active' => true,
        ]);
        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => ['en' => $name.' item', 'ar' => $name],
            'slug' => ['en' => 'item-'.uniqid(), 'ar' => 'item-'.uniqid()],
            'sku' => 'BOX-'.uniqid(),
            'price_per_kg' => 10,
            'is_active' => true,
        ]);
        $box = ProduceBox::query()->create([
            'name' => ['en' => $name, 'ar' => $name],
            'slug' => ['en' => str($name)->slug().'-'.uniqid(), 'ar' => str($name)->slug().'-'.uniqid()],
            'price' => $price,
            'is_active' => true,
            'show_card_input' => $showInput,
        ]);
        ProduceBoxItem::query()->create([
            'produce_box_id' => $box->id,
            'product_id' => $product->id,
            'quantity' => '1.0000',
            'unit' => 'kg',
        ]);

        return $box;
    }
}
