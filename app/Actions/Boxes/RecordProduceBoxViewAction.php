<?php

namespace App\Actions\Boxes;

use App\Models\ProduceBox;
use App\Models\ProduceBoxView;
use Illuminate\Support\Facades\Schema;

final class RecordProduceBoxViewAction
{
    public function execute(ProduceBox $box): void
    {
        if (! Schema::hasColumn('produce_boxes', 'view_count')) {
            return;
        }

        if (Schema::hasTable('produce_box_views')) {
            ProduceBoxView::query()->create([
                'produce_box_id' => $box->id,
                'session_id' => $this->resolveSessionId(),
                'user_id' => auth()->id(),
                'visited_at' => now(),
            ]);
        }

        $box->increment('view_count');
    }

    private function resolveSessionId(): string
    {
        $sessionId = session()->getId();
        if ($sessionId !== '') {
            return $sessionId;
        }

        return 'api:'.hash('sha256', (string) request()->ip().'|'.substr((string) request()->userAgent(), 0, 512));
    }
}
