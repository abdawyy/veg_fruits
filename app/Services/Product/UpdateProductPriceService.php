<?php

namespace App\Services\Product;

use App\Models\Product;

/**
 * Central place to update catalog prices (e.g. after Excel import). Call from Actions/Imports, not controllers.
 */
final class UpdateProductPriceService
{
    public function updateKiloAndPiece(Product $product, string $pricePerKg, ?string $pricePerPiece = null): void
    {
        $product->forceFill([
            'price_per_kg' => $pricePerKg,
            'price_per_piece' => $pricePerPiece,
        ])->save();
    }
}
