<?php

namespace App\Http\Controllers;

use App\Actions\Subscriptions\SubscribeToProduceBoxAction;
use App\Enums\SubscriptionInterval;
use App\Models\ProduceBox;
use App\Payments\PaymentGatewayResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

final class SubscriptionController extends Controller
{
    public function store(
        Request $request,
        ProduceBox $produceBox,
        SubscribeToProduceBoxAction $subscribe,
        PaymentGatewayResolver $paymentGateways,
    ): RedirectResponse {
        abort_unless($produceBox->is_active, 404);

        $user = $request->user();
        if ($user === null) {
            return redirect()
                ->guest(route('login', ['redirect' => route('store.boxes.show', $produceBox)]))
                ->with('status', __('aldawy.subscription_login_required'));
        }

        $data = $request->validate([
            'interval' => ['required', Rule::enum(SubscriptionInterval::class)],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'shipping_address_line1' => ['required', 'string', 'max:255'],
            'shipping_address_line2' => ['nullable', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:32'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_email' => ['nullable', 'string', 'lowercase', 'email', 'max:255'],
            'payment_method' => ['required', 'string', 'in:'.implode(',', array_keys($paymentGateways->optionsForCheckout()))],
        ]);

        $interval = SubscriptionInterval::from($data['interval']);

        $result = $subscribe->execute(
            $user,
            $produceBox,
            $interval,
            $data,
            $data['payment_method'],
        );

        $request->session()->put('checkout_order_id', $result['order']->id);

        return redirect()
            ->route('store.checkout.thanks')
            ->with('status', __('aldawy.subscription_started', ['ref' => $result['order']->reference]));
    }
}
