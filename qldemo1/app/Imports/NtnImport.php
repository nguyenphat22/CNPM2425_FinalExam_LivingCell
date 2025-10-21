<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Row;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class NtnImport implements OnEachRow, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    private int $inserted = 0;

    // Header bắt buộc: masv | tenhoatdong | ngaythamgia | songaytn | trangthaiduyet
    public function onRow(Row $row): void
    {
        $r = $row->toArray();

        // Bỏ qua dòng rỗng
        if (empty($r['masv']) && empty($r['tenhoatdong']) && empty($r['ngaythamgia'])) {
            return;
        }

        // ===== Chuẩn hoá =====
        $masv = trim((string)($r['masv'] ?? ''));
        $tenHoatDong = trim((string)($r['tenhoatdong'] ?? ''));

        // Ngày: hỗ trợ serial Excel, dd/mm/yyyy, dd-mm-yyyy, yyyy-mm-dd
        $ngay = $r['ngaythamgia'] ?? '';
        if (is_numeric($ngay)) {
            $ngay = ExcelDate::excelToDateTimeObject($ngay)->format('Y-m-d');
        } else {
            $ngay = trim((string)$ngay);
            if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $ngay, $m)) {
                $ngay = sprintf('%04d-%02d-%02d', (int)$m[3], (int)$m[2], (int)$m[1]);
            }
        }

        $soNgay = (int)($r['songaytn'] ?? 1);
        if ($soNgay < 1) $soNgay = 1;

        // Map trạng thái
        $tt = strtoupper(trim((string)($r['trangthaiduyet'] ?? 'ChuaDuyet')));
        $tt = match (true) {
            in_array($tt, ['DADUYET','DA DUYET','ĐÃ DUYỆT']) => 'DaDuyet',
            in_array($tt, ['TUCHOI','TU CHOI','TỪ CHỐI'])    => 'TuChoi',
            default                                           => 'ChuaDuyet',
        };

        // ===== Insert (không timestamps vì bảng không có 2 cột đó) =====
        DB::table('bang_ngaytinhnguyen')->insert([
            'MaSV'           => $masv,
            'NgayThamGia'    => $ngay,          // DATE yyyy-mm-dd
            'TenHoatDong'    => $tenHoatDong,
            'SoNgayTN'       => $soNgay,
            'TrangThaiDuyet' => $tt,
        ]);

        $this->inserted++;
    }

    public function headingRow(): int { return 1; }

    public function rules(): array
    {
        return [
            '*.masv'           => ['required','string','max:50'],
            '*.tenhoatdong'    => ['required','string','max:255'],
            '*.ngaythamgia'    => ['required'], // chuẩn hoá trong onRow()
            '*.songaytn'       => ['nullable','integer','min:1'],
            '*.trangthaiduyet' => ['nullable'], // map lại ở trên
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

    public function insertedCount(): int { return $this->inserted; }
}