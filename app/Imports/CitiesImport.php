<?php

namespace App\Imports;

use App\Models\City;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CitiesImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $code = trim((string) ($row['code'] ?? ''));
        if ($code === '') {
            return null;
        }

        $city = City::query()->firstOrNew(['code' => $code]);

        $nameEn = trim((string) ($row['name_en'] ?? ''));
        $nameAr = trim((string) ($row['name_ar'] ?? ''));
        if ($nameEn !== '' || $nameAr !== '') {
            $city->name = [
                'en' => $nameEn !== '' ? $nameEn : ($city->getTranslation('name', 'en') ?: $code),
                'ar' => $nameAr !== '' ? $nameAr : ($city->getTranslation('name', 'ar') ?: $nameEn ?: $code),
            ];
        }

        if (isset($row['shipping_fee']) && $row['shipping_fee'] !== '') {
            $city->shipping_fee = $row['shipping_fee'];
        }

        if (isset($row['is_active'])) {
            $city->is_active = filter_var($row['is_active'], FILTER_VALIDATE_BOOLEAN)
                || (string) $row['is_active'] === '1';
        }

        if (isset($row['sort_order']) && $row['sort_order'] !== '') {
            $city->sort_order = (int) $row['sort_order'];
        }

        return $city;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:64'],
            'shipping_fee' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
