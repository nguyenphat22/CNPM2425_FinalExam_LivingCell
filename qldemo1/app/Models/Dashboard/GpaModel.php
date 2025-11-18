<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Model;

class GpaModel extends Model
{
    protected $table = 'BANG_DiemHocTap';
    protected $primaryKey = 'MaDHT';
    public $timestamps = false;

    protected $fillable = [
        'MaDHT', 'MaSV', 'HocKy', 'NamHoc', 'DiemHe4', 'XepLoai',
    ];
}
