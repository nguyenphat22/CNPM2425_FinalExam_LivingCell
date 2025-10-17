<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KhaothiController extends Controller
{
    public function sinhVienIndex(){ return view('khaothi.sinhvien'); }
    public function gpaIndex(){ return view('khaothi.diemhoc'); }
}
