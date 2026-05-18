<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Order::query()->with('user')->orderByDesc('id');
    }

    /**
     * @return list<string>
     */
    public function headings(): array
    {
        return [
            'id',
            'reference',
            'status',
            'user_email',
            'customer_email',
            'customer_phone',
            'total',
            'notes',
            'created_at',
        ];
    }

    /**
     * @param  Order  $order
     * @return list<mixed>
     */
    public function map($order): array
    {
        return [
            $order->id,
            $order->reference,
            (string) $order->status->value,
            $order->user?->email,
            $order->customer_email,
            $order->customer_phone,
            $order->total,
            $order->notes,
            $order->created_at?->toDateTimeString(),
        ];
    }
}
