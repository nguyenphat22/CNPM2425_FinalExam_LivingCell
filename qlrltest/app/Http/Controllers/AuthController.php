<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\TaiKhoan;

class AuthController extends Controller
{
    /* ---- VIEW ---- */
    public function showLogin()  { return view('auth.login'); }
    public function showForgot() { return view('auth.forgot'); }

    /* ---- LOGIN ---- */
    public function login(Request $req)
    {
        $req->validate(['username'=>'required','password'=>'required']);

        $user = TaiKhoan::where('TenDangNhap',$req->username)
            ->where('TrangThai','Active')->first();

        if (!$user || !Hash::check($req->password, $user->MatKhau)) {
            return back()->withErrors(['username'=>'Sai tài khoản hoặc mật khẩu']);
        }

        $sv = DB::table('BANG_SinhVien')->where('MaTK',$user->MaTK)->first();
        $req->session()->put('user', [
            'MaTK'=>$user->MaTK,'TenDangNhap'=>$user->TenDangNhap,
            'VaiTro'=>$user->VaiTro,'MaSV'=>$sv->MaSV ?? null
        ]);

        return redirect($this->urlByRole($user->VaiTro));
    }

    public function logout(Request $req)
    {
        $req->session()->flush();
        return redirect()->route('login.form');
    }

    /* ---- FORGOT / RESET ---- */
    public function verifyForgot(Request $req)
    {
        $req->validate(['username'=>'required','email'=>'required|email']);
        $user = TaiKhoan::where('TenDangNhap',$req->username)
                ->where('Email',$req->email)->first();
        if (!$user) return back()->withErrors(['username'=>'Không khớp tên đăng nhập/email']);

        $token = bin2hex(random_bytes(8));
        $req->session()->put('reset_token', ['MaTK'=>$user->MaTK,'token'=>$token]);
        return back()->with('token',$token);
    }

    public function resetPassword(Request $req)
    {
        $req->validate([
            'token'=>'required','password'=>'required|min:6|confirmed'
        ]);
        $data = $req->session()->get('reset_token');
        if (!$data || $data['token'] !== $req->token)
            return back()->withErrors(['token'=>'Token không hợp lệ']);

        TaiKhoan::where('MaTK',$data['MaTK'])
            ->update(['MatKhau'=>Hash::make($req->password)]);
        $req->session()->forget('reset_token');
        return redirect()->route('login.form')->with('ok','Đổi mật khẩu thành công');
    }

    /* ---- helper ---- */
    private function urlByRole(string $role): string
    {
        return match($role){
            'Admin'      => route('admin.taikhoan.index'),
            'SinhVien'   => route('sv.dashboard'),
            'CTCTHSSV'   => route('ctct.sv.index'),
            'KhaoThi'    => route('kt.sv.index'),
            'DoanTruong' => route('doan.kt.index'),
            default      => route('login.form'),
        };
    }
}
