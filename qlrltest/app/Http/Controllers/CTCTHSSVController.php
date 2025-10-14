<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CTCTHSSVController extends Controller
{
    // DS sinh viên + tìm kiếm + phân trang
    public function dsSinhVien(Request $req)
    {
        $q = $req->get('q','');
        $per = (int)$req->get('per', 10);

        $query = DB::table('BANG_SinhVien')
            ->select('MaSV','HoTen','NgaySinh','Khoa','Lop');

        if ($q) {
            $query->where(function($s) use ($q){
                $s->where('MaSV','like',"%$q%")
                  ->orWhere('HoTen','like',"%$q%")
                  ->orWhere('Lop','like',"%$q%");
            });
        }

        $rows = $query->orderBy('MaSV')->paginate($per);
        return response()->json($rows);
    }

    public function storeSV(Request $r)
    {
        $r->validate([
            'MaSV'=>'required', 'HoTen'=>'required', 'NgaySinh'=>'required|date', 'Khoa'=>'required'
        ]);
        DB::table('BANG_SinhVien')->insert($r->only(['MaSV','HoTen','NgaySinh','Khoa','Lop']));
        return response()->json(['message'=>'Đã thêm SV'], 201);
    }

    public function updateSV($masv, Request $r)
    {
        DB::table('BANG_SinhVien')->where('MaSV',$masv)->update($r->only(['HoTen','NgaySinh','Khoa','Lop']));
        return response()->json(['message'=>'Đã cập nhật SV']);
    }

    public function destroySV($masv)
    {
        DB::table('BANG_SinhVien')->where('MaSV',$masv)->delete();
        return response()->json(['message'=>'Đã xóa SV']);
    }

    // Điểm rèn luyện
    public function dsDRL(Request $r)
    {
        $q = $r->get('q',''); $per=(int)$r->get('per',10);
        $query = DB::table('BANG_DiemRenLuyen AS d')
            ->join('BANG_SinhVien AS s','s.MaSV','=','d.MaSV')
            ->select('d.MaSV','s.HoTen','d.NamHoc','d.HocKy','d.DiemRL','d.XepLoai');

        if ($q) $query->where('d.MaSV','like',"%$q%")->orWhere('s.HoTen','like',"%$q%");
        return response()->json($query->orderByDesc('NamHoc')->orderByDesc('HocKy')->paginate($per));
    }

    public function updateDRL(Request $r)
    {
        $r->validate(['MaSV'=>'required','NamHoc'=>'required','HocKy'=>'required|integer','DiemRL'=>'required|integer']);
        DB::table('BANG_DiemRenLuyen')->updateOrInsert(
            ['MaSV'=>$r->MaSV,'NamHoc'=>$r->NamHoc,'HocKy'=>$r->HocKy],
            ['DiemRL'=>$r->DiemRL,'XepLoai'=>$r->XepLoai]
        );
        return response()->json(['message'=>'Đã lưu DRL']);
    }

    public function deleteDRL(Request $r)
    {
        DB::table('BANG_DiemRenLuyen')
            ->where(['MaSV'=>$r->MaSV,'NamHoc'=>$r->NamHoc,'HocKy'=>$r->HocKy])
            ->delete();
        return response()->json(['message'=>'Đã xóa DRL']);
    }

    public function importDRL(){ return response()->json(['message'=>'TODO import DRL']); }
    public function exportDRL(){ return response()->json(['message'=>'TODO export DRL']); }
}
