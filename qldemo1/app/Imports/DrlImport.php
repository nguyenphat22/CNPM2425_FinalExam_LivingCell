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

class DrlImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure, WithChunkReading
{
    use SkipsFailures;

    protected int $inserted = 0;
    protected int $updated  = 0;

    public function collection(Collection $rows)
    {
        $upserts = [];
        foreach ($rows as $row) {
            $masv   = trim((string)($row['masv'] ?? ''));
            $hk     = (int)($row['hocky'] ?? 0);
            $nh     = trim((string)($row['namhoc'] ?? ''));
            if ($masv === '' || $hk === 0 || $nh === '') continue;

            $upserts[] = [
                'MaSV'    => $masv,
                'HocKy'   => $hk,
                'NamHoc'  => $nh,
                'DiemRL'  => is_null($row['diemrl'] ?? null) ? null : (int)$row['diemrl'],
                'XepLoai' => $row['xeploai'] ?? null,
            ];
        }
        if (!$upserts) return;

        // đếm trước để ước lượng inserted/updated
        $exists = DB::table('BANG_DiemRenLuyen')
            ->whereIn('MaSV', array_column($upserts, 'MaSV'))
            ->whereIn('HocKy', array_column($upserts, 'HocKy'))
            ->whereIn('NamHoc', array_column($upserts, 'NamHoc'))
            ->get(['MaSV', 'HocKy', 'NamHoc'])
            ->map(fn($r) => "{$r->MaSV}|{$r->HocKy}|{$r->NamHoc}")
            ->flip();

        DB::table('BANG_DiemRenLuyen')->upsert(
            $upserts,
            ['MaSV', 'HocKy', 'NamHoc'],                // unique keys
            ['DiemRL', 'XepLoai']                      // columns to update
        );

        foreach ($upserts as $r) {
            $k = "{$r['MaSV']}|{$r['HocKy']}|{$r['NamHoc']}";
            isset($exists[$k]) ? $this->updated++ : $this->inserted++;
        }
    }

    public function rules(): array
    {
        return [
            '*.masv'    => ['required', 'string', 'max:20', 'exists:BANG_SinhVien,MaSV'],
            '*.hocky'   => ['required', 'integer', 'min:1', 'max:3'],
            '*.namhoc'  => ['required', 'string', 'max:9'],
            '*.diemrl'  => ['nullable', 'integer', 'min:0', 'max:100'],
            '*.xeploai' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }
    public function getInserted(): int
    {
        return $this->inserted;
    }
    public function getUpdated(): int
    {
        return $this->updated;
    }
}
