<?php

namespace App\Filament\Resources\Cities\Pages;

use App\Filament\Resources\Cities\CityResource;
use App\Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;

class EditCity extends EditRecord
{
    protected static string $resource = CityResource::class;

    protected function getResourceHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
