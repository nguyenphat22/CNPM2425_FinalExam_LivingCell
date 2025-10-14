<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class TaiKhoan extends Model
{
    protected $table = 'BANG_TaiKhoan';
    protected $primaryKey = 'MaTK';
    public $timestamps = false;

    protected $fillable = ['TenDangNhap','MatKhau','VaiTro','TrangThai','Email'];

    // helper
    public static function attempt(string $username, string $password): ?self
    {
        $user = static::where('TenDangNhap', $username)
            ->where('TrangThai', 'Active')
            ->first();
        if ($user && Hash::check($password, $user->MatKhau)) {
            return $user;
        }
        return null;
    }

    // quan hệ
    public function sinhVien()
    {
        return $this->hasOne(SinhVien::class, 'MaTK', 'MaTK');
    }
}
