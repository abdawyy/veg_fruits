<?php

namespace App\Actions\Auth;

use App\Actions\Orders\LinkGuestOrdersToUserAction;
use App\Models\PhoneVerification;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class LoginWithPhoneOtpAction
{
    public function __construct(private readonly LinkGuestOrdersToUserAction $linkGuestOrders) {}

    /**
     * @return array{user: User, linked: int}
     */
    public function execute(string $phoneNumber, string $code, ?int $linkGuestOrderId = null): array
    {
        $phone = preg_replace('/\s+/', '', trim($phoneNumber)) ?? trim($phoneNumber);

        $verification = PhoneVerification::query()
            ->where('phone_number', $phone)
            ->where('expires_at', '>', Carbon::now())
            ->latest('id')
            ->first();

        if (! $verification || ! hash_equals($verification->code, trim($code))) {
            throw ValidationException::withMessages([
                'code' => [__('aldawy.otp_invalid')],
            ]);
        }

        $verification->delete();

        $user = User::query()->firstOrCreate(
            ['phone_number' => $phone],
            [
                'name' => __('aldawy.phone_user_default_name'),
                'email' => null,
                'password' => bcrypt(Str::random(32)),
                'phone_verified_at' => now(),
            ],
        );

        if ($user->phone_verified_at === null) {
            $user->forceFill(['phone_verified_at' => now()])->save();
        }

        Auth::login($user, remember: true);

        $linked = $this->linkGuestOrders->execute($user, $linkGuestOrderId);

        return ['user' => $user, 'linked' => $linked];
    }
}
