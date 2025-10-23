<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class TaiKhoan extends Model
{
    protected $table = 'BANG_TaiKhoan';
    protected $primaryKey = 'MaTK';
    public $timestamps = false;

    // Nếu MaTK là AUTO INCREMENT (int) thì giữ mặc định.
    // Nếu MaTK là chuỗi MSSV/MSCB, mở 2 dòng dưới và tự truyền MaTK khi create():
    // public $incrementing = false;
    // protected $keyType = 'string';

    protected $fillable = [
        'TenDangNhap', 'MatKhau', 'VaiTro', 'TrangThai', 'Email',
        // 'MaTK', // chỉ thêm nếu $incrementing=false và bạn tự cấp khóa
    ];

    protected $hidden = ['MatKhau'];

    // Hash mật khẩu khi được gán (create/fill/update)
    public function setMatKhauAttribute($value)
    {
        if ($value === null || $value === '') return; // không động vào nếu không truyền
        $this->attributes['MatKhau'] = Hash::make($value);
    }
}
