<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Products\Schemas\ProductForm;
use App\Filament\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
use App\Models\ProductView;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema as DbSchema;
use UnitEnum;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static string|UnitEnum|null $navigationGroup = 'Catalog';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('Products');
    }

    public static function getModelLabel(): string
    {
        return __('Product');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Products');
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->withSum('orderItems as sold_kg_total', 'quantity');

        if (! DbSchema::hasTable('product_views')) {
            return $query;
        }

        return $query
            ->selectSub(
                ProductView::query()
                    ->selectRaw('count(distinct session_id)')
                    ->whereColumn('product_views.product_id', 'products.id'),
                'unique_visitors_count',
            )
            ->selectSub(
                ProductView::query()
                    ->selectRaw('count(*)')
                    ->whereColumn('product_views.product_id', 'products.id')
                    ->where('visited_at', '>=', now()->subDays(7)),
                'product_views_7d',
            )
            ->selectSub(
                ProductView::query()
                    ->selectRaw('count(*)')
                    ->whereColumn('product_views.product_id', 'products.id')
                    ->where('visited_at', '>=', now()->subDays(30)),
                'product_views_30d',
            );
    }

    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
