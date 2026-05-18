<?php

namespace App\Filament\Resources\ContentStrings\Pages;

use App\Filament\Resources\ContentStrings\ContentStringResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContentString extends EditRecord
{
    protected static string $resource = ContentStringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
