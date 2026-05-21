<?php

namespace App\Http\Controllers\Api\V1;

use App\DTO\CartLineDto;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Cart\CalculateCartTotalService;
use App\Services\Money\DecimalMath;
use App\Services\Pricing\SurchargeOnBase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

final class CartQuoteController extends Controller
{
    public function __invoke(Request $request, CalculateCartTotalService $totals): JsonResponse
    {
        $data = $request->validate([
            'lines' => ['required', 'array', 'min:1'],
            'lines.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'lines.*.unit' => ['required', Rule::in(['kg', 'piece'])],
            'lines.*.quantity' => ['required', 'numeric', 'min:0.01', 'max:500'],
            'lines.*.preparation_service_ids' => ['sometimes', 'array'],
            'lines.*.preparation_service_ids.*' => ['integer', 'exists:preparation_services,id'],
            'lines.*.packaging_type_id' => ['nullable', 'integer', 'exists:packaging_types,id'],
            'packaging_fee' => ['sometimes', 'numeric', 'min:0'],
        ]);

        $cartLines = [];
        $lineDetails = [];

        foreach ($data['lines'] as $row) {
            $product = Product::query()
                ->active()
                ->with([
                    'preparationServices' => fn ($q) => $q->where('preparation_services.is_active', true),
                    'packagingTypes' => fn ($q) => $q->where('packaging_types.is_active', true),
                ])
                ->findOrFail((int) $row['product_id']);

            if ($product->isOutOfStock() || ! $product->hasStockFor((string) $row['quantity'])) {
                return response()->json([
                    'message' => __('aldawy.stock_insufficient'),
                    'product_id' => $product->id,
                ], 422);
            }

            $unit = $row['unit'];
            $unitPrice = $unit === 'piece'
                ? ($product->price_per_piece !== null ? DecimalMath::normalizeNumericString((string) $product->price_per_piece) : null)
                : DecimalMath::normalizeNumericString((string) $product->price_per_kg);

            if ($unitPrice === null) {
                return response()->json(['message' => __('aldawy.cart_unit_unavailable'), 'product_id' => $product->id], 422);
            }

            $qty = DecimalMath::normalizeNumericString((string) $row['quantity']);
            $lineBase = DecimalMath::mul($unitPrice, $qty);
            $prepIds = array_map('intval', $row['preparation_service_ids'] ?? []);
            $selectedPrep = $product->preparationServices->whereIn('id', $prepIds)->values();
            $servicesSurcharge = SurchargeOnBase::sumForRules($lineBase, $selectedPrep);
            $packId = isset($row['packaging_type_id']) ? (int) $row['packaging_type_id'] : null;
            $packaging = $packId ? $product->packagingTypes->firstWhere('id', $packId) : null;
            $amountAfterServices = DecimalMath::add($lineBase, $servicesSurcharge);
            $packagingSurcharge = $packaging
                ? SurchargeOnBase::amount($amountAfterServices, $packaging)
                : DecimalMath::normalizeNumericString('0');
            $servicesTotal = DecimalMath::add($servicesSurcharge, $packagingSurcharge);

            $cartLines[] = new CartLineDto(
                unitPrice: $unitPrice,
                quantity: $qty,
                services: [],
                servicesSurchargeTotal: $servicesTotal,
            );

            $lineDetails[] = [
                'product_id' => $product->id,
                'line_subtotal' => DecimalMath::add($amountAfterServices, $packagingSurcharge),
            ];
        }

        $packagingFee = DecimalMath::normalizeNumericString((string) ($data['packaging_fee'] ?? '0'));
        $result = $totals->execute($cartLines, $packagingFee);

        return response()->json([
            'lines' => $lineDetails,
            'lines_subtotal' => $result->linesSubtotal,
            'packaging_fee' => $result->orderPackagingFee,
            'grand_total' => $result->grandTotal,
            'currency' => config('aldawy.currency', 'EGP'),
        ]);
    }
}
