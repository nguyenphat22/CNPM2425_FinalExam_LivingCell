<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class KhenThuongExport implements FromCollection, WithHeadings, WithTitle
{
    protected $hk;

    public function __construct($hk)
    {
        $this->hk = $hk;
    }

    public function collection()
    {
        // Lấy danh sách sinh viên và danh hiệu như bên controller
        $sv = DB::table('BANG_SinhVien')->select('MaSV', 'HoTen')->get();
        $gpa = DB::table('BANG_DiemHocTap')
            ->select('MaSV', DB::raw('MAX(DiemHe4) as DiemHe4'))
            ->groupBy('MaSV')->pluck('DiemHe4', 'MaSV');
        $drl = DB::table('BANG_DiemRenLuyen')
            ->select('MaSV', DB::raw('MAX(DiemRL) as DiemRL'))
            ->groupBy('MaSV')->pluck('DiemRL', 'MaSV');
        $ntn = DB::table('BANG_NgayTinhNguyen')
            ->where('TrangThaiDuyet', 'DaDuyet')
            ->select('MaSV', DB::raw('SUM(SoNgayTN) as SoNgayTN'))
            ->groupBy('MaSV')->pluck('SoNgayTN', 'MaSV');
        $danhhieu = DB::table('BANG_DanhHieu')->get();

        $rows = [];
        foreach ($sv as $s) {
            $ds = [];
            foreach ($danhhieu as $d) {
                $ok = ($gpa[$s->MaSV] ?? 0) >= $d->DieuKienGPA
                    && ($drl[$s->MaSV] ?? 0) >= $d->DieuKienDRL
                    && ($ntn[$s->MaSV] ?? 0) >= $d->DieuKienNTN;
                if ($ok) $ds[] = $d->TenDH;
            }
            $rows[] = [
                $s->MaSV,
                $s->HoTen,
                $gpa[$s->MaSV] ?? '',
                $drl[$s->MaSV] ?? '',
                $ntn[$s->MaSV] ?? '',
                $ds ? implode(', ', $ds) : '',
            ];
        }
        return collect($rows);
    }

    public function headings(): array
    {
        return ['MSSV', 'Họ và Tên', 'Điểm GPA', 'Điểm RL', 'Số ngày TN', 'Danh hiệu đạt được'];
    }

    public function title(): string
    {
        return "Bao cao $this->hk";
    }
}
