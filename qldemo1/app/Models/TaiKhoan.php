<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class TaiKhoan extends Model
{
    protected $table = 'BANG_TaiKhoan';
    protected $primaryKey = 'MaTK';
    public $timestamps = false;

    protected $fillable = [
        'TenDangNhap', 'MatKhau', 'VaiTro', 'TrangThai', 'Email'
    ];

    // Nếu muốn tự hash mật khẩu khi set
    public function setMatKhauAttribute($value)
    {
        $this->attributes['MatKhau'] = Hash::make($value);
    }
}
