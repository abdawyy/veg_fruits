<?php

namespace Database\Seeders;

use App\Models\HomeBanner;
use Illuminate\Database\Seeder;

class HomeBannerSeeder extends Seeder
{
    public function run(): void
    {
        HomeBanner::query()->updateOrCreate(
            ['sort_order' => 0],
            [
                'is_active' => true,
                'starts_at' => null,
                'ends_at' => null,
                'title' => [
                    'en' => 'This season: Egyptian mango',
                    'ar' => 'موسم المانجو المصري',
                ],
                'subtitle' => [
                    'en' => 'Sweet, sun-ripened & perfect for Ramadan tables — pair with dates & fresh juice oranges.',
                    'ar' => 'حلوة وناضجة في الشمس — مثالية لسفرة رمضان مع التمر والبرتقال الطازج.',
                ],
                'badge_text' => [
                    'en' => 'Hot now',
                    'ar' => 'الأكثر طلباً',
                ],
                'cta_label' => [
                    'en' => 'Shop mango & citrus',
                    'ar' => 'تسوق المانجو والحمضيات',
                ],
                'cta_url' => '/shop?q=مانجو',
                'image_url' => 'https://images.unsplash.com/photo-1605027990121-c42e40f43593?auto=format&fit=crop&w=1200&q=85',
                'gradient_from' => '#f97316',
                'gradient_mid' => '#eab308',
                'gradient_to' => '#22c55e',
                'hot_product_skus' => 'FRU-022,FRU-024,FRU-010,VEG-153',
            ],
        );
    }
}
