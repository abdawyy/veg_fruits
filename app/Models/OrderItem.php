<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'produce_box_id',
        'product_name_snapshot',
        'unit',
        'quantity',
        'services',
        'packaging',
        'unit_price',
        'line_total',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'product_name_snapshot' => 'array',
            'services' => 'array',
            'metadata' => 'array',
            'quantity' => 'decimal:4',
            'unit_price' => 'decimal:4',
            'line_total' => 'decimal:4',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function produceBox(): BelongsTo
    {
        return $this->belongsTo(ProduceBox::class);
    }
}
