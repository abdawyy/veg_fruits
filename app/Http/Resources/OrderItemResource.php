<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\OrderItem */
class OrderItemResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'produce_box_id' => $this->produce_box_id,
            'product_name' => $this->product_name_snapshot,
            'unit' => $this->unit,
            'quantity' => $this->quantity,
            'services' => $this->services,
            'packaging' => $this->packaging,
            'unit_price' => $this->unit_price,
            'line_total' => $this->line_total,
        ];
    }
}
