<?php

namespace App\Models;

use App\Enums\SubscriptionInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'produce_box_id',
        'interval',
        'status',
        'starts_at',
        'next_order_at',
        'last_generated_at',
    ];

    protected function casts(): array
    {
        return [
            'interval' => SubscriptionInterval::class,
            'starts_at' => 'datetime',
            'next_order_at' => 'datetime',
            'last_generated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function produceBox(): BelongsTo
    {
        return $this->belongsTo(ProduceBox::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
