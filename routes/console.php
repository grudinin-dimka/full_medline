<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\SheduleController;

// Обновление расписания каждй день
Schedule::call(function () {
    (new SheduleController)->saveShedulesAll();
})->everyMinute()->timezone(env('APP_TIMEZONE', 'Asia/Yekaterinburg'));