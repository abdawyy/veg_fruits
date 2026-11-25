<?php

namespace Tests\Feature;

use App\Models\HomeBanner;
use App\Models\ProduceBox;
use App\Support\ProduceBoxSeoCopy;
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
    }
}
