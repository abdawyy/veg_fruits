<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @return array<string, list<string>>
     */
    public static function importRules(): array
    {
        return [
            'sku' => ['required', 'string', 'max:64'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'price_per_kg' => ['nullable', 'numeric', 'min:0'],
            'track_stock' => ['nullable'],
            'stock_quantity' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    /**
     * @param  array<string, mixed>  $row
     */
    public static function applyRow(array $row): void
    {
        $instance = new self;
        $model = $instance->model($row);
        if ($model !== null) {
            $model->save();
        }
    }

    public function model(array $row)
    {
        $sku = trim((string) ($row['sku'] ?? ''));
        if ($sku === '') {
            return null;
        }

        $product = Product::query()->firstOrNew(['sku' => $sku]);

        $nameEn = trim((string) ($row['name_en'] ?? ''));
        $nameAr = trim((string) ($row['name_ar'] ?? ''));
        if ($nameEn !== '' || $nameAr !== '') {
            $product->name = [
                'en' => $nameEn !== '' ? $nameEn : ($product->getTranslation('name', 'en') ?: $sku),
                'ar' => $nameAr !== '' ? $nameAr : ($product->getTranslation('name', 'ar') ?: $nameEn ?: $sku),
            ];
        }

        $slugEn = trim((string) ($row['slug_en'] ?? ''));
        $slugAr = trim((string) ($row['slug_ar'] ?? ''));
        if ($slugEn !== '' || $slugAr !== '') {
            $product->slug = [
                'en' => $slugEn !== '' ? $slugEn : ($product->getTranslation('slug', 'en') ?: \Illuminate\Support\Str::slug($product->getTranslation('name', 'en'))),
                'ar' => $slugAr !== '' ? $slugAr : ($product->getTranslation('slug', 'ar') ?: \Illuminate\Support\Str::slug($product->getTranslation('name', 'ar'))),
            ];
        }

        if (isset($row['category_id']) && $row['category_id'] !== null && $row['category_id'] !== '') {
            $product->category_id = (int) $row['category_id'];
        }

        if (isset($row['description_en']) || isset($row['description_ar'])) {
            $product->description = [
                'en' => (string) ($row['description_en'] ?? $product->getTranslation('description', 'en') ?? ''),
                'ar' => (string) ($row['description_ar'] ?? $product->getTranslation('description', 'ar') ?? ''),
            ];
        }

        if (isset($row['price_per_kg']) && $row['price_per_kg'] !== '') {
            $product->price_per_kg = $row['price_per_kg'];
        }

        if (isset($row['price_per_piece']) && $row['price_per_piece'] !== '') {
            $product->price_per_piece = $row['price_per_piece'];
        }

        if (isset($row['sell_by_piece'])) {
            $product->sell_by_piece = filter_var($row['sell_by_piece'], FILTER_VALIDATE_BOOLEAN)
                || (string) $row['sell_by_piece'] === '1';
        }

        if (isset($row['is_active'])) {
            $product->is_active = filter_var($row['is_active'], FILTER_VALIDATE_BOOLEAN)
                || (string) $row['is_active'] === '1';
        }

        if (isset($row['track_stock'])) {
            $product->track_stock = filter_var($row['track_stock'], FILTER_VALIDATE_BOOLEAN)
                || (string) $row['track_stock'] === '1';
        }

        if (isset($row['stock_quantity']) && $row['stock_quantity'] !== '') {
            $product->stock_quantity = $row['stock_quantity'];
        }

        if (isset($row['image_url']) && $row['image_url'] !== '') {
            $product->image_url = (string) $row['image_url'];
        }

        return $product;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return self::importRules();
    }
}
