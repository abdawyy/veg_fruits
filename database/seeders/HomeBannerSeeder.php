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

        HomeBanner::query()->updateOrCreate(
            ['sort_order' => 1],
            [
                'is_active' => true,
                'starts_at' => null,
                'ends_at' => null,
                'title' => [
                    'en' => 'Farm-fresh vegetables',
                    'ar' => 'خضروات طازجة من المزرعة',
                ],
                'subtitle' => [
                    'en' => 'Daily greens, tomatoes, and seasonal picks — washed and ready for your kitchen.',
                    'ar' => 'خضروات يومية وطماطم وموسمية — مغسولة وجاهزة لمطبخك.',
                ],
                'badge_text' => [
                    'en' => 'New week',
                    'ar' => 'أسبوع جديد',
                ],
                'cta_label' => [
                    'en' => 'Browse vegetables',
                    'ar' => 'تصفح الخضروات',
                ],
                'cta_url' => '/vegetables',
                'image_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=1200&q=85',
                'gradient_from' => '#16a34a',
                'gradient_mid' => '#84cc16',
                'gradient_to' => '#22c55e',
                'hot_product_skus' => 'VEG-153,VEG-101',
            ],
        );
    }
}
