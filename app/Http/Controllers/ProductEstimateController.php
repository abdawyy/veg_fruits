<?php

namespace App\Http\Controllers;

use App\Models\PackagingType;
use App\Models\PreparationService;
use App\Models\Product;
use App\Services\Money\DecimalMath;
use App\Services\Pricing\SurchargeOnBase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ProductEstimateController extends Controller
{
    public function __invoke(Request $request, Product $product): JsonResponse
    {
        abort_unless($product->is_active, 404);

        $data = $request->validate([
            'kg' => ['required', 'numeric', 'min:0.25', 'max:500'],
            'preparation_service_ids' => ['sometimes', 'array'],
            'preparation_service_ids.*' => ['integer', 'exists:preparation_services,id'],
            'packaging_type_id' => ['nullable', 'integer', 'exists:packaging_types,id'],
        ]);

        $product->load([
            'preparationServices' => fn ($q) => $q->where('preparation_services.is_active', true),
            'packagingTypes' => fn ($q) => $q->where('packaging_types.is_active', true),
        ]);

        $kg = bcadd((string) $data['kg'], '0', 4);
        $prepIds = array_values(array_unique(array_map('intval', $data['preparation_service_ids'] ?? [])));

        $enabledPrep = $product->preparationServices->filter(fn ($p) => (bool) $p->pivot->is_enabled);
        $enabledPack = $product->packagingTypes->filter(fn ($p) => (bool) $p->pivot->is_enabled);

        $selectedPrep = $enabledPrep->whereIn('id', $prepIds)->values();
        $packagingTypeId = isset($data['packaging_type_id']) ? (int) $data['packaging_type_id'] : null;
        $packagingType = $packagingTypeId
            ? $enabledPack->firstWhere('id', $packagingTypeId)
            : null;

        $unitPrice = DecimalMath::normalizeNumericString((string) $product->price_per_kg);
        $lineBase = DecimalMath::mul($unitPrice, $kg);
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
