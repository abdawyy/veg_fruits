<?php

namespace App\Actions\Auth;

use App\Contracts\Sms\SmsSenderInterface;
use App\Models\PhoneVerification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

final class SendPhoneOtpAction
{
    public function __construct(private readonly SmsSenderInterface $sms) {}

    public function execute(string $phoneNumber): void
    {
        $phone = $this->normalizePhone($phoneNumber);
        $code = config('aldawy.otp.driver') === 'log'
            ? '123456'
            : (string) random_int(100000, 999999);

        PhoneVerification::query()
            ->where('phone_number', $phone)
            ->delete();

        PhoneVerification::query()->create([
            'phone_number' => $phone,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes((int) config('aldawy.otp.ttl_minutes', 10)),
        ]);

        $this->sms->send($phone, __('aldawy.otp_sms_message', ['code' => $code]));
    }

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/\s+/', '', trim($phone)) ?? trim($phone);
    }
}
