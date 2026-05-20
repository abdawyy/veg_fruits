<?php

namespace App\Actions\Orders;

use App\Models\Order;
use App\Models\User;

final class LinkGuestOrdersToUserAction
{
    public function execute(User $user, ?int $orderIdFromSession = null): int
    {
        $linked = 0;

        if ($orderIdFromSession !== null) {
            $linked += Order::query()
                ->whereKey($orderIdFromSession)
                ->whereNull('user_id')
                ->update(['user_id' => $user->id]);
        }

        $email = strtolower(trim((string) $user->email));
        if ($email !== '') {
            $linked += Order::query()
                ->whereNull('user_id')
                ->whereRaw('LOWER(customer_email) = ?', [$email])
                ->update(['user_id' => $user->id]);
        }

        $phone = trim((string) $user->phone_number);
        if ($phone !== '') {
            $linked += Order::query()
                ->whereNull('user_id')
                ->where('customer_phone', $phone)
                ->update(['user_id' => $user->id]);
        }

        return $linked;
    }
}
