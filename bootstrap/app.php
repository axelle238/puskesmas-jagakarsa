<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CekPeran;
use App\Http\Middleware\CekKeamananSistem;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register Middleware Alias
        $middleware->alias([
            'peran' => CekPeran::class,
            'keamanan' => CekKeamananSistem::class,
        ]);
        
        // Middleware Global (jika perlu)
        // $middleware->append(CekKeamananSistem::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();