<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Concerns\HasAdjacentRecordNavigation;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord as BaseEditRecord;

abstract class EditRecord extends BaseEditRecord
{
    use HasAdjacentRecordNavigation;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getAdjacentRecordNavigationActions(),
            ...$this->getResourceHeaderActions(),
        ];
    }

    /**
     * @return list<Action>
     */
    protected function getResourceHeaderActions(): array
    {
        return [];
    }
}
