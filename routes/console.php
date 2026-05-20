<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Generates orders for due produce-box subscriptions (see ProcessDueSubscriptionsAction).
Schedule::command('aldawy:process-subscriptions')->dailyAt('06:00');
