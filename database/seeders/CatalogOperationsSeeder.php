<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\PackagingType;
use App\Models\PreparationService;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CatalogOperationsSeeder extends Seeder
{
    public function run(): void
    {
        $preparations = [
            ['wash', ['en' => 'Wash & trim', 'ar' => 'غسل وتنظيف'], '5.0000', false],
            ['peel', ['en' => 'Peel', 'ar' => 'تقشير'], '3.0000', false],
            ['dice', ['en' => 'Dice / chop', 'ar' => 'تقطيع مكعبات'], '7.0000', false],
            ['slice', ['en' => 'Slice', 'ar' => 'شرائح'], '4.0000', false],
        ];

        foreach ($preparations as $i => [$code, $name, $amount, $pct]) {
            PreparationService::query()->updateOrCreate(
                ['code' => $code],
                [
                    'name' => $name,
                    'surcharge_amount' => $amount,
                    'surcharge_is_percent' => $pct,
                    'is_active' => true,
                    'sort_order' => $i,
                ],
            );
        }

        $packaging = [
            ['bag', ['en' => 'Plastic bag', 'ar' => 'كيس بلاستيك'], '0', false],
            ['tray', ['en' => 'Tray wrap', 'ar' => 'علبة مغلفة'], '5.0000', false],
            ['box', ['en' => 'Box', 'ar' => 'صندوق'], '12.0000', false],
        ];

        foreach ($packaging as $i => [$code, $name, $amount, $pct]) {
            PackagingType::query()->updateOrCreate(
                ['code' => $code],
                [
                    'name' => $name,
                    'surcharge_amount' => $amount,
                    'surcharge_is_percent' => $pct,
                    'is_active' => true,
                    'sort_order' => $i,
                ],
            );
        }

        $cities = [
            ['cairo', ['en' => 'Cairo', 'ar' => 'القاهرة'], '25.0000', 0],
            ['giza', ['en' => 'Giza', 'ar' => 'الجيزة'], '30.0000', 1],
            ['alex', ['en' => 'Alexandria', 'ar' => 'الإسكندرية'], '45.0000', 2],
            ['delta', ['en' => 'Delta cities', 'ar' => 'مدن الدلتا'], '35.0000', 3],
            ['upper', ['en' => 'Upper Egypt', 'ar' => 'صعيد مصر'], '55.0000', 4],
        ];

        foreach ($cities as [$code, $name, $fee, $order]) {
            City::query()->updateOrCreate(
                ['code' => $code],
                [
                    'name' => $name,
                    'shipping_fee' => $fee,
                    'is_active' => true,
                    'sort_order' => $order,
                ],
            );
        }

        $prepIds = PreparationService::query()->pluck('id');
        $packIds = PackagingType::query()->pluck('id');

        Product::query()->each(function (Product $product) use ($prepIds, $packIds): void {
            $product->preparationServices()->sync(
                $prepIds->mapWithKeys(fn (int $id): array => [$id => ['is_enabled' => true]])->all()
            );
            $product->packagingTypes()->sync(
                $packIds->mapWithKeys(fn (int $id): array => [$id => ['is_enabled' => true]])->all()
            );
        });
    }
}
