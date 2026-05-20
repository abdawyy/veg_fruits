<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'reference',
        'user_id',
        'city_id',
        'shipping_address_line1',
        'shipping_address_line2',
        'customer_phone',
        'customer_name',
        'customer_email',
        'status',
        'payment_gateway',
        'packaging_code',
        'subtotal',
        'packaging_fee',
        'shipping_fee',
        'total',
        'invoice_path',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'subtotal' => 'decimal:4',
            'packaging_fee' => 'decimal:4',
            'shipping_fee' => 'decimal:4',
            'total' => 'decimal:4',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function canCustomerCancel(): bool
    {
        return in_array($this->status, [OrderStatus::Pending, OrderStatus::Confirmed], true);
    }
}
