<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jalankan dengan `php artisan schedule:work` saat pengembangan lokal.
Schedule::command('borrowly:check-expirations')->everyMinute()->withoutOverlapping();
