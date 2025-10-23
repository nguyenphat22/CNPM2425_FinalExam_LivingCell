<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\GpaImport;
use App\Exports\GpaExport;
use App\Models\DiemHocTap;
use App\Models\SinhVien;

class KhaothiController extends Controller
{
    // === TRANG DANH SÁCH SINH VIÊN ===
    public function sinhVienIndex(Request $r)
    {
        $q = trim((string) $r->input('q'));

        // Lấy dữ liệu sinh viên từ bảng BANG_SinhVien
        $query = DB::table('BANG_SinhVien')
            ->select('MaSV', 'HoTen', 'NgaySinh', 'Khoa', 'Lop');

        if ($q !== '') {
            $query->where(function ($s) use ($q) {
                $s->where('MaSV', 'like', "%{$q}%")
                    ->orWhere('HoTen', 'like', "%{$q}%")
                    ->orWhere('Khoa', 'like', "%{$q}%")
                    ->orWhere('Lop', 'like', "%{$q}%");
            });
        }

        // Phân trang
        $data = $query->orderBy('MaSV')->paginate(10)->withQueryString();

        // Trả về view resources/views/khaothi/sinhvien.blade.php
        return view('khaothi.sinhvien', compact('data', 'q'));
    }

    // === TRANG QUẢN LÝ ĐIỂM HỌC TẬP ===
    public function gpaIndex(Request $r)
    {
        $hk = (int) $r->input('hk', 1);
        $nh = (string) $r->input('nh', '2024-2025');
        $q  = trim((string) $r->input('q', ''));

        $query = DB::table('BANG_SinhVien as sv')
            ->leftJoin('BANG_DiemHocTap as gpa', function ($j) use ($hk, $nh) {
                $j->on('sv.MaSV', '=', 'gpa.MaSV')
                    ->where('gpa.HocKy', $hk)
                    ->where('gpa.NamHoc', $nh);
            })
            ->select(
                'sv.MaSV',
                'sv.HoTen',
                'gpa.HocKy',
                'gpa.NamHoc',
                DB::raw('gpa.DiemHe4 as DiemHT'),   // ⬅️ alias
                'gpa.XepLoai'
            );

        if ($q !== '') {
            $query->where(function ($s) use ($q) {
                $s->where('sv.MaSV', 'like', "%{$q}%")
                    ->orWhere('sv.HoTen', 'like', "%{$q}%");
            });
        }

        $data = $query->orderBy('sv.MaSV')->paginate(10)->withQueryString();

        return view('khaothi.diemhoc', compact('data', 'hk', 'nh', 'q'));
    }

    public function gpaUpdate(Request $r)
    {
        $r->validate([
            'MaSV'    => 'required|string|exists:BANG_SinhVien,MaSV',
            'HocKy'   => 'required|integer|min:1|max:3',
            'NamHoc'  => 'required|string|max:9',
            'DiemHT'  => 'required|numeric|min:0|max:4', // nếu thang 4, sửa theo thang điểm bạn dùng
            'XepLoai' => 'nullable|string|max:20',
        ]);

        DB::table('BANG_DiemHocTap')->updateOrInsert(
            ['MaSV' => $r->MaSV, 'HocKy' => $r->HocKy, 'NamHoc' => $r->NamHoc],
            ['DiemHe4' => $r->DiemHT, 'XepLoai' => $r->XepLoai]   // ⬅️ sửa DiemHe4
        );

        return back()->with('ok', 'Đã lưu điểm học tập.');
    }

    public function gpaDelete(Request $r)
    {
        $r->validate([
            'MaSV'   => 'required|string|exists:BANG_SinhVien,MaSV',
            'HocKy'  => 'required|integer',
            'NamHoc' => 'required|string'
        ]);

        DB::table('BANG_DiemHocTap')
            ->where('MaSV', $r->MaSV)
            ->where('HocKy', $r->HocKy)
            ->where('NamHoc', $r->NamHoc)
            ->delete();

        return back()->with('ok', 'Đã xóa điểm học tập.');
    }

    public function gpaImport(Request $r)
    {
        $r->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:20480',
        ], [], ['file' => 'Tệp Excel']);

        $import = new GpaImport();
        try {
            Excel::import($import, $r->file('file'));
        } catch (\Throwable $e) {
            return back()->withErrors(['file' => 'Import lỗi: ' . $e->getMessage()]);
        }

        $msg = "Nhập điểm học tập thành công. Thêm: {$import->getInserted()}, Cập nhật: {$import->getUpdated()}.";
        if ($import->failures()->isNotEmpty()) {
            return back()->with('ok', $msg)->with('failures', $import->failures());
        }
        return back()->with('ok', $msg);
    }

    public function gpaExport(Request $r)
    {
        $hk = (int) $r->input('hk', 1);
        $nh = (string) $r->input('nh', '2024-2025');
        $q  = $r->input('q');

        return Excel::download(new GpaExport($hk, $nh, $q), "GPA_HK{$hk}_{$nh}.xlsx");
    }
}
