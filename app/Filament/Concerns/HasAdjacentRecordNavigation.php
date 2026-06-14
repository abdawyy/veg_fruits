<?php

namespace App\Filament\Concerns;

use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;

trait HasAdjacentRecordNavigation
{
    /**
     * @return array{0: string, 1: 'asc'|'desc'}
     */
    protected function getRecordNavigationSort(): array
    {
        return ['id', 'asc'];
    }

    protected function getRecordNavigationPageName(): string
    {
        return $this instanceof ViewRecord ? 'view' : 'edit';
    }

    /**
     * @return list<Action>
     */
    protected function getAdjacentRecordNavigationActions(): array
    {
        $adjacent = $this->resolveAdjacentRecordKeys();

        return [
            $this->makeAdjacentRecordAction(
                name: 'previousRecord',
                label: __('Previous'),
                icon: Heroicon::OutlinedChevronLeft,
                recordKey: $adjacent['previous'],
            ),
            $this->makeAdjacentRecordAction(
                name: 'nextRecord',
                label: __('Next'),
                icon: Heroicon::OutlinedChevronRight,
                recordKey: $adjacent['next'],
            ),
        ];
    }

    protected function makeAdjacentRecordAction(string $name, string $label, Heroicon $icon, mixed $recordKey): Action
    {
        $resource = static::getResource();
        $pageName = $this->getRecordNavigationPageName();

        return Action::make($name)
            ->label($label)
            ->icon($icon)
            ->color('gray')
            ->visible(fn (): bool => $recordKey !== null && $this->canNavigateToRecord($recordKey))
            ->url(fn (): string => $resource::getUrl($pageName, ['record' => $recordKey]));
    }

    /**
     * @return array{previous: mixed, next: mixed}
     */
    protected function resolveAdjacentRecordKeys(): array
    {
        $record = $this->getRecord();
        $query = static::getResource()::getEloquentQuery();
        [$column, $direction] = $this->getRecordNavigationSort();
        $keyName = $record->getKeyName();
        $currentKey = $record->getKey();

        if ($column === $keyName) {
            if ($direction === 'asc') {
                return [
                    'previous' => (clone $query)->where($keyName, '<', $currentKey)->orderByDesc($keyName)->value($keyName),
                    'next' => (clone $query)->where($keyName, '>', $currentKey)->orderBy($keyName)->value($keyName),
                ];
            }

            return [
                'previous' => (clone $query)->where($keyName, '>', $currentKey)->orderBy($keyName)->value($keyName),
                'next' => (clone $query)->where($keyName, '<', $currentKey)->orderByDesc($keyName)->value($keyName),
            ];
        }

        $keys = (clone $query)
            ->orderBy($column, $direction)
            ->orderBy($keyName, $direction)
            ->pluck($keyName)
            ->values();

        $index = $keys->search($currentKey, strict: false);

        if ($index === false) {
            return ['previous' => null, 'next' => null];
        }

        return [
            'previous' => $index > 0 ? $keys[$index - 1] : null,
            'next' => $index < ($keys->count() - 1) ? $keys[$index + 1] : null,
        ];
    }

    protected function canNavigateToRecord(mixed $recordKey): bool
    {
        $resource = static::getResource();
        $model = $resource::getModel();
        /** @var Model|null $target */
        $target = $model::query()->whereKey($recordKey)->first();

        if ($target === null) {
            return false;
        }

        $pageName = $this->getRecordNavigationPageName();

        return match ($pageName) {
            'view' => $resource::canView($target),
            'edit' => $resource::canEdit($target),
            default => false,
        };
    }
}
