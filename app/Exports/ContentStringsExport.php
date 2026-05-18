<?php

namespace App\Exports;

use App\Models\ContentString;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ContentStringsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return ContentString::query()->orderBy('group')->orderBy('key');
    }

    /**
     * @return list<string>
     */
    public function headings(): array
    {
        return [
            'id',
            'key',
            'group',
            'value_en',
            'value_ar',
            'admin_note',
        ];
    }

    /**
     * @param  ContentString  $c
     * @return list<mixed>
     */
    public function map($c): array
    {
        return [
            $c->id,
            $c->key,
            $c->group,
            $c->value_en,
            $c->value_ar,
            $c->admin_note,
        ];
    }
}
