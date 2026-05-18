<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProduceBoxItem extends Model
{
    protected $fillable = ['produce_box_id', 'product_id', 'quantity', 'unit'];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
        ];
    }

    public function produceBox(): BelongsTo
    {
        return $this->belongsTo(ProduceBox::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
