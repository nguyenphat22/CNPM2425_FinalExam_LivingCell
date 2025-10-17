<?php

namespace App\Http\Controllers;

class DoanController extends Controller
{
    public function khenThuongIndex()
    {
        return view('doan.khenthuong');
    }

    public function tinhNguyenIndex()
    {
        return view('doan.tinhnguyen');
    }

    public function danhHieuIndex()
    {
        return view('doan.danhhieu');
    }
}
