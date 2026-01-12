<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
|
| Reset student choix and consigned status every Sunday at midnight (00:00)
|
*/
Schedule::command('students:reset-weekly')
    ->weeklyOn(0, '08:47') // 0 = Sunday
    ->timezone('Africa/Algiers')
    ->withoutOverlapping()
    ->onSuccess(function () {
        Log::info('Weekly student reset completed successfully.');
    })
    ->onFailure(function () {
        Log::error('Weekly student reset failed.');
    });
