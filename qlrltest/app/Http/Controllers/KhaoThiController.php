<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KhaoThiController extends Controller
{
    public function dsSinhVien(Request $r)
    {
        $q = $r->get('q',''); $per=(int)$r->get('per',10);
        $qB = DB::table('BANG_SinhVien')->select('MaSV','HoTen','NgaySinh','Khoa','Lop');
        if ($q) $qB->where('MaSV','like',"%$q%")->orWhere('HoTen','like',"%$q%");
        return response()->json($qB->orderBy('MaSV')->paginate($per));
    }

    public function dsGPA(Request $r)
    {
        $q = $r->get('q',''); $per=(int)$r->get('per',10);
        $query = DB::table('BANG_DiemHocTap AS d')
            ->join('BANG_SinhVien AS s','s.MaSV','=','d.MaSV')
            ->select('d.MaSV','s.HoTen','d.NamHoc','d.HocKy','d.DiemHe4','d.XepLoai');

        if ($q) $query->where('d.MaSV','like',"%$q%")->orWhere('s.HoTen','like',"%$q%");
        return response()->json($query->orderByDesc('NamHoc')->orderByDesc('HocKy')->paginate($per));
    }

    public function updateGPA(Request $r)
    {
        $r->validate(['MaSV'=>'required','NamHoc'=>'required','HocKy'=>'required|integer','DiemHe4'=>'required|numeric']);
        DB::table('BANG_DiemHocTap')->updateOrInsert(
            ['MaSV'=>$r->MaSV,'NamHoc'=>$r->NamHoc,'HocKy'=>$r->HocKy],
            ['DiemHe4'=>$r->DiemHe4,'XepLoai'=>$r->XepLoai]
        );
        return response()->json(['message'=>'Đã lưu GPA']);
    }

    public function deleteGPA(Request $r)
    {
        DB::table('BANG_DiemHocTap')
            ->where(['MaSV'=>$r->MaSV,'NamHoc'=>$r->NamHoc,'HocKy'=>$r->HocKy])
            ->delete();
        return response()->json(['message'=>'Đã xóa GPA']);
    }

    public function importGPA(){ return response()->json(['message'=>'TODO import GPA']); }
    public function exportGPA(){ return response()->json(['message'=>'TODO export GPA']); }
}
