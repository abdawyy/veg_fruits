<?php

namespace App\Filament\Resources\PreparationServices\Pages;

use App\Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PreparationServices\PreparationServiceResource;
use Filament\Actions\DeleteAction;

class EditPreparationService extends EditRecord
{
    protected static string $resource = PreparationServiceResource::class;

    protected function getResourceHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
