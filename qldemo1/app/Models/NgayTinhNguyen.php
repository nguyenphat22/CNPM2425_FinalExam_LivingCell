<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NgayTinhNguyen extends Model
{
    protected $table = 'bang_ngaytinhnguyen';   // đúng tên bảng của T
    protected $primaryKey = 'MaNTN';
    public $timestamps = false;

    protected $fillable = [
        'MaSV','TenHoatDong','NgayThamGia','SoNgayTN','TrangThaiDuyet'
    ];
}
