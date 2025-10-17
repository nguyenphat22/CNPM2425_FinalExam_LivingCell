<?php
// app/Http/Controllers/CtctController.php

namespace App\Http\Controllers;

class CtctController extends Controller
{
    public function sinhVienIndex()
    {
        return view('ctct.sinhvien');
    }

    public function drlIndex()
    {
        return view('ctct.drl');
    }
}