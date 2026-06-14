<?php

namespace App\Filament\Resources\PackagingTypes\Pages;

use App\Filament\Resources\PackagingTypes\PackagingTypeResource;
use App\Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;

class EditPackagingType extends EditRecord
{
    protected static string $resource = PackagingTypeResource::class;

    protected function getResourceHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
