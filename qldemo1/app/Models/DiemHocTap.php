<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiemHocTap extends Model
{
    protected $table = 'BANG_DiemHocTap';
    public $timestamps = false;

    // Bảng này có khóa chính composite (MaSV, HocKy, NamHoc) nên không dùng primaryKey mặc định
    protected $fillable = ['MaSV','HocKy','NamHoc','DiemHe4','XepLoai'];

    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'MaSV', 'MaSV');
    }
}
