<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiemHocTap extends Model
{
    protected $table = 'BANG_DiemHocTap';
    public $timestamps = false;

    // Khóa chính là composite (MaSV, HocKy, NamHoc) → không dùng PK mặc định
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = ['MaSV','HocKy','NamHoc','DiemHe4','XepLoai'];

    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'MaSV', 'MaSV');
    }

    /** Scope lọc theo học kỳ + năm học */
    public function scopeTermYear($q, int $hk, string $nh)
    {
        return $q->where('HocKy', $hk)->where('NamHoc', $nh);
    }

    /** Upsert một record theo bộ khóa (MaSV, HocKy, NamHoc) */
    public static function upsertOne(array $keys, array $values)
    {
        return static::updateOrCreate($keys, $values); // tránh rắc rối composite key khi save()
    }
}
