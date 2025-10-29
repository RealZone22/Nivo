<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\CheckLanguage::class
        ]);
        $middleware->appendToGroup('auth', [
            \App\Http\Middleware\Authenticate::class,
            \App\Http\Middleware\CheckIfUserIsDisabled::class,
        ]);
        $middleware->alias([
            'language' => \App\Http\Middleware\CheckLanguage::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
