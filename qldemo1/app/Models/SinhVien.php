<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SinhVien extends Model
{
    protected $table = 'BANG_SinhVien';
    protected $primaryKey = 'MaSV';
    public $incrementing = false;       // MaSV là VARCHAR
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['MaSV','HoTen','NgaySinh','Khoa','Lop'];
    protected $casts = ['NgaySinh' => 'date'];

    // Quan hệ DRL
    public function drls()
    {
        return $this->hasMany(DiemRenLuyen::class, 'MaSV', 'MaSV');
    }

    // Scope tìm kiếm nhanh
    public function scopeSearch($q, $term)
    {
        $term = trim((string)$term);
        if ($term === '') return $q;
        return $q->where(function($s) use ($term){
            $s->where('MaSV','like',"%{$term}%")
              ->orWhere('HoTen','like',"%{$term}%")
              ->orWhere('Khoa','like',"%{$term}%")
              ->orWhere('Lop','like',"%{$term}%");
        });
    }
}
