<?php

namespace App\Imports;

use App\Models\TaiKhoan;
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

    public static array $requiredHeaders = ['matk','tendangnhap','matkhau','vaitro','email'];

    public static function missingHeaders(array $headers): array
    {
        $headers = array_map('strtolower', $headers);
        return array_values(array_diff(self::$requiredHeaders, $headers));
    }

    public function model(array $row)
    {
        $this->total++;

        $row = array_change_key_case($row, CASE_LOWER);

        $maTK        = trim((string)($row['matk'] ?? ''));
        $tenDangNhap = trim((string)($row['tendangnhap'] ?? ''));
        $matKhau     = trim((string)($row['matkhau'] ?? ''));
        $vaiTro      = trim((string)($row['vaitro'] ?? ''));
        $email       = trim((string)($row['email'] ?? ''));

        if (!$maTK || !$tenDangNhap || !$vaiTro) {
            return null;
        }

        $payload = [
            'TenDangNhap' => $tenDangNhap,
            'VaiTro'      => $vaiTro,
            'TrangThai'   => 'Active',
            'Email'       => $email ?: null,
        ];

        if ($matKhau !== '') {
            // để mutator hash
            $payload['MatKhau'] = $matKhau;
        }

        // Nếu bản ghi tồn tại -> update, ngược lại -> create
        $exists = TaiKhoan::query()->where('MaTK', $maTK)->exists();

        TaiKhoan::updateOrCreate(
            ['MaTK' => $maTK],
            $payload
        );

        if ($exists) $this->updated++; else $this->inserted++;

        return null;
    }

    public function rules(): array
    {
        $table = TaiKhoan::tableName();

        return [
            '*.matk'        => ['required','max:50'],
            '*.tendangnhap' => ['required','max:50'],
            '*.matkhau'     => ['nullable','regex:/^.{6,}$/'],
            '*.vaitro'      => ['required', Rule::in(['Admin','SinhVien','KhaoThi','CTCTHSSV','DoanTruong'])],
            '*.email'       => ['nullable','email','max:100'],
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
