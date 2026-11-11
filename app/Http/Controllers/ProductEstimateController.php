<?php

namespace App\Http\Controllers;

use App\Models\PackagingType;
use App\Models\Product;
use App\Services\Money\DecimalMath;
use App\Services\Pricing\SurchargeOnBase;
use App\Support\StoreCart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

final class ProductEstimateController extends Controller
{
    public function __invoke(Request $request, Product $product): JsonResponse
    {
        abort_unless($product->is_active, 404);

        $data = $request->validate([
            'unit' => ['sometimes', Rule::in(StoreCart::units())],
            'quantity' => ['sometimes', 'numeric', 'min:0.01', 'max:500000'],
            'kg' => ['sometimes', 'numeric', 'min:0.25', 'max:500'],
            'preparation_service_ids' => ['sometimes', 'array'],
            'preparation_service_ids.*' => ['integer', 'exists:preparation_services,id'],
            'packaging_type_id' => ['nullable', 'integer', 'exists:packaging_types,id'],
        ]);

        $unit = $data['unit'] ?? $product->sellingUnit();
        if (isset($data['quantity'])) {
            $quantity = (string) $data['quantity'];
        } elseif (isset($data['kg'])) {
            $unit = StoreCart::UNIT_KG;
            $quantity = (string) $data['kg'];
        } else {
            $quantity = $product->minimumOrderQuantity($unit);
        }

        if (! $product->allowsUnit($unit)) {
            throw ValidationException::withMessages([
                'unit' => [__('aldawy.cart_unit_unavailable')],
            ]);
        }

        $maximum = StoreCart::maxQuantity($unit);
        if (bccomp(DecimalMath::normalizeNumericString($quantity), $maximum, 4) > 0) {
            throw ValidationException::withMessages([
                'quantity' => [__('aldawy.cart_max_quantity', [
                    'maximum' => StoreCart::formatQuantity($maximum, $unit),
                    'unit' => StoreCart::unitLabel($unit),
                ])],
            ]);
        }

        $product->load([
            'preparationServices' => fn ($q) => $q->where('preparation_services.is_active', true),
            'packagingTypes' => fn ($q) => $q->where('packaging_types.is_active', true),
        ]);

        $quantity = $unit === StoreCart::UNIT_KG
            ? bcadd($quantity, '0', 4)
            : (string) max(1, (int) round((float) $quantity));

        $prepIds = array_values(array_unique(array_map('intval', $data['preparation_service_ids'] ?? [])));
        $enabledPrep = $product->preparationServices->filter(fn ($p) => (bool) $p->pivot->is_enabled);
        $enabledPack = $product->packagingTypes->filter(fn ($p) => (bool) $p->pivot->is_enabled);
        $selectedPrep = $enabledPrep->whereIn('id', $prepIds)->values();
        $packagingTypeId = isset($data['packaging_type_id']) ? (int) $data['packaging_type_id'] : null;
        $packagingType = $packagingTypeId
            ? $enabledPack->firstWhere('id', $packagingTypeId)
            : null;

        $unitPrice = StoreCart::unitPriceForProduct($product, $unit);
        if ($unitPrice === null) {
            throw ValidationException::withMessages([
                'unit' => [__('aldawy.cart_unit_unavailable')],
            ]);
        }

        $lineBase = DecimalMath::mul($unitPrice, $quantity);
        $servicesSurcharge = SurchargeOnBase::sumForRules($lineBase, $selectedPrep);
        $amountAfterServices = DecimalMath::add($lineBase, $servicesSurcharge);
        $packagingSurcharge = $packagingType instanceof PackagingType
            ? SurchargeOnBase::amount($amountAfterServices, $packagingType)
            : DecimalMath::normalizeNumericString('0');
        $lineSubtotal = DecimalMath::add($amountAfterServices, $packagingSurcharge);

        $currency = config('aldawy.currency', 'EGP');

        return response()->json([
            'ok' => true,
            'line_subtotal' => $lineSubtotal,
            'formatted' => number_format((float) $lineSubtotal, 2).' '.$currency,
            'currency' => $currency,
        ]);
    }
}
