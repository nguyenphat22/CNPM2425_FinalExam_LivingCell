<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiemHocTap extends Model
{
    protected $table      = 'BANG_DiemHocTap';
    public $timestamps    = false;

    protected $fillable = ['MaSV','HocKy','NamHoc','DiemHe4','XepLoai'];

    protected $casts = [
        'HocKy'   => 'integer',
        'DiemHe4' => 'float',
    ];

    /* Lấy MAX(GPA) theo MaSV cho danh sách MaSV */
    public static function maxGpaByStudent(array $masv): array
    {
        return static::query()
            ->whereIn('MaSV', $masv)
            ->selectRaw('MaSV, MAX(DiemHe4) as DiemHe4')
            ->groupBy('MaSV')
            ->pluck('DiemHe4', 'MaSV')
            ->toArray();
    }

    /* ---------- Scopes tiện dụng ---------- */
    public function scopeForTerm($q, int $hk, string $nh)
    {
        return $q->where('HocKy', $hk)->where('NamHoc', $nh);
    }

    public function scopeStudent($q, string $maSV)
    {
        return $q->where('MaSV', $maSV);
    }
}
