<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiemRenLuyen extends Model
{
    protected $table = 'BANG_DiemRenLuyen';
    public $timestamps = false;

    // Bảng này dùng khóa tổng hợp (MaSV, HocKy, NamHoc)
    // -> không dùng incrementing / primaryKey mặc định
    public $incrementing = false;

    protected $fillable = ['MaSV','HocKy','NamHoc','DiemRL','XepLoai'];

    public function sinhvien()
    {
        return $this->belongsTo(SinhVien::class, 'MaSV', 'MaSV');
    }
}
