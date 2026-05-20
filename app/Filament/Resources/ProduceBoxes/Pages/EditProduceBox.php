<?php

namespace App\Filament\Resources\ProduceBoxes\Pages;

use App\Filament\Resources\ProduceBoxes\ProduceBoxResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduceBox extends EditRecord
{
    protected static string $resource = ProduceBoxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
