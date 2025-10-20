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
    // Login xử lý
    public function login(Request $r)
    {
        $r->validate([
            'TenDangNhap' => 'required',
            'MatKhau'     => 'required',
        ], [
            'TenDangNhap.required' => 'Vui lòng nhập tên đăng nhập.',
            'MatKhau.required'     => 'Vui lòng nhập mật khẩu.',
        ]);

        $user = DB::table('BANG_TaiKhoan')
            ->where('TenDangNhap', $r->TenDangNhap)
            ->first();

        if (!$user || !Hash::check($r->MatKhau, $user->MatKhau)) {
            // ✅ Trả lỗi có key + giữ lại input tên đăng nhập
            return back()
                ->withErrors(['login' => 'Tên đăng nhập hoặc mật khẩu không đúng.'])
                ->withInput($r->only('TenDangNhap'));
        }

        // Lưu session đăng nhập
        $r->session()->put('user', [
            'MaTK' => $user->MaTK,
            'name' => $user->TenDangNhap,
            'role' => $user->VaiTro,
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
    // Logout xử lý
    public function logout(Request $r)
    {
        $r->session()->forget('user');
        return redirect()->route('login.show')->with('ok', 'Đã đăng xuất.');
    }

    // ===== Quên / Đặt lại mật khẩu =====
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
        ], [
            'TenDangNhap.required' => 'Vui lòng nhập tên đăng nhập.',
            'Email.required'       => 'Vui lòng nhập email công tác.',
            'Email.email'          => 'Email không đúng định dạng.',
        ]);

        $u = DB::table('BANG_TaiKhoan')
            ->where('TenDangNhap', $r->TenDangNhap)
            ->where('Email', $r->Email)
            ->first();

        if (!$u) {
            // ✅ Trả lỗi có key, không cần giữ input (tùy bạn)
            return back()
                ->withErrors(['forgot' => 'Tên đăng nhập hoặc email không đúng.']);
        }

        // Thành công -> cho phép vào màn hình đặt lại
        $r->session()->put('reset_ok_user_id', $u->MaTK);

        return redirect()
            ->route('reset.show')
            ->with('ok', 'Xác thực thành công, vui lòng đặt mật khẩu mới.');
    }

    public function showReset(Request $r)
    {
        if (!$r->session()->has('reset_ok_user_id')) {
            return redirect()->route('forgot.show');
        }
        return view('auth.reset');
    }

    public function handleReset(Request $r)
    {
        if (!$r->session()->has('reset_ok_user_id')) {
            return redirect()->route('forgot.show');
        }

        $r->validate([
            // name="MatKhau" và name="MatKhau_confirmation" trong form
            'MatKhau' => 'required|min:6|confirmed',
        ], [
            'MatKhau.required'   => 'Vui lòng nhập mật khẩu mới.',
            'MatKhau.min'        => 'Mật khẩu phải từ 6 ký tự.',
            'MatKhau.confirmed'  => 'Xác nhận mật khẩu chưa khớp.',
        ]);

        DB::table('BANG_TaiKhoan')
            ->where('MaTK', $r->session()->get('reset_ok_user_id'))
            ->update(['MatKhau' => Hash::make($r->MatKhau)]);

        $r->session()->forget('reset_ok_user_id');

        return redirect()
            ->route('login.show')
            ->with('ok', 'Đổi mật khẩu thành công, hãy đăng nhập lại.');
    }
}
