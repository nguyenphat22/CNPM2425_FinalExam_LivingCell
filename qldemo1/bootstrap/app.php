<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Middleware của bạn
use App\Http\Middleware\EnsureLoggedIn;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\EnsureActiveAccount; // ✅ kiểm tra file tồn tại: app/Http/Middleware/EnsureActiveAccount.php

return Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {
        // Đăng ký alias cho middleware
        $middleware->alias([
            'auth.session' => EnsureLoggedIn::class,
            'role'         => RoleMiddleware::class,
            'active'       => EnsureActiveAccount::class, // ✅ THÊM DÒNG NÀY
        ]);

        // Nếu muốn middleware global:
        // $middleware->use([ ... ]);
    })
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
