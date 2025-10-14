<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SinhVien extends Model
{
    protected $table = 'BANG_SinhVien';
    protected $primaryKey = 'MaSV';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['MaSV','HoTen','NgaySinh','Khoa','Lop','MaTK'];

    public function gpa()   { return $this->hasMany(DiemHocTap::class, 'MaSV', 'MaSV'); }
    public function drl()   { return $this->hasMany(DiemRenLuyen::class, 'MaSV', 'MaSV'); }
    public function ntn()   { return $this->hasMany(NgayTinhNguyen::class, 'MaSV', 'MaSV'); }
}

class DiemHocTap extends Model {
    protected $table = 'BANG_DiemHocTap';  public $timestamps = false;
    protected $primaryKey = null; public $incrementing = false;
    protected $fillable = ['MaSV','HocKy','NamHoc','DiemHe4','XepLoai','MaPKT'];
}
class DiemRenLuyen extends Model {
    protected $table = 'BANG_DiemRenLuyen'; public $timestamps = false;
    protected $primaryKey = null; public $incrementing = false;
    protected $fillable = ['MaSV','HocKy','NamHoc','DiemRL','XepLoai'];
}
class NgayTinhNguyen extends Model {
    protected $table = 'BANG_NgayTinhNguyen'; public $timestamps = false;
    protected $primaryKey = 'MaNTN';
    protected $fillable = ['MaSV','NgayThamGia','TenHoatDong','SoNgayTN','TrangThaiDuyet'];
}
class DanhHieu extends Model {
    protected $table = 'BANG_DanhHieu'; public $timestamps = false;
    protected $primaryKey = 'MaDH';
    protected $fillable = ['TenDH','DieuKienGPA','DieuKienDRL','DieuKienNTN'];
}
