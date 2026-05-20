<?php

namespace App\Filament\Resources\ProduceBoxes\Pages;

use App\Filament\Resources\ProduceBoxes\ProduceBoxResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProduceBoxes extends ListRecords
{
    protected static string $resource = ProduceBoxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
