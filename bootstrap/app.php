<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Добавление проверки на права доступа
        $middleware->alias([
            'admin' => \App\Http\Middleware\CheckAdmin::class,
            'creator' => \App\Http\Middleware\CheckCreator::class,
            'admin-or-creator' => \App\Http\Middleware\CheckAdminOrCreator::class,
            'track-load' => \App\Http\Middleware\TrackLoad::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();
    })->create();
