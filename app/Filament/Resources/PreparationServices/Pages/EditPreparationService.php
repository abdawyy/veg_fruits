<?php

namespace App\Filament\Resources\PreparationServices\Pages;

use App\Filament\Resources\PreparationServices\PreparationServiceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPreparationService extends EditRecord
{
    protected static string $resource = PreparationServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
