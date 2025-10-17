<?php
// app/Http/Controllers/CtctController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SinhVienImport;

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

    public function drlIndex()
    {
        return view('ctct.drl');
    }
}
