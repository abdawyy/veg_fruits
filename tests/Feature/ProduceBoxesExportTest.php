<?php

namespace Tests\Feature;

use App\Exports\ProduceBoxesExport;
use App\Models\ProduceBox;
use App\Support\ProduceBoxSeoCopy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProduceBoxesExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_excel_export_includes_bilingual_seo_copy_and_can_limit_rows(): void
    {
        $copy = ProduceBoxSeoCopy::forSlug('weekly-essentials');
        $weekly = ProduceBox::query()->create([
            'name' => ['en' => 'Weekly essentials box', 'ar' => 'صندوق الأسبوع الأساسي'],
            'slug' => ['en' => 'weekly-essentials', 'ar' => 'weekly-essentials'],
            'description' => $copy['description'],
            'meta_title' => $copy['meta_title'],
            'meta_description' => $copy['meta_description'],
            'price' => 199,
            'is_active' => true,
        ]);
        ProduceBox::query()->create([
            'name' => ['en' => 'Other box', 'ar' => 'صندوق آخر'],
            'slug' => ['en' => 'other-box', 'ar' => 'other-box'],
            'description' => ['en' => 'Other', 'ar' => 'آخر'],
            'price' => 50,
            'is_active' => true,
        ]);

        $export = new ProduceBoxesExport([$weekly->id]);
        $headings = $export->headings();
        $row = $export->map($export->query()->first());

        $this->assertContains('description_en', $headings);
        $this->assertContains('description_ar', $headings);
        $this->assertContains('meta_title_en', $headings);
        $this->assertContains('meta_description_ar', $headings);
        $this->assertSame($copy['description']['en'], $row[array_search('description_en', $headings, true)]);
        $this->assertSame($copy['description']['ar'], $row[array_search('description_ar', $headings, true)]);
        $this->assertSame($copy['meta_title']['en'], $row[array_search('meta_title_en', $headings, true)]);
        $this->assertSame($copy['meta_description']['ar'], $row[array_search('meta_description_ar', $headings, true)]);
        $this->assertCount(1, $export->query()->get());
    }
}
