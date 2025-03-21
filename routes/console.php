<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\AdminController;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Обновление расписания каждй день
Schedule::call(function () {
    (new AdminController)->saveShedulesAll();
})->everyMinute();