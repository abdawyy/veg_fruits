<?php

namespace App\Filament\Account\Resources\MyOrders;

use App\Filament\Account\Resources\MyOrders\Pages\ListMyOrders;
use App\Filament\Account\Resources\MyOrders\Pages\ViewMyOrder;
use App\Filament\Account\Resources\MyOrders\Schemas\MyOrderInfolist;
use App\Filament\Account\Resources\MyOrders\Tables\MyOrdersTable;
use App\Filament\Resources\Orders\RelationManagers\OrderItemsRelationManager;
use App\Models\Order;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class MyOrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static string|UnitEnum|null $navigationGroup = 'My account';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('My orders');
    }

    public static function getModelLabel(): string
    {
        return __('Order');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Orders');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', auth()->id())
            ->with(['city']);
    }

    public static function canViewAny(): bool
    {
        return auth()->check();
    }

    public static function canView(Model $record): bool
    {
        return (int) $record->user_id === (int) auth()->id();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function infolist(Schema $schema): Schema
    {
        return MyOrderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MyOrdersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            OrderItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMyOrders::route('/'),
            'view' => ViewMyOrder::route('/{record}'),
        ];
    }
}
