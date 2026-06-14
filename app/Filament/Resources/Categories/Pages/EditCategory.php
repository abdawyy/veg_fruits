<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getResourceHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
