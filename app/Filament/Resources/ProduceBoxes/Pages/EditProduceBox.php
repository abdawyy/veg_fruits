<?php

namespace App\Filament\Resources\ProduceBoxes\Pages;

use App\Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ProduceBoxes\ProduceBoxResource;
use Filament\Actions\DeleteAction;

class EditProduceBox extends EditRecord
{
    protected static string $resource = ProduceBoxResource::class;

    protected function getResourceHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
