<?php
// app/Http/Controllers/CtctController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SinhVienImport;
use App\Imports\DrlImport;
use App\Exports\DrlExport;

class CtctController extends Controller
{
    /**
     * Danh sách sinh viên + tìm kiếm + phân trang
     */
    public function sinhvienIndex(Request $r)
    {
        $q = trim((string) $r->input('q'));

        $query = DB::table('BANG_SinhVien')
            ->select('MaSV','HoTen','NgaySinh','Khoa','Lop');

        if ($q !== '') {
            $query->where(function($s) use ($q){
                $s->where('MaSV','like',"%{$q}%")
                  ->orWhere('HoTen','like',"%{$q}%")
                  ->orWhere('Khoa','like',"%{$q}%")
                  ->orWhere('Lop','like',"%{$q}%");
            });
        }

        // KHÔNG orderBy('id') vì không tồn tại; dùng MaSV
        $data = $query->orderBy('MaSV')->paginate(10)->withQueryString();

        return view('ctct.sinhvien', compact('data','q'));
    }

    /**
     * Thêm sinh viên mới
     */
    public function svStore(Request $r)
    {
        $attrs = [
            'MaSV'     => 'Mã sinh viên',
            'HoTen'    => 'Họ và Tên',
            'NgaySinh' => 'Ngày sinh',
            'Khoa'     => 'Khoa',
            'Lop'      => 'Lớp',
        ];

        $data = $r->validate([
            'MaSV'     => 'required|string|max:20|unique:BANG_SinhVien,MaSV',
            'HoTen'    => 'required|string|max:100',
            'NgaySinh' => 'required|date',
            'Khoa'     => 'nullable|string|max:100',
            'Lop'      => 'nullable|string|max:50',
        ], [], $attrs);

        DB::table('BANG_SinhVien')->insert($data);

        return back()->with('ok','Đã thêm sinh viên.');
    }

    /**
     * Cập nhật thông tin sinh viên (không đổi MaSV)
     */
    public function svUpdate(Request $r)
    {
        $attrs = [
            'MaSV'     => 'Mã sinh viên',
            'HoTen'    => 'Họ và Tên',
            'NgaySinh' => 'Ngày sinh',
            'Khoa'     => 'Khoa',
            'Lop'      => 'Lớp',
        ];

        // MaSV phải tồn tại; các cột còn lại required
        $r->validate([
            'MaSV'     => 'required|string|exists:BANG_SinhVien,MaSV',
            'HoTen'    => 'required|string|max:100',
            'NgaySinh' => 'required|date',
            'Khoa'     => 'nullable|string|max:100',
            'Lop'      => 'nullable|string|max:50',
        ], [], $attrs);

        DB::table('BANG_SinhVien')
            ->where('MaSV', $r->MaSV)
            ->update([
                'HoTen'    => $r->HoTen,
                'NgaySinh' => $r->NgaySinh,
                'Khoa'     => $r->Khoa,
                'Lop'      => $r->Lop,
            ]);

        return back()->with('ok', 'Đã cập nhật sinh viên.');
    }

    /**
     * Xóa sinh viên theo MaSV
     */
    public function svDelete(Request $r)
    {
        $r->validate([
            'MaSV' => 'required|string|exists:BANG_SinhVien,MaSV',
        ], [], ['MaSV' => 'Mã sinh viên']);

        DB::table('BANG_SinhVien')->where('MaSV', $r->MaSV)->delete();

        return back()->with('ok','Đã xóa sinh viên.');
    }

    public function svImport(Request $r)
{
    $r->validate([
        'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
    ], [], ['file' => 'Tệp Excel']);

    $import = new SinhVienImport();

    try {
        Excel::import($import, $r->file('file'));
    } catch (\Throwable $e) {
        return back()->withErrors(['file' => 'Import lỗi: '.$e->getMessage()]);
    }

    $msg = "Nhập file thành công. Thêm mới: {$import->getInserted()}, Cập nhật: {$import->getUpdated()}.";
    if ($import->failures()->isNotEmpty()) {
        return back()->with('ok', $msg)->with('failures', $import->failures());
    }
    return back()->with('ok', $msg);
}
    /**
     * Trang quản lý điểm rèn luyện (placeholder)
     */

    public function drlIndex(Request $r)
{
    $hk = (int) $r->input('hk', 1);
    $nh = (string) $r->input('nh', '2024-2025');
    $q  = trim((string)$r->input('q', ''));

    $query = DB::table('BANG_SinhVien as sv')
        ->leftJoin('BANG_DiemRenLuyen as drl', function($j) use ($hk, $nh) {
            $j->on('sv.MaSV','=','drl.MaSV')
              ->where('drl.HocKy', $hk)
              ->where('drl.NamHoc', $nh);
        })
        ->select(
            'sv.MaSV',
            'sv.HoTen',
            'drl.HocKy',
            'drl.NamHoc',
            'drl.DiemRL',
            'drl.XepLoai'
        );

    if ($q !== '') {
        $query->where(function($s) use ($q){
            $s->where('sv.MaSV','like',"%{$q}%")
              ->orWhere('sv.HoTen','like',"%{$q}%");
        });
    }

    $data = $query->orderBy('sv.MaSV')->paginate(10)->withQueryString();

    return view('ctct.drl', compact('data','hk','nh','q'));
}
public function drlImport(\Illuminate\Http\Request $r)
{
    $r->validate([
        'file' => 'required|file|mimes:xlsx,xls,csv|max:20480',
    ], [], ['file' => 'Tệp Excel']);

    $import = new DrlImport();

    try {
        Excel::import($import, $r->file('file'));
    } catch (\Throwable $e) {
        return back()->withErrors(['file' => 'Import lỗi: '.$e->getMessage()]);
    }

    $msg = "Nhập DRL thành công. Thêm mới: {$import->getInserted()}, Cập nhật: {$import->getUpdated()}.";
    if ($import->failures()->isNotEmpty()) {
        return back()->with('ok', $msg)->with('failures', $import->failures());
    }
    return back()->with('ok', $msg);
}
public function drlUpdate(Request $r)
{
    $r->validate([
        'MaSV'    => 'required|string|exists:BANG_SinhVien,MaSV',
        'HocKy'   => 'required|integer|min:1|max:3',
        'NamHoc'  => 'required|string|max:9',
        'DiemRL'  => 'required|integer|min:0|max:100',
        'XepLoai' => 'nullable|string|max:20',
    ], [], [
        'MaSV'=>'MSSV','HocKy'=>'Học kỳ','NamHoc'=>'Năm học',
        'DiemRL'=>'Điểm rèn luyện','XepLoai'=>'Xếp loại'
    ]);

    DB::table('BANG_DiemRenLuyen')->updateOrInsert(
        ['MaSV'=>$r->MaSV,'HocKy'=>$r->HocKy,'NamHoc'=>$r->NamHoc],
        ['DiemRL'=>$r->DiemRL,'XepLoai'=>$r->XepLoai]
    );

    return back()->with('ok','Đã lưu điểm rèn luyện.');
}
public function drlExport(\Illuminate\Http\Request $r)
{
    $hk = (int) $r->input('hk', 1);
    $nh = (string) $r->input('nh', '2024-2025');
    $q  = $r->input('q');

    return Excel::download(new DrlExport($hk, $nh, $q), "DRL_HK{$hk}_{$nh}.xlsx");
}

}
