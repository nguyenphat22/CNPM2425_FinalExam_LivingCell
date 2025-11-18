<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Model;

class DrlModel extends Model
{
    protected $table = 'BANG_DiemRenLuyen';
    protected $primaryKey = 'MaDRL';
    public $timestamps = false;

    protected $fillable = [
        'MaDRL', 'MaSV', 'HocKy', 'NamHoc', 'DiemRL', 'XepLoai',
    ];
}
