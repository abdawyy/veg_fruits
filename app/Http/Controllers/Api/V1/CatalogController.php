<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CatalogController extends Controller
{
    public function categories(): JsonResponse
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get();

        return response()->json(['data' => CategoryResource::collection($categories)]);
    }

    public function products(Request $request): JsonResponse
    {
        $query = Product::query()
            ->active()
            ->with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', (int) $request->query('category_id'));
        }

        if ($request->filled('q')) {
            $query->search((string) $request->query('q'));
        }

        $products = $query->orderBy('id')->paginate(min(50, max(1, (int) $request->query('per_page', 20))));

        return ProductResource::collection($products)->response();
    }

    public function show(Product $product): JsonResponse
    {
        abort_unless($product->is_active, 404);
        $product->load('category');

        return response()->json(['data' => new ProductResource($product)]);
    }
}
