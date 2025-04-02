<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\AdminController;

// Обновление расписания каждй день
Schedule::call(function () {
    (new AdminController)->saveShedulesAll();
})->twiceDailyAt(12, 19, 30)->timezone('Asia/Yekaterinburg');