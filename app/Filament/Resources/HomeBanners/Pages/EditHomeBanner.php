<?php

namespace App\Filament\Resources\HomeBanners\Pages;

use App\Filament\Resources\HomeBanners\HomeBannerResource;
use App\Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;

class EditHomeBanner extends EditRecord
{
    protected static string $resource = HomeBannerResource::class;

    /**
     * @return array{0: string, 1: 'asc'|'desc'}
     */
    protected function getRecordNavigationSort(): array
    {
        return ['sort_order', 'asc'];
    }

    protected function getResourceHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
