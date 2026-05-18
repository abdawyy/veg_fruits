<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Product::query()->orderBy('id');
    }

    /**
     * @return list<string>
     */
    public function headings(): array
    {
        return [
            'id',
            'sku',
            'category_id',
            'name_en',
            'name_ar',
            'slug_en',
            'slug_ar',
            'description_en',
            'description_ar',
            'price_per_kg',
            'price_per_piece',
            'sell_by_piece',
            'is_active',
            'view_count',
            'image_url',
            'image_path',
        ];
    }

    /**
     * @param  Product  $product
     * @return list<mixed>
     */
    public function map($product): array
    {
        return [
            $product->id,
            $product->sku,
            $product->category_id,
            $product->getTranslation('name', 'en'),
            $product->getTranslation('name', 'ar'),
            $product->getTranslation('slug', 'en'),
            $product->getTranslation('slug', 'ar'),
            $product->getTranslation('description', 'en'),
            $product->getTranslation('description', 'ar'),
            $product->price_per_kg,
            $product->price_per_piece,
            $product->sell_by_piece ? 1 : 0,
            $product->is_active ? 1 : 0,
            $product->view_count,
            $product->image_url,
            $product->image_path,
        ];
    }
}
