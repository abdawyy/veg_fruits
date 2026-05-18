<?php

namespace App\Contracts\Sms;

interface SmsSenderInterface
{
    public function send(string $phoneE164, string $message): void;
}
