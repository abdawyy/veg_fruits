<?php

namespace App\Exports;

use App\Models\SitePageView;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SitePageViewsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): Collection
    {
        if (! Schema::hasTable('site_page_views')) {
            return collect();
        }

        return SitePageView::query()
            ->where('visited_at', '>=', now()->subDays(180))
            ->orderByDesc('visited_at')
            ->get();
    }

    /**
     * @return list<string>
     */
    public function headings(): array
    {
        return [
            'id',
            'session_id',
            'path',
            'referrer',
            'visited_at',
        ];
    }

    /**
     * @param  SitePageView  $row
     * @return list<mixed>
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->session_id,
            $row->path,
            $row->referrer,
            $row->visited_at?->toDateTimeString(),
        ];
    }
}
