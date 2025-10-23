<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnsureActiveAccount
{
    public function handle(Request $request, Closure $next)
    {
        $sess = $request->session()->get('user');

        // Chưa đăng nhập -> để middleware đăng nhập xử lý
        if (!$sess || empty($sess['MaTK'])) {
            return $next($request);
        }

        // Kiểm tra trạng thái mới nhất từ DB
        $u = DB::table('BANG_TaiKhoan')->select('TrangThai')->where('MaTK', $sess['MaTK'])->first();

        if (!$u || $u->TrangThai !== 'Active') {
            $request->session()->forget('user');
            $msg = match ($u->TrangThai ?? 'none') {
                'Locked'   => 'Tài khoản đã bị khóa.',
                'Inactive' => 'Tài khoản chưa được kích hoạt.',
                default    => 'Tài khoản không hợp lệ hoặc đã bị xóa.',
            };
            return redirect()->route('login.show')->withErrors($msg);
        }

        return $next($request);
    }
}
