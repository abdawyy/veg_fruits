<?php

namespace App\Actions\Products;

use App\Models\Product;
use App\Models\ProductView;
use Illuminate\Support\Facades\Schema;

final class RecordProductViewAction
{
    public function execute(Product $product): void
    {
        if (! Schema::hasColumn('products', 'view_count')) {
            return;
        }

        if (Schema::hasTable('product_views')) {
            ProductView::query()->create([
                'product_id' => $product->id,
                'session_id' => $this->resolveSessionId(),
                'user_id' => auth()->id(),
                'visited_at' => now(),
            ]);
        }

        $product->increment('view_count');
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
