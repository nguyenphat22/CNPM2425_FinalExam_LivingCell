<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiemRenLuyen extends Model
{
    protected $table      = 'BANG_DiemRenLuyen';
    protected $primaryKey = null;
    public $timestamps    = false;

    protected $fillable = ['MaSV','HocKy','NamHoc','DiemRL'];

    public static function maxDrlByStudent(array $masv): array
    {
        return static::query()
            ->whereIn('MaSV', $masv)
            ->selectRaw('MaSV, MAX(DiemRL) as DiemRL')
            ->groupBy('MaSV')
            ->pluck('DiemRL', 'MaSV')
            ->toArray();
    }
}
