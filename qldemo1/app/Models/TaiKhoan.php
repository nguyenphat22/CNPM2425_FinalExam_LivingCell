<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class TaiKhoan extends Model
{
    protected $table = 'BANG_TaiKhoan';
    protected $primaryKey = 'MaTK';
    public $timestamps = false;

    // Nếu MaTK là chuỗi (MSSV/MSCB) tự cấp khoá, bật 2 dòng dưới:
    // public $incrementing = false;
    // protected $keyType = 'string';

    protected $fillable = [
        'MaTK', 'TenDangNhap', 'MatKhau', 'VaiTro', 'TrangThai', 'Email',
    ];

    protected $hidden = ['MatKhau'];

    // Hash mật khẩu khi gán
    public function setMatKhauAttribute($value)
    {
        if ($value === null || $value === '') return;
        $this->attributes['MatKhau'] = Hash::make($value);
    }

    /* ---------- Scopes ---------- */
    public function scopeFilterQ($q, ?string $kw)
    {
        if (!$kw) return $q;
        $kw = trim($kw);
        return $q->where(function ($s) use ($kw) {
            $s->where('TenDangNhap', 'like', "%{$kw}%")
              ->orWhere('VaiTro', 'like', "%{$kw}%")
              ->orWhere('TrangThai', 'like', "%{$kw}%");
        });
    }

    /* ---------- Helper methods (tùy chọn) ---------- */

    // Dùng cho login: tìm theo tên đăng nhập
    public static function findByUsername(string $username): ?self
    {
        return static::where('TenDangNhap', $username)->first();
    }

    // Kiểm tra active
    public function isActive(): bool
    {
        return $this->TrangThai === 'Active';
    }

    public static function tableName(): string
    {
        return (new static)->getTable();
    }
}
