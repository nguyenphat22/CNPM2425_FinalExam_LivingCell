<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GpaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        protected int $hk,
        protected string $nh,
        protected ?string $q = null
    ) {}

    public function headings(): array
    {
        return ['MSSV', 'Họ và Tên', 'Học kỳ', 'Năm học', 'Điểm học tập', 'Xếp loại'];
    }

    public function collection()
    {
        $query = DB::table('BANG_SinhVien as sv')
            ->leftJoin('BANG_DiemHocTap as gpa', function ($j) {
                $j->on('sv.MaSV', '=', 'gpa.MaSV')
                    ->where('gpa.HocKy', $this->hk)
                    ->where('gpa.NamHoc', $this->nh);
            })
            ->select('sv.MaSV', 'sv.HoTen', DB::raw('gpa.DiemHe4 as DiemHT'), 'gpa.XepLoai');

        if ($this->q) {
            $q = trim($this->q);
            $query->where(function ($s) use ($q) {
                $s->where('sv.MaSV', 'like', "%{$q}%")
                    ->orWhere('sv.HoTen', 'like', "%{$q}%");
            });
        }

        return $query->orderBy('sv.MaSV')->get();
    }

    public function map($row): array
    {
        return [
            $row->MaSV,
            $row->HoTen,
            $this->hk,
            $this->nh,
            $row->DiemHT ?? '',
            $row->XepLoai ?? '',
        ];
    }
}
