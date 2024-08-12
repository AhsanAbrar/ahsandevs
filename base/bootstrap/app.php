<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectTo(
            users: '/'
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            $redirectableExceptions = [
                \Illuminate\Auth\AuthenticationException::class,
                \Illuminate\Validation\ValidationException::class,
            ];

            return $request->hasHeader('X-AhsanDevs-Error-HTML')
                ? in_array(get_class($e), $redirectableExceptions, true)
                : $request->expectsJson();
        });
    })->create();
