<?php

namespace App\Filament\Resources\HomeBanners\Pages;

use App\Filament\Resources\HomeBanners\HomeBannerResource;
use App\Filament\Resources\Pages\EditRecord;
use App\Models\HomeBanner;
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

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        /** @var HomeBanner $record */
        $record = $this->getRecord();
        $data['cta_product_ids'] = $record->resolvedCtaProductIds();

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['cta_buttons'] = HomeBanner::packCtaButtons(
            is_array($data['cta_label'] ?? null) ? $data['cta_label'] : [],
            $data['cta_url'] ?? null,
            is_array($data['cta_product_ids'] ?? null) ? $data['cta_product_ids'] : [],
        );
        unset($data['cta_product_ids']);

        return $data;
    }
}
