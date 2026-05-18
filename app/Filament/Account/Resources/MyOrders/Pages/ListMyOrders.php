<?php

namespace App\Filament\Account\Resources\MyOrders\Pages;

use App\Filament\Account\Resources\MyOrders\MyOrderResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;

class ListMyOrders extends ListRecords
{
    protected static string $resource = MyOrderResource::class;

    /**
     * Default rows per page for the My orders table.
     *
     * @var int|string|null
     */
    public $tableRecordsPerPage = 10;

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(10)
            ->extremePaginationLinks();
    }

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
