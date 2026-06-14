<?php

namespace App\Filament\Resources\SeoSettings\Pages;

use App\Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\SeoSettings\SeoSettingResource;

class EditSeoSetting extends EditRecord
{
    protected static string $resource = SeoSettingResource::class;

    protected function getResourceHeaderActions(): array
    {
        return [];
    }
}
