<?php

namespace Tests\Feature;

use App\Filament\Widgets\TopBoxesByViewsChart;
use App\Models\ProduceBox;
use App\Models\ProduceBoxView;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

final class ProduceBoxViewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_opening_a_box_counts_the_view_and_the_visitor(): void
    {
        $box = ProduceBox::query()->create([
            'name' => ['en' => 'Salad box', 'ar' => 'صندوق سلطة'],
            'slug' => ['en' => 'salad-views', 'ar' => 'salad-views'],
            'price' => 80,
            'is_active' => true,
        ]);

        $this->get(route('store.boxes.show', $box))->assertOk();
        $this->get(route('store.boxes.show', $box))->assertOk();

        $box->refresh();
        $this->assertSame(2, (int) $box->view_count);
        $this->assertSame(2, ProduceBoxView::query()->where('produce_box_id', $box->id)->count());

        Livewire::test(TopBoxesByViewsChart::class, ['pageFilters' => ['period' => '7']])
            ->assertOk()
            ->assertSee('Salad box');
    }
}
