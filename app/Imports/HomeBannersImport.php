<?php

namespace App\Imports;

use App\Models\HomeBanner;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class HomeBannersImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $id = isset($row['id']) && $row['id'] !== '' ? (int) $row['id'] : null;
        if ($id === null || $id < 1) {
            return null;
        }

        $banner = HomeBanner::query()->find($id);
        if ($banner === null) {
            return null;
        }

        if (isset($row['sort_order']) && $row['sort_order'] !== '') {
            $banner->sort_order = (int) $row['sort_order'];
        }

        if (isset($row['is_active'])) {
            $banner->is_active = filter_var($row['is_active'], FILTER_VALIDATE_BOOLEAN)
                || (string) $row['is_active'] === '1';
        }

        $titleEn = trim((string) ($row['title_en'] ?? ''));
        $titleAr = trim((string) ($row['title_ar'] ?? ''));
        if ($titleEn !== '' || $titleAr !== '') {
            $banner->title = [
                'en' => $titleEn !== '' ? $titleEn : $banner->getTranslation('title', 'en'),
                'ar' => $titleAr !== '' ? $titleAr : $banner->getTranslation('title', 'ar'),
            ];
        }

        foreach ([['subtitle', 'subtitle_en', 'subtitle_ar'], ['badge_text', 'badge_en', 'badge_ar'], ['cta_label', 'cta_label_en', 'cta_label_ar']] as [$attr, $enKey, $arKey]) {
            $en = trim((string) ($row[$enKey] ?? ''));
            $ar = trim((string) ($row[$arKey] ?? ''));
            if ($en !== '' || $ar !== '') {
                $banner->{$attr} = [
                    'en' => $en !== '' ? $en : $banner->getTranslation($attr, 'en'),
                    'ar' => $ar !== '' ? $ar : $banner->getTranslation($attr, 'ar'),
                ];
            }
        }

        if (isset($row['cta_url'])) {
            $banner->cta_url = (string) $row['cta_url'];
        }

        if (isset($row['image_url'])) {
            $banner->image_url = (string) $row['image_url'];
        }

        if (isset($row['hot_product_skus'])) {
            $banner->hot_product_skus = (string) $row['hot_product_skus'];
        }

        foreach (['gradient_from', 'gradient_mid', 'gradient_to'] as $g) {
            if (isset($row[$g]) && trim((string) $row[$g]) !== '') {
                $banner->{$g} = (string) $row[$g];
            }
        }

        if (! empty($row['starts_at'])) {
            $banner->starts_at = Carbon::parse((string) $row['starts_at']);
        }
        if (! empty($row['ends_at'])) {
            $banner->ends_at = Carbon::parse((string) $row['ends_at']);
        }

        return $banner;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:home_banners,id'],
        ];
    }
}
