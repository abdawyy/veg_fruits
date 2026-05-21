<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LoginWithPhoneOtpAction;
use App\Actions\Auth\SendPhoneOtpAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class PhoneAuthController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.phone-login', [
            'redirect' => $request->string('redirect')->toString(),
            'step' => 'phone',
        ]);
    }

    public function sendOtp(Request $request, SendPhoneOtpAction $send): RedirectResponse
    {
        if (! config('aldawy.otp.enabled')) {
            return back()->withErrors(['phone' => __('aldawy.otp_disabled')]);
        }

        $data = $request->validate([
            'phone' => ['required', 'string', 'max:32'],
            'redirect' => ['nullable', 'string', 'max:500'],
        ]);

        $send->execute($data['phone']);

        return redirect()
            ->route('phone.login.verify', array_filter([
                'redirect' => $data['redirect'] ?? null,
            ]))
            ->with('phone', $data['phone'])
            ->with('status', __('aldawy.otp_sent'));
    }

    public function verifyForm(Request $request): View|RedirectResponse
    {
        $phone = $request->session()->get('phone') ?? old('phone');
        if (! is_string($phone) || $phone === '') {
            return redirect()->route('phone.login');
        }

        return view('auth.phone-login', [
            'redirect' => $request->string('redirect')->toString(),
            'step' => 'verify',
            'phone' => $phone,
        ]);
    }

    public function verify(Request $request, LoginWithPhoneOtpAction $login): RedirectResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:32'],
            'code' => ['required', 'string', 'max:12'],
            'redirect' => ['nullable', 'string', 'max:500'],
        ]);

        $result = $login->execute(
            $data['phone'],
            $data['code'],
            $request->session()->pull('link_guest_order_id'),
        );

        $request->session()->regenerate();

        $response = redirect()->intended(route('store.home'));
        $intended = $data['redirect'] ?? null;
        if (is_string($intended) && $intended !== '' && str_starts_with($intended, '/') && ! str_starts_with($intended, '//')) {
            $response = redirect()->to($intended);
        }

        if ($result['linked'] > 0) {
            $response = $response->with('status', __('aldawy.orders_linked', ['count' => $result['linked']]));
        }

        return $response;
    }
}
