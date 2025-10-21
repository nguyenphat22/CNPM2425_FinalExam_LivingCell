<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureLoggedIn;
use App\Http\Middleware\RoleMiddleware; // 👉 nhớ import thêm dòng này

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth.session' => EnsureLoggedIn::class,
            'role' => RoleMiddleware::class, // 👉 thêm middleware phân quyền ở đây
        ]);

        // Nếu muốn thêm global middleware, thêm ở đây:
        // $middleware->use([YourGlobalMiddleware::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
