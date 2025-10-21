<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $r)
    {
        $r->validate([
            'TenDangNhap' => 'required',
            'MatKhau'     => 'required',
        ]);

        $user = DB::table('BANG_TaiKhoan')->where('TenDangNhap', $r->TenDangNhap)->first();

        if (!$user || !Hash::check($r->MatKhau, $user->MatKhau)) {
            return back()->withErrors('Tên đăng nhập hoặc mật khẩu không đúng.');
        }

        // Lưu session
        $r->session()->put('user', [
            'MaTK'   => $user->MaTK,
            'name'   => $user->TenDangNhap,
            'role'   => $user->VaiTro,
        ]);

        // Điều hướng theo vai trò
        return match ($user->VaiTro) {
            'Admin'      => redirect()->route('admin.home'),
            'SinhVien'   => redirect()->route('sv.home'),
            'CTCTHSSV'   => redirect()->route('ctct.home'),
            'KhaoThi'    => redirect()->route('khaothi.home'),
            'DoanTruong' => redirect()->route('doan.home'),
            default      => redirect()->route('login.show'),
        };
    }

    public function logout(Request $r)
    {
        $r->session()->forget('user');
        return redirect()->route('login.show')->with('ok', 'Đã đăng xuất.');
    }

    public function showForgot()
    {
        return view('auth.forgot');
    }

    // Bước 1: xác thực tên đăng nhập + email
    public function handleForgot(Request $r)
    {
        $r->validate([
            'TenDangNhap' => 'required',
            'Email'       => 'required|email',
        ]);

        $u = DB::table('BANG_TaiKhoan')
            ->where('TenDangNhap', $r->TenDangNhap)
            ->where('Email', $r->Email)
            ->first();

        if (!$u) {
            // KHÔNG dùng withInput() => không giữ lại dữ liệu cũ
            return back()->withErrors(['login' => 'Tên đăng nhập hoặc mật khẩu không đúng.']);
        }

        // Nếu xác thực thành công
        $r->session()->put('reset_ok_user_id', $u->MaTK);
        return redirect()->route('reset.show')
            ->with('ok', 'Xác thực thành công, vui lòng đặt mật khẩu mới.');
    }


    public function showReset(Request $r)
    {
        if (!$r->session()->has('reset_ok_user_id')) return redirect()->route('forgot.show');
        return view('auth.reset');
    }

    public function handleReset(Request $r)
    {
        if (!$r->session()->has('reset_ok_user_id')) return redirect()->route('forgot.show');

        $r->validate([
            'MatKhau' => 'required|min:6|confirmed', // dùng MatKhau + MatKhau_confirmation
        ]);

        DB::table('BANG_TaiKhoan')
            ->where('MaTK', $r->session()->get('reset_ok_user_id'))
            ->update(['MatKhau' => Hash::make($r->MatKhau)]);

        $r->session()->forget('reset_ok_user_id');
        return redirect()->route('login.show')->with('ok', 'Đổi mật khẩu thành công, hãy đăng nhập lại.');
    }
}
