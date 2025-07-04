<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\SheduleController;
use App\Http\Controllers\ClientController;

/* Обновление расписания каждый день */
Schedule::call(function () {
    // Обновление расписания
    (new SheduleController)->saveShedulesAll();

    // Обновление клиентов
    (new ClientController)->saveClientPoints();
})->everyMinute()->timezone(env('APP_TIMEZONE', 'Asia/Yekaterinburg'));
