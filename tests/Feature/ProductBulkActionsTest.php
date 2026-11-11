<?php

namespace Tests\Feature;

use App\Imports\ProductsImport;
use App\Models\Category;
use App\Models\Product;
use App\Support\ProductBulkEditor;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProductBulkActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_bulk_editor_updates_price_discount_active_min_qty_and_unit(): void
    {
        [$a, $b] = $this->products();

        ProductBulkEditor::applyPrice(new EloquentCollection([$a, $b]), '20', '8');
        $this->assertSame(0, bccomp((string) $a->fresh()->price_per_kg, '20', 4));
        $this->assertSame(0, bccomp((string) $b->fresh()->price_per_piece, '8', 4));

        ProductBulkEditor::applyDiscount(new EloquentCollection([$a->fresh()]), 'percent', '10', 'kg');
        $this->assertSame(0, bccomp((string) $a->fresh()->price_per_kg, '18', 4));

        ProductBulkEditor::setActive(new EloquentCollection([$a->fresh(), $b->fresh()]), false);
        $this->assertFalse($a->fresh()->is_active);
        $this->assertFalse($b->fresh()->is_active);

        ProductBulkEditor::setMinOrderQuantity(new EloquentCollection([$a->fresh()]), '2.5');
        $this->assertSame(0, bccomp((string) $a->fresh()->min_order_quantity, '2.5', 4));
        $this->assertSame(0, bccomp($a->fresh()->minimumOrderQuantity('kg'), '2.5', 4));

        ProductBulkEditor::setSellUnit(new EloquentCollection([$b->fresh()]), 'piece', '9');
        $b = $b->fresh();
        $this->assertSame('piece', $b->unit);
        $this->assertTrue($b->sell_by_piece);
        $this->assertSame(0, bccomp((string) $b->price_per_piece, '9', 4));

        ProductBulkEditor::setSellUnit(new EloquentCollection([$a->fresh()]), 'gram', '0.02');
        $a = $a->fresh();
        $this->assertSame('gram', $a->unit);
        $this->assertFalse($a->sell_by_piece);
        $this->assertSame(0, bccomp((string) $a->price_per_gram, '0.02', 4));
    }

    public function test_excel_import_updates_and_creates_products_by_sku(): void
    {
        [$existing] = $this->products();
        $categoryId = $existing->category_id;

        ProductsImport::applyRow([
            'sku' => $existing->sku,
            'price_per_kg' => 33,
            'min_order_quantity' => 1.5,
            'is_active' => 0,
            'track_stock' => 1,
            'stock_quantity' => 12,
        ]);

        $existing->refresh();
        $this->assertSame(0, bccomp((string) $existing->price_per_kg, '33', 4));
        $this->assertSame(0, bccomp((string) $existing->min_order_quantity, '1.5', 4));
        $this->assertFalse($existing->is_active);
        $this->assertTrue($existing->track_stock);
        $this->assertSame(0, bccomp((string) $existing->stock_quantity, '12', 4));

        ProductsImport::applyRow([
            'sku' => 'NEW-TOM',
            'category_id' => $categoryId,
            'name_en' => 'New Tomato',
            'name_ar' => 'طماطم جديدة',
            'price_per_kg' => 15,
            'sell_by_piece' => 1,
            'price_per_piece' => 3,
            'min_order_quantity' => 2,
            'is_active' => 1,
        ]);

        $created = Product::query()->where('sku', 'NEW-TOM')->first();
        $this->assertNotNull($created);
        $this->assertSame('New Tomato', $created->getTranslation('name', 'en'));
        $this->assertTrue($created->sell_by_piece);
        $this->assertSame(0, bccomp((string) $created->min_order_quantity, '2', 4));
    }

    public function test_cart_rejects_quantity_below_product_minimum(): void
    {
        [$product] = $this->products();
        $product->update(['min_order_quantity' => 2]);

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'kg',
            'quantity' => 1,
        ])->assertSessionHasErrors('quantity');

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'kg',
            'quantity' => 2,
        ])->assertRedirect();
    }

    /**
     * @return array{0: Product, 1: Product}
     */
    private function products(): array
    {
        $category = Category::query()->create([
            'name' => ['en' => 'Produce', 'ar' => 'خضار'],
            'slug' => ['en' => 'produce-bulk', 'ar' => 'produce-bulk'],
            'is_active' => true,
        ]);

        $a = Product::query()->create([
            'category_id' => $category->id,
            'name' => ['en' => 'Tomato', 'ar' => 'طماطم'],
            'slug' => ['en' => 'tomato-bulk', 'ar' => 'tomato-bulk'],
            'sku' => 'BULK-TOM',
            'price_per_kg' => 10,
            'price_per_piece' => 2,
            'sell_by_piece' => true,
            'is_active' => true,
        ]);

        $b = Product::query()->create([
            'category_id' => $category->id,
            'name' => ['en' => 'Cucumber', 'ar' => 'خيار'],
            'slug' => ['en' => 'cucumber-bulk', 'ar' => 'cucumber-bulk'],
            'sku' => 'BULK-CUC',
            'price_per_kg' => 12,
            'price_per_piece' => 4,
            'sell_by_piece' => false,
            'is_active' => true,
        ]);

        return [$a, $b];
    }
}
