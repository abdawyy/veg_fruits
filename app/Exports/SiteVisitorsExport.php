<?php

namespace App\Exports;

use App\Models\SiteVisitor;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SiteVisitorsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return SiteVisitor::query()->with('user')->orderByDesc('last_seen_at');
    }

    /**
     * @return list<string>
     */
    public function headings(): array
    {
        return [
            'id',
            'session_id',
            'user_email',
            'first_path',
            'last_path',
            'referrer',
            'utm_source',
            'utm_medium',
            'utm_campaign',
            'device_type',
            'first_seen_at',
            'last_seen_at',
        ];
    }

    /**
     * @param  SiteVisitor  $v
     * @return list<mixed>
     */
    public function map($v): array
    {
        return [
            $v->id,
            $v->session_id,
            $v->user?->email,
            $v->first_path,
            $v->last_path,
            $v->referrer,
            $v->utm_source,
            $v->utm_medium,
            $v->utm_campaign,
            $v->device_type,
            $v->first_seen_at?->toDateTimeString(),
            $v->last_seen_at?->toDateTimeString(),
        ];
    }
}
