<?php

namespace App\Exports;

use App\Models\HomeBanner;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class HomeBannersExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return HomeBanner::query()->orderBy('sort_order')->orderBy('id');
    }

    /**
     * @return list<string>
     */
    public function headings(): array
    {
        return [
            'id',
            'sort_order',
            'is_active',
            'title_en',
            'title_ar',
            'subtitle_en',
            'subtitle_ar',
            'badge_en',
            'badge_ar',
            'cta_label_en',
            'cta_label_ar',
            'cta_url',
            'image_url',
            'hot_product_skus',
            'gradient_from',
            'gradient_mid',
            'gradient_to',
            'starts_at',
            'ends_at',
        ];
    }

    /**
     * @param  HomeBanner  $b
     * @return list<mixed>
     */
    public function map($b): array
    {
        return [
            $b->id,
            $b->sort_order,
            $b->is_active ? 1 : 0,
            $b->getTranslation('title', 'en'),
            $b->getTranslation('title', 'ar'),
            $b->getTranslation('subtitle', 'en'),
            $b->getTranslation('subtitle', 'ar'),
            $b->getTranslation('badge_text', 'en'),
            $b->getTranslation('badge_text', 'ar'),
            $b->getTranslation('cta_label', 'en'),
            $b->getTranslation('cta_label', 'ar'),
            $b->cta_url,
            $b->image_url,
            $b->hot_product_skus,
            $b->gradient_from,
            $b->gradient_mid,
            $b->gradient_to,
            $b->starts_at?->toDateTimeString(),
            $b->ends_at?->toDateTimeString(),
        ];
    }
}
