<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        // ...
        'auth.session' => \App\Http\Middleware\AuthSession::class,
        'role'         => \App\Http\Middleware\EnsureRole::class,
    ];
}
?>