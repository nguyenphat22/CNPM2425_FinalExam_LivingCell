<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class SinhVienController extends Controller
{
    public function index(Request $r)
    {
        // Lấy dữ liệu của T (giữ tên biến như đang dùng)
        $sv   = $r->sv   ?? null;
        $gpa  = $r->gpa  ?? null;
        $drl  = $r->drl  ?? null;
        $ntn  = $r->ntn  ?? null;     // tổng + danh sách, nếu có
        $awds = $r->awards ?? [];     // danh sách khen thưởng
        $goiY = $r->goiY ?? null;

        return view('sinhvien.index', compact('sv','gpa','drl','ntn','awds','goiY'));
    }
}