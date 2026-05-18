<?php

namespace App\Sms;

use App\Contracts\Sms\SmsSenderInterface;
use Illuminate\Support\Facades\Log;

/**
 * Local/dev stand-in for a national SMS gateway. Swap binding in AppServiceProvider for production.
 */
final class LogSmsSender implements SmsSenderInterface
{
    public function send(string $phoneE164, string $message): void
    {
        Log::info('[SMS] '.$phoneE164.' — '.$message);
    }
}
