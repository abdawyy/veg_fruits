<?php

namespace App\Exports;

use App\Models\City;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CitiesExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return City::query()->orderBy('sort_order')->orderBy('id');
    }

    /**
     * @return list<string>
     */
    public function headings(): array
    {
        return [
            'id',
            'code',
            'name_en',
            'name_ar',
            'shipping_fee',
            'is_active',
            'sort_order',
        ];
    }

    /**
     * @param  City  $city
     * @return list<mixed>
     */
    public function map($city): array
    {
        return [
            $city->id,
            $city->code,
            $city->getTranslation('name', 'en'),
            $city->getTranslation('name', 'ar'),
            $city->shipping_fee,
            $city->is_active ? 1 : 0,
            $city->sort_order,
        ];
    }
}
