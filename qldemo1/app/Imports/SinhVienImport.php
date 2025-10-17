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
        // WithHeadingRow => header được đưa về lowercase: masv, hoten, ngaysinh, khoa, lop
        $upserts = [];

        foreach ($rows as $row) {
            $masv  = trim((string)($row['masv'] ?? ''));
            $hoten = trim((string)($row['hoten'] ?? ''));
            if ($masv === '' || $hoten === '') continue;

            // Chuẩn hóa ngày sinh (hỗ trợ serial Excel)
            $ngaySinh = null;
            if (isset($row['ngaysinh']) && $row['ngaysinh'] !== '') {
                $ng = $row['ngaysinh'];
                $ngaySinh = is_numeric($ng)
                    ? Carbon::instance(ExcelDate::excelToDateTimeObject($ng))->format('Y-m-d')
                    : Carbon::parse($ng)->format('Y-m-d');
            }

            $upserts[] = [
                'MaSV'     => $masv,
                'HoTen'    => $hoten,
                'NgaySinh' => $ngaySinh,
                'Khoa'     => $row['khoa'] ?? null,
                'Lop'      => $row['lop'] ?? null,
            ];
        }

        if (!$upserts) return;

        // Đếm trước để ước lượng inserted/updated
        $existing = DB::table('BANG_SinhVien')
            ->whereIn('MaSV', array_column($upserts, 'MaSV'))
            ->pluck('MaSV')->all();
        $existingSet = array_flip($existing);

        DB::table('BANG_SinhVien')->upsert(
            $upserts,
            ['MaSV'],                       // unique key
            ['HoTen','NgaySinh','Khoa','Lop'] // columns to update
        );

        foreach ($upserts as $r) {
            isset($existingSet[$r['MaSV']]) ? $this->updated++ : $this->inserted++;
        }
    }

    public function rules(): array
    {
        return [
            '*.masv'     => ['required','string','max:20'],
            '*.hoten'    => ['required','string','max:100'],
            '*.ngaysinh' => ['nullable'],
            '*.khoa'     => ['nullable','string','max:100'],
            '*.lop'      => ['nullable','string','max:50'],
        ];
    }

    public function getInserted(): int { return $this->inserted; }
    public function getUpdated(): int  { return $this->updated; }
    public function chunkSize(): int   { return 500; }
}
