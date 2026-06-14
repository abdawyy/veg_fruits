<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    /**
     * @return array{0: string, 1: 'asc'|'desc'}
     */
    protected function getRecordNavigationSort(): array
    {
        return ['created_at', 'desc'];
    }

    protected function getResourceHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
