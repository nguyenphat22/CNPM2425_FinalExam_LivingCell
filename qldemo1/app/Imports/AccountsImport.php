<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class AccountsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public int $total = 0;
    public int $inserted = 0;
    public int $updated = 0;

    /** Các cột bắt buộc trong file Excel */
    public static array $requiredHeaders = ['matk', 'tendangnhap', 'matkhau', 'vaitro', 'email'];

    public static function missingHeaders(array $headers): array
    {
        $headers = array_map('strtolower', $headers);
        return array_values(array_diff(self::$requiredHeaders, $headers));
    }

    public function model(array $row)
    {
        $this->total++;

        // chuẩn hoá key
        $row = array_change_key_case($row, CASE_LOWER);

        $maTK        = trim((string)($row['matk'] ?? ''));
        $tenDangNhap = trim((string)($row['tendangnhap'] ?? ''));
        $matKhau     = trim((string)($row['matkhau'] ?? ''));
        $vaiTro      = trim((string)($row['vaitro'] ?? ''));
        $email       = trim((string)($row['email'] ?? ''));

        if (!$maTK || !$tenDangNhap || !$vaiTro) {
            return null; // bỏ qua dòng trống hoặc thiếu dữ liệu chính
        }

        $exist = DB::table('BANG_TaiKhoan')->where('MaTK', $maTK)->first();

        $data = [
            'TenDangNhap' => $tenDangNhap,
            'VaiTro'      => $vaiTro,
            'TrangThai'   => 'Active',
            'Email'       => $email ?: null,
        ];
        if ($matKhau) {
            $data['MatKhau'] = Hash::make($matKhau);
        }

        if ($exist) {
            DB::table('BANG_TaiKhoan')->where('MaTK', $maTK)->update($data);
            $this->updated++;
        } else {
            $data['MaTK'] = $maTK;
            DB::table('BANG_TaiKhoan')->insert($data);
            $this->inserted++;
        }

        return null;
    }

    public function rules(): array
    {
        return [
            '*.matk'        => ['required', 'max:50'],
            '*.tendangnhap' => ['required', 'max:50'],
            '*.matkhau'     => ['nullable', 'regex:/^.{6,}$/'], // >=6 ký tự
            '*.vaitro'      => ['required', Rule::in(['Admin','SinhVien','KhaoThi','CTCTHSSV','DoanTruong'])],
            '*.email'       => ['nullable', 'email', 'max:100'],
        ];
    }

    public function customValidationAttributes()
    {
        return [
            'matk'        => 'MaTK',
            'tendangnhap' => 'Tên đăng nhập',
            'matkhau'     => 'Mật khẩu',
            'vaitro'      => 'Vai trò',
            'email'       => 'Email',
        ];
    }
}
