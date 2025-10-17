<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin()    { return view('dash.admin'); }
    public function sinhvien(Request $r)
    {
        $user = $r->session()->get('user');

        // Demo: lấy hồ sơ SV nếu có liên kết MaTK trong bảng sinh viên
        $sv = DB::table('BANG_SinhVien')->where('MaTK', $user['MaTK'] ?? null)->first();

        // GPA / DRL / NTN demo (lấy gần nhất)
        $gpa = DB::table('BANG_DiemHocTap')->where('MaSV', $sv->MaSV ?? null)->orderByDesc('NamHoc')->orderByDesc('HocKy')->first();
        $drl = DB::table('BANG_DiemRenLuyen')->where('MaSV', $sv->MaSV ?? null)->orderByDesc('NamHoc')->orderByDesc('HocKy')->first();
        $ntn = DB::table('BANG_NgayTinhNguyen')->selectRaw('COALESCE(SUM(SoNgayTN),0) as tong')->where('MaSV', $sv->MaSV ?? null)->first();

        // Gợi ý danh hiệu đơn giản (demo)
        $goiY = null;
        if ($gpa && $drl && $ntn) {
            $goiY = "GPA {$gpa->DiemHe4} ; DRL {$drl->DiemRL}; NTN {$ntn->tong} ngày";
        }

        return view('dash.sinhvien', compact('sv','gpa','drl','ntn','goiY'));
    }
    // app/Http/Controllers/DashboardController.php
public function ctct()
{
    // chuyển thẳng tới trang CTCT -> Danh sách sinh viên
    return redirect()->route('ctct.sinhvien.index');
}
    public function khaothi()  { return redirect()->route('khaothi.sinhvien.index'); }
    public function doan()     { return redirect()->route('doan.index'); }
}
