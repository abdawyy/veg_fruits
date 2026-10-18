<?php

namespace App\Filament\Resources\HomeBanners\Pages;

use App\Filament\Resources\HomeBanners\HomeBannerResource;
use App\Models\HomeBanner;
use Filament\Resources\Pages\CreateRecord;

class CreateHomeBanner extends CreateRecord
{
    protected static string $resource = HomeBannerResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
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
