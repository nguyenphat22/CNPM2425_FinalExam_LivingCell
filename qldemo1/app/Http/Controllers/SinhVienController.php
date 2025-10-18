<?php
// app/Http/Controllers/SinhVienController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SinhVienController extends Controller
{
    public function index(Request $r)
    {
        $matk = session('auth.MaTK') ?? session('user.MaTK');

        if (!$matk) {
            return view('sinhvien.index', [
                'sv'       => null,
                'gpaVal'   => null,
                'drlVal'   => null,
                'ngaySinh' => null,
                'ntnTong'  => 0,
                'awds'     => collect(),
                'goiY'     => null,
            ]);
        }

        $sv = DB::table('BANG_SinhVien')->where('MaTK', $matk)->first();
        if (!$sv) {
            return view('sinhvien.index', [
                'sv'       => null,
                'gpaVal'   => null,
                'drlVal'   => null,
                'ngaySinh' => null,
                'ntnTong'  => 0,
                'awds'     => collect(),
                'goiY'     => null,
            ]);
        }

        $masv = $sv->MaSV;

        $gpa = DB::table('BANG_DiemHocTap')
            ->where('MaSV', $masv)
            ->orderByDesc('NamHoc')
            ->orderByDesc('HocKy')
            ->first();
        $gpaVal = $gpa->DiemHe4 ?? null;

        $drl = DB::table('BANG_DiemRenLuyen')
            ->where('MaSV', $masv)
            ->orderByDesc('NamHoc')
            ->orderByDesc('HocKy')
            ->first();
        $drlVal = $drl->DiemRL ?? null;

        // Tổng số ngày tình nguyện đã duyệt (đã dùng đúng cột SoNgayTN & TrangThaiDuyet)
        $ntnTong = DB::table('BANG_NgayTinhNguyen')
            ->where('MaSV', $masv)
            ->where('TrangThaiDuyet', 'DaDuyet')
            ->selectRaw('COALESCE(SUM(SoNgayTN), 0) AS tong')
            ->value('tong') ?? 0;

        // Định dạng ngày sinh cho view (tùy có/không)
        $ngaySinh = null;
        if (!empty($sv->NgaySinh)) {
            try { $ngaySinh = Carbon::parse($sv->NgaySinh)->format('Y-m-d'); }
            catch (\Throwable $e) { $ngaySinh = $sv->NgaySinh; }
        }

        // Nếu không dùng thì để trống
        $awds = collect();
        $goiY = null;

        return view('sinhvien.index', [
            'sv'       => $sv,
            'gpaVal'   => $gpaVal,
            'drlVal'   => $drlVal,
            'ngaySinh' => $ngaySinh,
            'ntnTong'  => (int)$ntnTong,
            'awds'     => $awds,
            'goiY'     => $goiY,
        ]);
    }
}