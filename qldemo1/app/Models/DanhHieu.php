<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhHieu extends Model
{
    protected $table = 'bang_danhhieu';
    protected $primaryKey = 'MaDH';
    public $timestamps = false;

    protected $fillable = ['TenDH','DieuKienGPA','DieuKienDRL','DieuKienNTN'];
}
