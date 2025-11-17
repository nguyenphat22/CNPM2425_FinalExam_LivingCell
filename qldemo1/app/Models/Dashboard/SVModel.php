<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Model;

class SVModel extends Model
{
    protected $table = 'BANG_SinhVien';   // bảng thật
    protected $primaryKey = 'MaSV';
    public $timestamps = false;

    protected $fillable = [
        'MaSV', 'HoTen', 'NgaySinh', 'Lop', 'Khoa', 'MaTK',
    ];
}
