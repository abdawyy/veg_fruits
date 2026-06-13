<?php

namespace App\Imports;

use App\Models\HomeBanner;
use App\Support\HomeBannerAttributes;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class HomeBannersImport implements ToModel, WithHeadingRow, WithValidation
{
    private int $nextSortOrder;

    public function __construct()
    {
        $this->nextSortOrder = (int) (HomeBanner::query()->max('sort_order') ?? -1);
    }

    public function model(array $row)
    {
        $id = isset($row['id']) && $row['id'] !== '' ? (int) $row['id'] : null;

        if ($id === null || $id < 1) {
            $titleEn = trim((string) ($row['title_en'] ?? ''));
            $titleAr = trim((string) ($row['title_ar'] ?? ''));
            if ($titleEn === '' && $titleAr === '') {
                return null;
            }

            $this->nextSortOrder++;

            return new HomeBanner(HomeBannerAttributes::fromImportRow($row, $this->nextSortOrder));
        }

        $banner = HomeBanner::query()->find($id);
        if ($banner === null) {
            return null;
        }

        $banner->fill(HomeBannerAttributes::fromImportRow($row, (int) $banner->sort_order));

        return $banner;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'id' => ['nullable', 'integer'],
            'title_en' => ['required_without:id'],
            'title_ar' => ['required_without:id'],
        ];
    }
}
