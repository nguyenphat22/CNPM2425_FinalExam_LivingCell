<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Model;

class NtnModel extends Model
{
    protected $table = 'BANG_NgayTinhNguyen';
    protected $primaryKey = 'MaNTN';
    public $timestamps = false;

    protected $fillable = [
        'MaNTN', 'MaSV', 'TenHoatDong', 'NgayThamGia', 'SoNgayTN', 'TrangThai',
    ];
}
