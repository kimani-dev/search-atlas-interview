<?php

use App\Jobs\CheckOverdueBookLoans;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    CheckOverdueBookLoans::dispatch();
})->dailyAt('00:00');
