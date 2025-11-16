<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\GpaImport;
use App\Exports\GpaExport;
use App\Models\DiemHocTap;
use App\Models\SinhVien;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
    // === ĐỔI MẬT KHẨU TÀI KHOẢN KHẢO THÍ ===
    public function changePassword(Request $request)
    {
        // 1. Validate dữ liệu
        $request->validate([
            'old_password' => ['required'],
            'new_password' => ['required', 'confirmed', 'min:6'],
        ], [], [
            'old_password' => 'Mật khẩu cũ',
            'new_password' => 'Mật khẩu mới',
        ]);

        // 2. Lấy MaTK từ session
        $user = session('user');
        $maTK = data_get($user, 'MaTK');

        if (!$maTK) {
            return back()->withErrors(['old_password' => 'Không tìm thấy tài khoản đăng nhập.']);
        }

        // 3. Lấy tài khoản
        $account = DB::table('BANG_TaiKhoan')->where('MaTK', $maTK)->first();
        if (!$account) {
            return back()->withErrors(['old_password' => 'Tài khoản không tồn tại trong hệ thống.']);
        }

        // 4. Kiểm tra mật khẩu cũ
        if (!Hash::check($request->old_password, $account->MatKhau)) {
            return back()->withErrors(['old_password' => 'Mật khẩu cũ không đúng.']);
        }

        // ❗❗ 5. NGĂN ĐẶT MẬT KHẨU MỚI GIỐNG MẬT KHẨU CŨ
        if (Hash::check($request->new_password, $account->MatKhau)) {
            return back()->withErrors([
                'new_password' => 'Mật khẩu mới không được trùng với mật khẩu cũ.'
            ]);
        }

        // 6. Cập nhật mật khẩu mới
        DB::table('BANG_TaiKhoan')
            ->where('MaTK', $maTK)
            ->update([
                'MatKhau' => Hash::make($request->new_password),
            ]);

        return back()->with('ok', 'Đã đổi mật khẩu thành công.');
    }
    public function gpaTemplate(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Hàng tiêu đề: MaSV  HocKy  NamHoc  DiemHT  XepLoai
        $headers = [
            'A1' => 'MaSV',
            'B1' => 'HocKy',
            'C1' => 'NamHoc',
            'D1' => 'DiemHT',
            'E1' => 'XepLoai',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // In đậm + chỉnh độ rộng cột
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(18);
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(14);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(16);

        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="mau_diem_hoc_tap.xlsx"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }
}
