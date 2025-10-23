<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NgayTinhNguyen extends Model
{
    // LƯU Ý: trong controller bạn dùng 'bang_ngaytinhnguyen' (thường),
    // hãy thống nhất table name là chữ thường:
    protected $table = 'bang_ngaytinhnguyen';
    protected $primaryKey = 'MaNTN';
    public $timestamps = false;

    protected $fillable = [
        'MaSV','TenHoatDong','NgayThamGia','SoNgayTN','TrangThaiDuyet'
    ];

    protected $casts = [
        'NgayThamGia' => 'date',
        'SoNgayTN'    => 'integer',
    ];

    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'MaSV', 'MaSV');
    }

    public function scopeSearch($q, ?string $term)
    {
        $term = trim((string)$term);
        if ($term === '') return $q;

        // join để tìm theo tên SV/ MSSV
        return $q->leftJoin('BANG_SinhVien as sv', 'sv.MaSV', '=', "{$this->table}.MaSV")
                 ->where(function ($s) use ($term) {
                    $s->where('sv.MaSV', 'like', "%{$term}%")
                      ->orWhere('sv.HoTen','like', "%{$term}%")
                      ->orWhere('TenHoatDong','like', "%{$term}%");
                 })
                 ->select("{$this->table}.*", 'sv.HoTen');
    }

    public function scopeApproved($q)
    {
        return $q->where('TrangThaiDuyet', 'DaDuyet');
    }
}
