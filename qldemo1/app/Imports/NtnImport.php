<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class NtnImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    /**
     * Map từng dòng dữ liệu -> insert vào bảng bang_ngaytinhnguyen
     * File Excel cần có header: masv, tenhoatdong, ngaythamgia, songaytn, trangthaiduyet
     */
    public function model(array $row)
    {
        // Bỏ qua dòng rỗng
        if (
            empty($row['masv']) &&
            empty($row['tenhoatdong']) &&
            empty($row['ngaythamgia'])
        ) {
            return null;
        }

        return DB::table('bang_ngaytinhnguyen')->insert([
            'MaSV'           => (string) $row['masv'],
            'TenHoatDong'    => (string) $row['tenhoatdong'],
            'NgayThamGia'    => (string) $row['ngaythamgia'],  // yyyy-mm-dd
            'SoNgayTN'       => (int)    ($row['songaytn'] ?? 1),
            'TrangThaiDuyet' => (string) ($row['trangthaiduyet'] ?? 'ChuaDuyet'),
        ]);
    }

    // Dòng tiêu đề nằm ở hàng 1
    public function headingRow(): int
    {
        return 1;
    }

    // Validate từng dòng (theo header)
    public function rules(): array
    {
        return [
            '*.masv'           => ['required', 'string', 'max:20'],
            '*.tenhoatdong'    => ['required', 'string', 'max:200'],
            '*.ngaythamgia'    => ['required', 'date'],
            '*.songaytn'       => ['nullable', 'integer', 'min:1'],
            '*.trangthaiduyet' => ['nullable', 'in:ChuaDuyet,DaDuyet,TuChoi'],
        ];
    }

    public function customValidationAttributes(): array
    {
        return [
            'masv'           => 'MSSV',
            'tenhoatdong'    => 'Tên hoạt động',
            'ngaythamgia'    => 'Ngày tham gia',
            'songaytn'       => 'Số ngày TN',
            'trangthaiduyet' => 'Trạng thái duyệt',
        ];
    }
}
