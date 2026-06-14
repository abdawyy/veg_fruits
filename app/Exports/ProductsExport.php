<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\ProductView;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @param  list<int>|null  $productIds  When set, only these products are exported.
     */
    public function __construct(
        private readonly ?array $productIds = null,
    ) {}

    public function query()
    {
        $query = Product::query()->orderBy('id');

        if ($this->productIds !== null) {
            $query->whereIn('id', $this->productIds);
        }

        if (! Schema::hasTable('product_views')) {
            return $query;
        }

        return $query
            ->select('products.*')
            ->selectSub(
                ProductView::query()
                    ->selectRaw('count(distinct session_id)')
                    ->whereColumn('product_views.product_id', 'products.id'),
                'unique_visitors_count',
            )
            ->selectSub(
                ProductView::query()
                    ->selectRaw('count(*)')
                    ->whereColumn('product_views.product_id', 'products.id')
                    ->where('visited_at', '>=', now()->subDays(7)),
                'product_views_7d',
            );
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
            'unique_visitors_count',
            'product_views_7d',
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
            $product->unique_visitors_count ?? 0,
            $product->product_views_7d ?? 0,
            $product->image_url,
            $product->image_path,
        ];
    }
}
