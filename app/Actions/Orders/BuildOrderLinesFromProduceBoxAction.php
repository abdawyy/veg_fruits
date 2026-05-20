<?php

namespace App\Actions\Orders;

use App\DTO\OrderLineDraftDto;
use App\Models\ProduceBox;
use App\Services\Money\DecimalMath;
use App\Support\StoreCart;

final class BuildOrderLinesFromProduceBoxAction
{
    /**
     * @return list<OrderLineDraftDto>
     */
    public function execute(ProduceBox $box, bool $asSingleBoxLine = false): array
    {
        $box->load(['items.product']);

        if ($asSingleBoxLine) {
            return [
                new OrderLineDraftDto(
                    productId: null,
                    produceBoxId: $box->id,
                    productNameSnapshot: $box->getTranslations('name'),
                    unit: 'box',
                    quantity: '1',
                    services: [],
                    packaging: '',
                    unitPrice: DecimalMath::normalizeNumericString((string) $box->price),
                    servicesSurchargeTotal: '0',
                ),
            ];
        }

        $lines = [];
        foreach ($box->items as $item) {
            $product = $item->product;
            if ($product === null || ! $product->is_active) {
                continue;
            }

            $unit = $item->unit === StoreCart::UNIT_PIECE ? StoreCart::UNIT_PIECE : StoreCart::UNIT_KG;
            $unitPrice = StoreCart::unitPriceForProduct($product, $unit);
            if ($unitPrice === null) {
                continue;
            }

            $lines[] = new OrderLineDraftDto(
                productId: $product->id,
                produceBoxId: $box->id,
                productNameSnapshot: $product->getTranslations('name'),
                unit: $unit,
                quantity: DecimalMath::normalizeNumericString((string) $item->quantity),
                services: [],
                packaging: '',
                unitPrice: $unitPrice,
                servicesSurchargeTotal: '0',
            );
        }

        return $lines;
    }
}
