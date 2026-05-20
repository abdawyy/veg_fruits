<?php

namespace App\Enums;

enum SubscriptionInterval: string
{
    case Monthly = 'monthly';
    case Yearly = 'yearly';

    public function label(): string
    {
        return __('aldawy.subscription_interval_'.$this->value);
    }

    public function nextAfter(\Illuminate\Support\Carbon $from): \Illuminate\Support\Carbon
    {
        return match ($this) {
            self::Monthly => $from->copy()->addMonth(),
            self::Yearly => $from->copy()->addYear(),
        };
    }
}
