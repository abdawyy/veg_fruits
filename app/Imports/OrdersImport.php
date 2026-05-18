<?php

namespace App\Imports;

use App\Enums\OrderStatus;
use App\Models\Order;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class OrdersImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $ref = trim((string) ($row['reference'] ?? ''));
        if ($ref === '') {
            return null;
        }

        $order = Order::query()->where('reference', $ref)->first();
        if ($order === null) {
            return null;
        }

        if (isset($row['status']) && is_string($row['status']) && trim($row['status']) !== '') {
            $s = trim($row['status']);
            $cases = array_map(fn (OrderStatus $e) => $e->value, OrderStatus::cases());
            if (in_array($s, $cases, true)) {
                $order->status = OrderStatus::from($s);
            }
        }

        if (array_key_exists('notes', $row)) {
            $order->notes = $row['notes'] !== null ? (string) $row['notes'] : null;
        }

        return $order;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'reference' => ['required', 'string', 'max:64'],
            'status' => ['nullable', 'string', 'max:32'],
        ];
    }
}
