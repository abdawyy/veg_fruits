<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->record = ProductResource::getEloquentQuery()
            ->whereKey($this->record->getKey())
            ->first() ?? $this->record;

        return $data;
    }

    protected function getResourceHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
