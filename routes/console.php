<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('cron:test', function () {
    $log = storage_path('logs/cron.log');
    $time = now()->format('Y-m-d H:i:s');
    file_put_contents($log, "[{$time}] Cron is working!\n", FILE_APPEND);
    $this->info('Cron test logged at ' . $time);
})->purpose('Test cron job');

Schedule::command('db:backup')->dailyAt('23:00');
Schedule::command('cron:test')->everyMinute();
