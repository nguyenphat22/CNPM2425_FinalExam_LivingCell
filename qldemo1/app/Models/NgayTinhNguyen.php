<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NgayTinhNguyen extends Model
{
    protected $table = 'bang_ngaytinhnguyen';
    protected $primaryKey = 'MaNTN';
    public $timestamps = false;

    protected $fillable = [
        'MaSV','TenHoatDong','NgayThamGia','SoNgayTN','TrangThaiDuyet'
    ];

    protected $casts = [
        'NgayThamGia' => 'date',
        'SoNgayTN'    => 'integer',
    ];
    public function getNgayThamGiaTextAttribute()
{
    return $this->NgayThamGia
        ? $this->NgayThamGia->format('d/m/Y')
        : null;
}
    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'MaSV', 'MaSV');
    }

    public function scopeSearch($q, ?string $term)
{
    $term  = trim((string)$term);
    $table = $q->getModel()->getTable();

    // Luôn join sang bảng sinh viên + select HoTen
    $q->leftJoin('BANG_SinhVien as sv', 'sv.MaSV', '=', "{$table}.MaSV")
      ->select("{$table}.*", 'sv.HoTen');

    // Nếu không có từ khóa thì trả luôn query đã join
    if ($term === '') {
        return $q;
    }

    // Có từ khóa thì thêm where
    return $q->where(function ($s) use ($term, $table) {
        $s->where("{$table}.MaSV", 'like', "%{$term}%")
          ->orWhere('sv.HoTen', 'like', "%{$term}%")
          ->orWhere("{$table}.TenHoatDong", 'like', "%{$term}%");
    });
}

    public function scopeApproved($q)
    {
        return $q->where('TrangThaiDuyet', 'DaDuyet');
    }

    /* Tổng số ngày TN đã duyệt theo MaSV */
    public static function sumApprovedByStudent(array $masv): array
    {
        $table = (new static)->getTable();

        return static::query()
            ->whereIn('MaSV', $masv)
            ->approved()
            ->selectRaw('MaSV, SUM(SoNgayTN) as SoNgayTN')
            ->groupBy('MaSV')
            ->pluck('SoNgayTN', 'MaSV')
            ->toArray();
    }
}
