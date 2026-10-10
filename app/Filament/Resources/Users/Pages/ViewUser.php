<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Resources\Users\Widgets\CustomerActivityChart;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getFooterWidgets(): array
    {
        return [
            CustomerActivityChart::class,
        ];
    }
}
