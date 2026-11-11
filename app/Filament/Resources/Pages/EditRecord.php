<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Concerns\HasAdjacentRecordNavigation;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord as BaseEditRecord;
use Filament\Support\Facades\FilamentView;

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

    public function saveAndOpenRecord(int|string $recordKey): void
    {
        $this->save(shouldRedirect: false);

        if (! $this->canNavigateToRecord($recordKey)) {
            return;
        }

        $url = static::getResource()::getUrl($this->getRecordNavigationPageName(), ['record' => $recordKey]);

        $this->redirect($url, navigate: FilamentView::hasSpaMode($url));
    }
}
