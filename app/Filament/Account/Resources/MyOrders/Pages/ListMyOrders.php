<?php

namespace App\Filament\Account\Resources\MyOrders\Pages;

use App\Filament\Account\Resources\MyOrders\MyOrderResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ListMyOrders extends ListRecords
{
    protected static string $resource = MyOrderResource::class;

    /**
     * @var int|string|null
     */
    public $tableRecordsPerPage = 10;

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->extremePaginationLinks();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('shop')
                ->label(__('aldawy.cta_shop'))
                ->icon(Heroicon::OutlinedShoppingBag)
                ->url(route('store.shop'))
                ->color('primary'),
        ];
    }
}
