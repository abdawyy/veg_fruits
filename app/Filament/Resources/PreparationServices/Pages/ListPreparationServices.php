<?php

namespace App\Filament\Resources\PreparationServices\Pages;

use App\Filament\Resources\PreparationServices\PreparationServiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPreparationServices extends ListRecords
{
    protected static string $resource = PreparationServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
