<?php

namespace App\Http\Controllers;

use App\Actions\Products\RecordProductViewAction;
use App\Models\Category;
use App\Models\HomeBanner;
use App\Models\PackagingType;
use App\Models\PreparationService;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class StorefrontController extends Controller
{
    public function home(): View
    {
        $banners = HomeBanner::query()->active()->ordered()->get();

        $categories = Category::query()
            ->where('is_active', true)
            ->with(['products' => fn ($q) => $q->active()->with('category')->inRandomOrder()->limit(4)])
            ->orderBy('id')
            ->get();

        $featuredCount = Product::query()->active()->count();

        return view('store.home', compact('categories', 'featuredCount', 'banners'));
    }

    public function shop(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get();

        /** @var array<int, LengthAwarePaginator> */
        $categoryProductPages = [];
        if ($q === '') {
            foreach ($categories as $category) {
                $categoryProductPages[$category->id] = Product::query()
                    ->where('category_id', $category->id)
                    ->active()
                    ->with(['category', 'preparationServices', 'packagingTypes'])
                    ->orderBy('name->en')
                    ->paginate(perPage: 12, pageName: 'cat_'.$category->id)
                    ->withQueryString();
            }
        }

        $results = null;
        if ($q !== '') {
            $results = Product::query()
                ->active()
                ->search($q)
                ->with(['category', 'preparationServices', 'packagingTypes'])
                ->orderBy('name->en')
                ->paginate(perPage: 24)
                ->withQueryString();
        }

        return view('store.shop', compact('categories', 'results', 'q', 'categoryProductPages'));
    }

    public function product(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load(['category', 'preparationServices', 'packagingTypes']);

        app(RecordProductViewAction::class)->execute($product);

        [$previousProduct, $nextProduct] = $this->adjacentProductsInCategory($product);

        return view('store.product', compact('product', 'previousProduct', 'nextProduct'));
    }

    /**
     * @return array{0: Product|null, 1: Product|null}
     */
    private function adjacentProductsInCategory(Product $product): array
    {
        $ids = Product::query()
            ->active()
            ->where('category_id', $product->category_id)
            ->orderBy('name->en')
            ->pluck('id')
            ->values();

        $index = $ids->search($product->id, strict: false);

        if ($index === false) {
            return [null, null];
        }

        $previous = $index > 0
            ? Product::query()->active()->with('category')->find($ids[$index - 1])
            : null;

        $next = $index < ($ids->count() - 1)
            ? Product::query()->active()->with('category')->find($ids[$index + 1])
            : null;

        return [$previous, $next];
    }

    public function services(): View
    {
        $prep = PreparationService::query()->where('is_active', true)->orderBy('sort_order')->get();
        $packaging = PackagingType::query()->where('is_active', true)->orderBy('sort_order')->get();

        return view('store.services', compact('prep', 'packaging'));
    }

    public function fruits(Request $request): View
    {
        return $this->categoryCatalog($request, 'fruits');
    }

    public function vegetables(Request $request): View
    {
        return $this->categoryCatalog($request, 'vegetables');
    }

    private function categoryCatalog(Request $request, string $slugEn): View
    {
        $q = trim((string) $request->query('q', ''));

        $category = Category::query()
            ->where('is_active', true)
            ->where('slug->en', $slugEn)
            ->firstOrFail();

        $products = Product::query()
            ->where('category_id', $category->id)
            ->active()
            ->with(['category', 'preparationServices', 'packagingTypes'])
            ->when($q !== '', fn ($query) => $query->search($q))
            ->orderBy('name->en')
            ->paginate(24)
            ->withQueryString();

        return view('store.category-shop', compact('category', 'products', 'q'));
    }
}
