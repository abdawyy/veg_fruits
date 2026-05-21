<?php

namespace App\Actions\Invoices;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

final class AuthorizeInvoiceDownloadAction
{
    public function assertCanDownload(Request $request, Order $order): void
    {
        $user = $request->user();

        if ($user instanceof User) {
            if ($order->user_id !== null && (int) $order->user_id === (int) $user->id) {
                return;
            }

            if ($this->guestOrderMatchesUser($order, $user)) {
                return;
            }

            abort(403);
        }

        if (! $request->hasValidSignature()) {
            abort(403);
        }
    }

    private function guestOrderMatchesUser(Order $order, User $user): bool
    {
        if ($order->user_id !== null && (int) $order->user_id !== (int) $user->id) {
            return false;
        }

        if ($user->phone_number && $order->customer_phone) {
            if ($this->normalizePhone($user->phone_number) === $this->normalizePhone($order->customer_phone)) {
                return true;
            }
        }

        if ($user->email && $order->customer_email) {
            if (strcasecmp($user->email, $order->customer_email) === 0) {
                return true;
            }
        }

        return false;
    }

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/\D+/', '', $phone) ?? $phone;
    }
}
