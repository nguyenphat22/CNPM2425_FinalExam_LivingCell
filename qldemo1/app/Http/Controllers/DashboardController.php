<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dashboard\SVModel;
use App\Models\Dashboard\GpaModel;
use App\Models\Dashboard\DrlModel;
use App\Models\Dashboard\NtnModel;

class DashboardController extends Controller
{
    public function admin()
    {
        return view('dash.admin');
    }

    public function sinhvien(Request $r)
    {
        $user = $r->session()->get('user');

        // Lấy hồ sơ sinh viên
        $sv = SVModel::where('MaTK', $user['MaTK'] ?? null)->first();

        // GPA gần nhất
        $gpa = $sv
            ? GpaModel::where('MaSV', $sv->MaSV)
                ->orderByDesc('NamHoc')
                ->orderByDesc('HocKy')
                ->first()
            : null;

        // DRL gần nhất
        $drl = $sv
            ? DrlModel::where('MaSV', $sv->MaSV)
                ->orderByDesc('NamHoc')
                ->orderByDesc('HocKy')
                ->first()
            : null;

        // Tổng NTN
        $ntn = $sv
            ? NtnModel::where('MaSV', $sv->MaSV)
                ->selectRaw('COALESCE(SUM(SoNgayTN), 0) AS tong')
                ->first()
            : null;

        // Gợi ý danh hiệu
        $goiY = ($gpa && $drl && $ntn)
            ? "GPA {$gpa->DiemHe4} ; DRL {$drl->DiemRL}; NTN {$ntn->tong} ngày"
            : null;

        return view('dash.sinhvien', compact('sv', 'gpa', 'drl', 'ntn', 'goiY'));
    }

    public function ctct()
    {
        return redirect()->route('ctct.sinhvien.index');
    }

    public function khaothi()
    {
        return redirect()->route('khaothi.sinhvien.index');
    }

    public function doan()
    {
        return redirect()->route('doan.index');
    }
}
