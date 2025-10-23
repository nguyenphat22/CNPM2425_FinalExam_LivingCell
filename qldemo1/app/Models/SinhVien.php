<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SinhVien extends Model
{
    protected $table = 'BANG_SinhVien';
    protected $primaryKey = 'MaSV';
    public $timestamps = false;

    // Nếu MaSV là chuỗi (MSSV)
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['MaSV', 'HoTen', 'NgaySinh', 'Khoa', 'Lop'];

    /** Quan hệ: 1 SV có nhiều bản ghi điểm học tập */
    public function diemHocTap()
    {
        return $this->hasMany(DiemHocTap::class, 'MaSV', 'MaSV');
    }

    /** Scope tìm kiếm nhanh theo q (MSSV/Họ tên/Khoa/Lớp) */
    public function scopeSearch($q, ?string $term)
    {
        $term = trim((string) $term);
        if ($term === '') return $q;

        return $q->where(function($s) use ($term) {
            $s->where('MaSV', 'like', "%{$term}%")
              ->orWhere('HoTen', 'like', "%{$term}%")
              ->orWhere('Khoa', 'like', "%{$term}%")
              ->orWhere('Lop', 'like', "%{$term}%");
        });
    }
}
