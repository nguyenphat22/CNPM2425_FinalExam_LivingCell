<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\TaiKhoan;
use Maatwebsite\Excel\HeadingRowImport;
use App\Imports\AccountsImport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AccountController extends Controller
{
    public function index(Request $r)
    {
        $q = $r->input('q');

        $data = TaiKhoan::query()
            ->select('MaTK', 'TenDangNhap', 'MatKhau', 'VaiTro', 'TrangThai', 'Email')
            ->filterQ($q)
            ->orderBy('MaTK')
            ->paginate(10)
            ->withQueryString();

        return view('admin.accounts.index', compact('data', 'q'));
    }

    // Demo store/update/delete để form hoạt động
    public function store(Request $r)
    {
        $table = TaiKhoan::tableName();

        $r->validateWithBag('add', [
            'MaTK'        => 'nullable|string|max:50', // bật "required" nếu MaTK tự cấp
            'TenDangNhap' => ['required', 'string', 'max:50', Rule::unique($table, 'TenDangNhap')],
            'MatKhau'     => ['required', 'min:6'],
            'VaiTro'      => ['required', Rule::in(['Admin', 'SinhVien', 'KhaoThi', 'CTCTHSSV', 'DoanTruong'])],
            'Email'       => ['nullable', 'email', 'max:100', Rule::unique($table, 'Email')],
        ], [
            'TenDangNhap.unique' => 'Tên đăng nhập đã tồn tại.',
            'Email.unique'       => 'Email đã tồn tại.',
        ], [
            'TenDangNhap' => 'Tên đăng nhập',
            'MatKhau'     => 'Mật khẩu',
            'VaiTro'      => 'Vai trò',
            'Email'       => 'Email',
        ]);

        $payload = [
            'MaTK'        => $r->MaTK,     
            'TenDangNhap' => $r->TenDangNhap,
            'MatKhau'     => $r->MatKhau,  
            'VaiTro'      => $r->VaiTro,
            'TrangThai'   => 'Active',
            'Email'       => $r->Email,
        ];

        // Nếu MaTK auto-increment, loại bỏ key rỗng để Eloquent tự tạo
        if ($payload['MaTK'] === null || $payload['MaTK'] === '') unset($payload['MaTK']);

        TaiKhoan::create($payload);

        return back()->with('ok', 'Đã thêm tài khoản.');
    }

    public function update(Request $r)
    {
        $table = TaiKhoan::tableName();

        $r->validateWithBag('edit', [
            'MaTK'        => 'required|string|max:50',
            'TenDangNhap' => ['required', 'string', 'max:50', Rule::unique($table, 'TenDangNhap')->ignore($r->MaTK, 'MaTK')],
            'MatKhau'     => ['nullable', 'min:6'],
            'VaiTro'      => ['required', Rule::in(['Admin', 'SinhVien', 'KhaoThi', 'CTCTHSSV', 'DoanTruong'])],
            'Email'       => ['nullable', 'email', 'max:100', Rule::unique($table, 'Email')->ignore($r->MaTK, 'MaTK')],
            'TrangThai'   => ['nullable', 'in:Active,Inactive,Locked'],
        ], [
            'TenDangNhap.unique' => 'Tên đăng nhập đã tồn tại.',
            'Email.unique'       => 'Email đã tồn tại.',
        ], [
            'MaTK'        => 'MaTK(ID)',
            'TenDangNhap' => 'Tên đăng nhập',
            'VaiTro'      => 'Vai trò',
            'Email'       => 'Email',
        ]);

        $tk = TaiKhoan::findOrFail($r->MaTK);

        $data = [
            'TenDangNhap' => $r->TenDangNhap,
            'VaiTro'      => $r->VaiTro,
            'TrangThai'   => $r->TrangThai ?? 'Active',
            'Email'       => $r->Email,
        ];

        if ($r->filled('MatKhau')) {
            $data['MatKhau'] = $r->MatKhau; 
        }

        $tk->update($data);

        return back()->with('ok', 'Đã cập nhật tài khoản.');
    }

    public function delete(Request $r)
    {
        $r->validate(['MaTK' => 'required|string']);
        TaiKhoan::destroy($r->MaTK);
        return back()->with('ok', 'Đã xóa tài khoản.');
    }

    public function import(Request $r)
    {
        $r->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        // Kiểm tra header “an toàn” (giữ nguyên logic cũ)
        $arr = (new HeadingRowImport)->toArray($r->file('file'));
        $firstRow = $arr[0][0] ?? [];
        $headers = [];
        foreach ((array)$firstRow as $k => $v) {
            $headers[] = is_string($k) ? $k : $v;
        }
        $headers = array_map(function ($h) {
            $h = is_string($h) ? $h : '';
            $h = trim($h);
            $h = preg_replace('/\s+/u', '', $h);
            return mb_strtolower($h, 'UTF-8');
        }, $headers);

        $required = \App\Imports\AccountsImport::$requiredHeaders;
        $missing  = array_values(array_diff($required, $headers));
        if (!empty($missing)) {
            return back()->withErrors('File Excel thiếu cột: ' . implode(', ', $missing));
        }

        $import = new AccountsImport();
        try {
            \Maatwebsite\Excel\Facades\Excel::import($import, $r->file('file'));
        } catch (\Throwable $e) {
            return back()->withErrors('Import lỗi: ' . $e->getMessage());
        }

        if ($import->failures()->isNotEmpty()) {
            $msg = [];
            /** @var \Maatwebsite\Excel\Validators\Failure $f */
            foreach ($import->failures()->take(5) as $f) {
                $msg[] = 'Dòng ' . $f->row() . ': ' . implode('; ', $f->errors());
            }
            return back()->withErrors("Import lỗi dữ liệu:\n" . implode("\n", $msg));
        }

        if ($import->total === 0) {
            return back()->withErrors('File không có dữ liệu hợp lệ để import.');
        }

        return back()->with('ok', "Nhập thành công: Tổng {$import->total}, Thêm {$import->inserted}, Cập nhật {$import->updated}");
    }
    public function downloadTemplate(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Tiêu đề cột
        $columns = [
            'A1' => 'MaTK',
            'B1' => 'TenDangNhap',
            'C1' => 'MatKhau',
            'D1' => 'VaiTro',
            'E1' => 'TrangThai',
            'F1' => 'Email'
        ];

        foreach ($columns as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Style tiêu đề
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(22);
        $sheet->getColumnDimension('C')->setWidth(18);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(25);

        // Xuất file xlsx
        $fileName = 'mau_taikhoan.xlsx';
        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"mau_taikhoan.xlsx\"",
            'Cache-Control'       => 'max-age=0',
        ]);
    }
}
