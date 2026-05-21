<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'name' => $this->getTranslations('name'),
            'slug' => $this->getTranslations('slug'),
            'description' => $this->getTranslations('description'),
            'price_per_kg' => $this->price_per_kg,
            'price_per_piece' => $this->price_per_piece,
            'sell_by_piece' => $this->sell_by_piece,
            'category_id' => $this->category_id,
            'image_url' => $this->image_url,
            'image_path' => $this->image_path,
            'display_image_url' => $this->display_image_url,
            'track_stock' => (bool) $this->track_stock,
            'stock_quantity' => $this->stock_quantity,
            'in_stock' => ! $this->isOutOfStock(),
        ];
    }
}
