<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Programar actualización de tasa BCV dos veces al día desde la API
Schedule::command('bcv:actualizar')->dailyAt('09:00'); // 9:00 AM
Schedule::command('bcv:actualizar')->dailyAt('15:00'); // 3:00 PM
