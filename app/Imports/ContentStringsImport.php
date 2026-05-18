<?php

namespace App\Imports;

use App\Models\ContentString;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ContentStringsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $key = trim((string) ($row['key'] ?? ''));
        if ($key === '') {
            return null;
        }

        $rec = ContentString::query()->firstOrNew(['key' => $key]);
        if (! $rec->exists) {
            $rec->group = 'general';
        }

        if (isset($row['group'])) {
            $g = trim((string) $row['group']);
            $rec->group = $g !== '' ? $g : ($rec->group ?? 'general');
        }

        if (isset($row['value_en'])) {
            $rec->value_en = (string) $row['value_en'];
        }

        if (isset($row['value_ar'])) {
            $rec->value_ar = (string) $row['value_ar'];
        }

        if (isset($row['admin_note'])) {
            $rec->admin_note = (string) $row['admin_note'];
        }

        return $rec;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'key' => ['required', 'string', 'max:191'],
        ];
    }
}
