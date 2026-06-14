<?php

namespace App\Filament\Resources\ContentStrings\Pages;

use App\Filament\Resources\ContentStrings\ContentStringResource;
use App\Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;

class EditContentString extends EditRecord
{
    protected static string $resource = ContentStringResource::class;

    protected function getResourceHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
