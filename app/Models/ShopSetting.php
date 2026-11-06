<?php

namespace App\Models;

use App\Support\MinimumOrder;
use Illuminate\Database\Eloquent\Model;

class ShopSetting extends Model
{
    protected $fillable = [
        'min_order_subtotal',
    ];

    protected function casts(): array
    {
        return [
            'min_order_subtotal' => 'decimal:4',
        ];
    }

    public static function current(): self
    {
        $existing = static::query()->first();

        return $existing ?? static::query()->create([
            'min_order_subtotal' => MinimumOrder::normalize(config('aldawy.min_order_subtotal')),
        ]);
    }
}
