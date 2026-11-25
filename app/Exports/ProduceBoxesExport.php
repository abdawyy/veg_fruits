<?php

namespace App\Exports;

use App\Models\ProduceBox;
use App\Models\ProduceBoxView;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProduceBoxesExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @param  list<int>|null  $boxIds  When set, only these boxes are exported.
     */
    public function __construct(
        private readonly ?array $boxIds = null,
    ) {}

    public function query()
    {
        $query = ProduceBox::query()->with(['items.product'])->orderBy('id');

        if ($this->boxIds !== null) {
            $query->whereIn('id', $this->boxIds);
        }

        if (! Schema::hasTable('produce_box_views')) {
            return $query;
        }

        return $query
            ->select('produce_boxes.*')
            ->selectSub(
                ProduceBoxView::query()
                    ->selectRaw('count(distinct session_id)')
                    ->whereColumn('produce_box_views.produce_box_id', 'produce_boxes.id'),
                'unique_visitors_count',
            )
            ->selectSub(
                ProduceBoxView::query()
                    ->selectRaw('count(*)')
                    ->whereColumn('produce_box_views.produce_box_id', 'produce_boxes.id')
                    ->where('visited_at', '>=', now()->subDays(7)),
                'box_views_7d',
            )
            ->selectSub(
                ProduceBoxView::query()
                    ->selectRaw('count(*)')
                    ->whereColumn('produce_box_views.produce_box_id', 'produce_boxes.id')
                    ->where('visited_at', '>=', now()->subDays(30)),
                'box_views_30d',
            );
    }

    /**
     * @return list<string>
     */
    public function headings(): array
    {
        return [
            'id',
            'slug_en',
            'slug_ar',
            'name_en',
            'name_ar',
            'description_en',
            'description_ar',
            'meta_title_en',
            'meta_title_ar',
            'meta_description_en',
            'meta_description_ar',
            'price',
            'compare_at_price',
            'is_active',
            'contents_en',
            'contents_ar',
            'view_count',
            'unique_visitors_count',
            'box_views_7d',
            'box_views_30d',
            'image_path',
        ];
    }

    /**
     * @param  ProduceBox  $box
     * @return list<mixed>
     */
    public function map($box): array
    {
        return [
            $box->id,
            $box->getTranslation('slug', 'en'),
            $box->getTranslation('slug', 'ar'),
            $box->getTranslation('name', 'en'),
            $box->getTranslation('name', 'ar'),
            $box->getTranslation('description', 'en'),
            $box->getTranslation('description', 'ar'),
            $box->getTranslation('meta_title', 'en'),
            $box->getTranslation('meta_title', 'ar'),
            $box->getTranslation('meta_description', 'en'),
            $box->getTranslation('meta_description', 'ar'),
            $box->price,
            $box->compare_at_price,
            $box->is_active ? 1 : 0,
            $box->contentsLabel('en'),
            $box->contentsLabel('ar'),
            $box->view_count,
            $box->unique_visitors_count ?? 0,
            $box->box_views_7d ?? 0,
            $box->box_views_30d ?? 0,
            $box->image_path,
        ];
    }
}
