<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SinhVien extends Model
{
    protected $table = 'BANG_SinhVien';
    protected $primaryKey = 'MaSV';
    public $timestamps = false;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['MaSV', 'HoTen', 'NgaySinh', 'Khoa', 'Lop'];

    public function diemHocTap()
    {
        return $this->hasMany(DiemHocTap::class, 'MaSV', 'MaSV');
    }

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

    // Sắp theo MSSV dạng số (bỏ dấu chấm) – giống code T đang dùng
    public function scopeOrderByMaSVNumeric($q)
    {
        return $q->orderByRaw('LPAD(REPLACE(MaSV, ".", ""), 20, "0")');
    }
}
