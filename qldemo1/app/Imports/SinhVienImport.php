<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;

class SinhVienImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure, WithChunkReading
{
    use SkipsFailures;

    protected int $inserted = 0;
    protected int $updated  = 0;

    public function collection(Collection $rows)
    {
        // WithHeadingRow => các header được đưa về lowercase: masv, hoten, ngaysinh, khoa, lop, matk
        $upserts = [];

        foreach ($rows as $row) {
            $masv  = trim((string)($row['masv']  ?? ''));
            $hoten = trim((string)($row['hoten'] ?? ''));
            if ($masv === '' || $hoten === '') {
                continue;
            }

            // Chuẩn hóa ngày sinh (hỗ trợ serial Excel)
            $ngaySinh = null;
            if (isset($row['ngaysinh']) && $row['ngaysinh'] !== '') {
                $ng = $row['ngaysinh'];
                $ngaySinh = is_numeric($ng)
                    ? Carbon::instance(ExcelDate::excelToDateTimeObject($ng))->format('Y-m-d')
                    : Carbon::parse($ng)->format('Y-m-d');
            }

            // --- NEW: đọc và kiểm tra MaTK (tùy chọn) ---
            $matk = null;
            if (isset($row['matk']) && $row['matk'] !== '') {
                $matkCandidate = (int) trim((string) $row['matk']);

                // 1) Phải tồn tại trong BANG_TaiKhoan với VaiTro = 'SinhVien'
                $tkOk = DB::table('BANG_TaiKhoan')
                    ->where('MaTK', $matkCandidate)
                    ->where('VaiTro', 'SinhVien')
                    ->exists();

                // 2) Không bị gán cho SV khác
                $tkUsedByOther = DB::table('BANG_SinhVien')
                    ->where('MaTK', $matkCandidate)
                    ->where('MaSV', '<>', $masv)
                    ->exists();

                if ($tkOk && !$tkUsedByOther) {
                    $matk = $matkCandidate;
                } else {
                    // Không hợp lệ -> để trống (không chặn import)
                    $matk = null;
                }
            }

            $upserts[] = [
                'MaSV'     => $masv,
                'HoTen'    => $hoten,
                'NgaySinh' => $ngaySinh,
                'Khoa'     => $row['khoa'] ?? null,
                'Lop'      => $row['lop']  ?? null,
                'MaTK'     => $matk,            // <-- NEW
            ];
        }

        if (!$upserts) {
            return;
        }

        // Ước lượng inserted/updated
        $existing = DB::table('BANG_SinhVien')
            ->whereIn('MaSV', array_column($upserts, 'MaSV'))
            ->pluck('MaSV')->all();
        $existingSet = array_flip($existing);

        // Upsert có cả cột MaTK
        DB::table('BANG_SinhVien')->upsert(
            $upserts,
            ['MaSV'],
            ['HoTen', 'NgaySinh', 'Khoa', 'Lop', 'MaTK'] // <-- NEW: MaTK
        );

        foreach ($upserts as $r) {
            isset($existingSet[$r['MaSV']]) ? $this->updated++ : $this->inserted++;
        }
    }

    public function rules(): array
    {
        // Không ép unique/exists ở đây để tránh vướng case update;
        // đã kiểm tra ở trong collection() rồi.
        return [
            '*.masv'     => ['required', 'string', 'max:20'],
            '*.hoten'    => ['required', 'string', 'max:100'],
            '*.ngaysinh' => ['nullable'],
            '*.khoa'     => ['nullable', 'string', 'max:100'],
            '*.lop'      => ['nullable', 'string', 'max:50'],
            '*.matk'     => ['nullable', 'integer'], // <-- NEW
        ];
    }

    public function getInserted(): int
    {
        return $this->inserted;
    }
    public function getUpdated(): int
    {
        return $this->updated;
    }
    public function chunkSize(): int
    {
        return 500;
    }
}
