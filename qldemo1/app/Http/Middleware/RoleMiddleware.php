<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    // Dùng: ->middleware('role:Admin') hoặc ->middleware('role:CTCTHSSV')
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->session()->get('user');
        $role = $user['role'] ?? null;

        if (!$role || !in_array($role, $roles, true)) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
}
