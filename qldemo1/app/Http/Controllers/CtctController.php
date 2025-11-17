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
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class CtctController extends Controller
{
    /**
     * Danh sách sinh viên + tìm kiếm + phân trang
     */
    public function sinhVienIndex(Request $r)
    {
        $q = trim((string) $r->input('q'));

        $query = DB::table('BANG_SinhVien')
            ->select('MaSV', 'HoTen', 'NgaySinh', 'Khoa', 'Lop', 'MaTK'); 

        if ($q !== '') {
            $query->where(function ($s) use ($q) {
                $s->where('MaSV', 'like', "%{$q}%")
                    ->orWhere('HoTen', 'like', "%{$q}%")
                    ->orWhere('Khoa', 'like', "%{$q}%")
                    ->orWhere('Lop', 'like', "%{$q}%")
                    ->orWhere('MaTK', 'like', "%{$q}%"); 
            });
        }

        $data = $query->orderBy('MaSV')->paginate(10)->withQueryString();

        return view('ctct.sinhvien', compact('data', 'q'));
    }


    /**
     * Thêm sinh viên mới
     */
    public function svStore(Request $r)
    {
        $r->validate([
            'MaSV'     => ['required', 'string', 'min:13', 'max:20', Rule::unique('BANG_SinhVien', 'MaSV')],
            'HoTen'    => ['required', 'string', 'max:100'],
            'NgaySinh' => ['required', 'date'],
            'Khoa'     => ['nullable', 'string', 'max:100'],
            'Lop'      => ['nullable', 'string', 'max:50'],
            'MaTK'     => [
                'nullable',
                'integer',
                Rule::exists('BANG_TaiKhoan', 'MaTK')->where(fn($q) => $q->where('VaiTro', 'SinhVien')),
                Rule::unique('BANG_SinhVien', 'MaTK'), 
            ],
        ], [], [
            'MaSV' => 'MSSV',
            'HoTen' => 'Họ và tên',
            'NgaySinh' => 'Ngày sinh',
            'MaTK' => 'Mã tài khoản'
        ]);

        DB::table('BANG_SinhVien')->insert([
            'MaSV'     => $r->MaSV,
            'HoTen'    => $r->HoTen,
            'NgaySinh' => $r->NgaySinh,
            'Khoa'     => $r->Khoa,
            'Lop'      => $r->Lop,
            'MaTK'     => $r->MaTK ?: null,
        ]);

        return back()->with('ok', 'Đã thêm sinh viên.');
    }

    /**
     * Cập nhật thông tin sinh viên (không đổi MaSV)
     */
    public function svUpdate(Request $r)
    {
        $r->validate([
            'MaSV'     => ['required', 'min:13', Rule::exists('BANG_SinhVien', 'MaSV')],
            'HoTen'    => ['required', 'string', 'max:100'],
            'NgaySinh' => ['required', 'date'],
            'Khoa'     => ['nullable', 'string', 'max:100'],
            'Lop'      => ['nullable', 'string', 'max:50'],
            'MaTK'     => [
                'nullable',
                'integer',
                Rule::exists('BANG_TaiKhoan', 'MaTK')->where(fn($q) => $q->where('VaiTro', 'SinhVien')),
                Rule::unique('BANG_SinhVien', 'MaTK')->ignore($r->MaSV, 'MaSV'),
            ],
        ], [], [
            'MaSV' => 'MSSV',
            'HoTen' => 'Họ và tên',
            'NgaySinh' => 'Ngày sinh',
            'MaTK' => 'Mã tài khoản'
        ]);

        DB::table('BANG_SinhVien')
            ->where('MaSV', $r->MaSV)
            ->update([
                'HoTen'    => $r->HoTen,
                'NgaySinh' => $r->NgaySinh,
                'Khoa'     => $r->Khoa,
                'Lop'      => $r->Lop,
                'MaTK'     => $r->MaTK ?: null,
            ]);

        return back()->with('ok', 'Đã cập nhật sinh viên.');
    }

    /**
     * Xóa sinh viên theo MaSV
     */
    public function svDelete(Request $r)
    {
        $r->validate([
            'MaSV' => 'required|string|min:13|exists:BANG_SinhVien,MaSV',
        ], [], ['MaSV' => 'Mã sinh viên']);

        DB::table('BANG_SinhVien')->where('MaSV', $r->MaSV)->delete();

        return back()->with('ok', 'Đã xóa sinh viên.');
    }

    public function svImport(Request $r)
    {
        $r->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [], ['file' => 'Tệp Excel']);

        $inserted = 0;
        $updated  = 0;
        $failures = collect();

        try {
            Excel::import(
                new class($inserted, $updated, $failures) implements ToCollection, WithHeadingRow {
                    private $inserted;
                    private $updated;
                    private $failures;

                    public function __construct(&$inserted, &$updated, &$failures)
                    {
                        $this->inserted = &$inserted;
                        $this->updated  = &$updated;
                        $this->failures = &$failures;
                    }

                    public function collection(Collection $rows)
                    {
                        foreach ($rows as $idx => $row) {
                            // Lấy theo header (không phân biệt hoa thường)
                            $maSV  = trim((string)($row['masv']     ?? ''));
                            $hoTen = trim((string)($row['hoten']    ?? ''));
                            $ngay  = trim((string)($row['ngaysinh'] ?? ''));
                            $rawNgay = $row['ngaysinh'] ?? null;
                            $khoa  = trim((string)($row['khoa']     ?? ''));
                            $lop   = trim((string)($row['lop']      ?? ''));
                            $maTK  = $row['matk'] ?? null;

                            // Dòng hiển thị = index + 2 (vì có tiêu đề)
                            $line = ((int) $idx) + 2;

                            if ($maSV === '' || $hoTen === '') {
                                $this->failures->push("Dòng {$line}: thiếu MSSV hoặc HoTen.");
                                continue;
                            }
                            if (mb_strlen($maSV) < 13) {
                                $this->failures->push("Dòng {$line}: MSSV phải có tối thiểu 13 ký tự.");
                                continue;
                            }

                            // Chuẩn hóa/kiểm tra MaTK (nếu có)
                            if ($maTK !== null && $maTK !== '') {
                                if (!ctype_digit((string)$maTK)) {
                                    $this->failures->push("Dòng {$line}: MaTK phải là số nguyên.");
                                    continue;
                                }


                                // Tài khoản tồn tại & là SinhVien
                                $ok = DB::table('BANG_TaiKhoan')
                                    ->where('MaTK', $maTK)
                                    ->where('VaiTro', 'SinhVien')
                                    ->exists();

                                if (!$ok) {
                                    $this->failures->push("Dòng {$line}: MaTK {$maTK} không tồn tại hoặc không phải tài khoản SinhVien.");
                                    continue;
                                }

                                // Không bị gán cho SV khác
                                $used = DB::table('BANG_SinhVien')
                                    ->where('MaTK', $maTK)
                                    ->where('MaSV', '<>', $maSV)
                                    ->exists();

                                if ($used) {
                                    $this->failures->push("Dòng {$line}: MaTK {$maTK} đã gán cho sinh viên khác.");
                                    continue;
                                }
                            }

                            // ===== Chuẩn hoá NgaySinh =====
$ngaySinh = null;

if ($rawNgay !== null && $rawNgay !== '') {

    // 1) Nếu là DateTime (thường khi dùng Excel date)
    if ($rawNgay instanceof \DateTimeInterface) {
        $ngaySinh = Carbon::instance($rawNgay)->format('Y-m-d');

    // 2) Nếu là số serial của Excel
    } elseif (is_numeric($rawNgay)) {
        try {
            $ngaySinh = ExcelDate::excelToDateTimeObject($rawNgay)->format('Y-m-d');
        } catch (\Throwable $e) {
            $ngaySinh = null;
        }

    // 3) Nếu là chuỗi (29/12/2005, 2005-12-29, ...)
    } else {
        $ngay = trim((string)$rawNgay);
        if ($ngay !== '') {
            try {
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $ngay)) {
                    // yyyy-mm-dd
                    $ngaySinh = $ngay;
                } elseif (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $ngay)) {
                    // dd/mm/yyyy
                    $ngaySinh = Carbon::createFromFormat('d/m/Y', $ngay)->format('Y-m-d');
                } elseif (preg_match('/^\d{2}-\d{2}-\d{4}$/', $ngay)) {
                    // dd-mm-yyyy
                    $ngaySinh = Carbon::createFromFormat('d-m-Y', $ngay)->format('Y-m-d');
                } else {
                    // Cho Carbon tự đoán
                    $ngaySinh = Carbon::parse($ngay)->format('Y-m-d');
                }
            } catch (\Throwable $e) {
                $ngaySinh = null;
            }
        }
    }
}

// Nếu vẫn không parse được → báo lỗi, bỏ qua dòng để khỏi lỗi SQL
if (!$ngaySinh) {
    $this->failures->push("Dòng {$line}: NgaySinh không hợp lệ hoặc để trống.");
    continue;
}
                            $exists = DB::table('BANG_SinhVien')->where('MaSV', $maSV)->exists();

                            DB::table('BANG_SinhVien')->updateOrInsert(
                                ['MaSV' => $maSV],
                                [
                                    'HoTen'    => $hoTen,
                                    'NgaySinh' => $ngaySinh,
                                    'Khoa'     => $khoa,
                                    'Lop'      => $lop,
                                    'MaTK'     => ($maTK === '' ? null : $maTK),
                                ]
                            );

                            $exists ? $this->updated++ : $this->inserted++;
                        }
                    }
                },
                $r->file('file')
            );
        } catch (\Throwable $e) {
            return back()->withErrors(['file' => 'Import lỗi: ' . $e->getMessage()]);
        }

        $msg = "Nhập file thành công. Thêm mới: {$inserted}, Cập nhật: {$updated}.";
        return back()->with('ok', $msg)->with('failures', $failures);
    }
    public function changePassword(Request $r)
{
    $matk = session('auth.MaTK') ?? session('user.MaTK');

    if (!$matk) {
        return back()->withErrors(['old_password' => 'Không xác định tài khoản.']);
    }

    $r->validate([
        'old_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ], [
        'old_password.required' => 'Vui lòng nhập mật khẩu cũ.',
        'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
        'new_password.min' => 'Mật khẩu mới phải >= 6 ký tự.',
        'new_password.confirmed' => 'Mật khẩu xác nhận không khớp.',
    ]);

    $acc = DB::table('BANG_TaiKhoan')->where('MaTK', $matk)->first();

    if (!$acc || !Hash::check($r->old_password, $acc->MatKhau)) {
        return back()->withErrors(['old_password' => 'Mật khẩu cũ không đúng.']);
    }

    DB::table('BANG_TaiKhoan')
        ->where('MaTK', $matk)
        ->update(['MatKhau' => Hash::make($r->new_password)]);

    return back()->with('ok', 'Đổi mật khẩu thành công!');
}
public function svTemplate(): StreamedResponse
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Tiêu đề cột
    $headers = ['A1' => 'MaSV', 'B1' => 'HoTen', 'C1' => 'NgaySinh',
                'D1' => 'Khoa', 'E1' => 'Lop', 'F1' => 'MaTK'];

    foreach ($headers as $cell => $value) {
        $sheet->setCellValue($cell, $value);
    }

    // In đậm + chỉnh rộng cột
    $sheet->getStyle('A1:F1')->getFont()->setBold(true);
    $sheet->getColumnDimension('A')->setWidth(18);
    $sheet->getColumnDimension('B')->setWidth(28);
    $sheet->getColumnDimension('C')->setWidth(16);
    $sheet->getColumnDimension('D')->setWidth(22);
    $sheet->getColumnDimension('E')->setWidth(16);
    $sheet->getColumnDimension('F')->setWidth(12);

    $fileName = 'mau_sinhvien.xlsx';
    $writer   = new Xlsx($spreadsheet);

    return new StreamedResponse(function () use ($writer) {
        $writer->save('php://output');
    }, 200, [
        'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Content-Disposition' => "attachment; filename=\"mau_sinhvien.xlsx\"",
        'Cache-Control'       => 'max-age=0',
    ]);
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
            ->leftJoin('BANG_DiemRenLuyen as drl', function ($j) use ($hk, $nh) {
                $j->on('sv.MaSV', '=', 'drl.MaSV')
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
            $query->where(function ($s) use ($q) {
                $s->where('sv.MaSV', 'like', "%{$q}%")
                    ->orWhere('sv.HoTen', 'like', "%{$q}%");
            });
        }

        $data = $query->orderBy('sv.MaSV')->paginate(10)->withQueryString();

        return view('ctct.drl', compact('data', 'hk', 'nh', 'q'));
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
            return back()->withErrors(['file' => 'Import lỗi: ' . $e->getMessage()]);
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
            'MaSV' => 'MSSV',
            'HocKy' => 'Học kỳ',
            'NamHoc' => 'Năm học',
            'DiemRL' => 'Điểm rèn luyện',
            'XepLoai' => 'Xếp loại'
        ]);

        DB::table('BANG_DiemRenLuyen')->updateOrInsert(
            ['MaSV' => $r->MaSV, 'HocKy' => $r->HocKy, 'NamHoc' => $r->NamHoc],
            ['DiemRL' => $r->DiemRL, 'XepLoai' => $r->XepLoai]
        );

        return back()->with('ok', 'Đã lưu điểm rèn luyện.');
    }
    public function drlExport(\Illuminate\Http\Request $r)
    {
        $hk = (int) $r->input('hk', 1);
        $nh = (string) $r->input('nh', '2024-2025');
        $q  = $r->input('q');

        return Excel::download(new DrlExport($hk, $nh, $q), "DRL_HK{$hk}_{$nh}.xlsx");
    }
    public function drlDelete(Request $r)
    {
        $r->validate([
            'MaSV'   => 'required|string|exists:BANG_SinhVien,MaSV',
            'HocKy'  => 'required|integer|min:1|max:3',
            'NamHoc' => 'required|string|max:9',
        ], [], ['MaSV' => 'MSSV', 'HocKy' => 'Học kỳ', 'NamHoc' => 'Năm học']);

        $deleted = DB::table('BANG_DiemRenLuyen')->where([
            'MaSV'   => $r->MaSV,
            'HocKy'  => $r->HocKy,
            'NamHoc' => $r->NamHoc,
        ])->delete();

        return back()->with('ok', $deleted ? 'Đã xóa điểm rèn luyện.' : 'Không tìm thấy bản ghi để xóa.');
    }
    public function drlTemplate(): StreamedResponse
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Dòng tiêu đề
    $headers = [
        'A1' => 'MaSV',
        'B1' => 'HocKy',
        'C1' => 'NamHoc',
        'D1' => 'DiemRL',
        'E1' => 'XepLoai',
    ];

    foreach ($headers as $cell => $value) {
        $sheet->setCellValue($cell, $value);
    }

    // In đậm + set width cho dễ nhìn
    $sheet->getStyle('A1:E1')->getFont()->setBold(true);
    $sheet->getColumnDimension('A')->setWidth(18);
    $sheet->getColumnDimension('B')->setWidth(10);
    $sheet->getColumnDimension('C')->setWidth(14);
    $sheet->getColumnDimension('D')->setWidth(12);
    $sheet->getColumnDimension('E')->setWidth(16);

    $fileName = 'mau_diem_ren_luyen.xlsx';
    $writer   = new Xlsx($spreadsheet);

    return new StreamedResponse(function () use ($writer) {
        $writer->save('php://output');
    }, 200, [
        'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Content-Disposition' => "attachment; filename=\"mau_diem_ren_luyen.xlsx\"",
        'Cache-Control'       => 'max-age=0',
    ]);
}
}
