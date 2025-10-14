<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoanTruongController extends Controller
{
    // Danh sách khen thưởng
    public function dsKhenThuong(Request $r)
    {
        $q = $r->get('q',''); $per=(int)$r->get('per',10);
        $query = DB::table('BANG_SinhVien_DanhHieu AS sd')
            ->join('BANG_SinhVien AS s','s.MaSV','=','sd.MaSV')
            ->join('BANG_DanhHieu AS d','d.MaDH','=','sd.MaDH')
            ->select('sd.MaSV','s.HoTen','d.TenDH','sd.SoQuyetDinh','sd.NamHoc','sd.HocKy');
        if ($q) $query->where('sd.MaSV','like',"%$q%")->orWhere('s.HoTen','like',"%$q%");
        return response()->json($query->orderByDesc('NamHoc')->orderByDesc('HocKy')->paginate($per));
    }

    // Quản lý ngày tình nguyện
    public function dsNTN(Request $r)
    {
        $q = $r->get('q',''); $per=(int)$r->get('per',10);
        $query = DB::table('BANG_NgayTinhNguyen AS n')
            ->join('BANG_SinhVien AS s','s.MaSV','=','n.MaSV')
            ->select('n.MaNTN','n.MaSV','s.HoTen','n.TenHoatDong','n.NgayThamGia','n.SoNgayTN','n.TrangThaiDuyet');
        if ($q) $query->where('n.MaSV','like',"%$q%")->orWhere('s.HoTen','like',"%$q%");
        return response()->json($query->orderByDesc('NgayThamGia')->paginate($per));
    }

    public function storeNTN(Request $r)
    {
        $r->validate([
            'MaSV'=>'required', 'TenHoatDong'=>'required', 'NgayThamGia'=>'required|date',
            'SoNgayTN'=>'required|integer', 'TrangThaiDuyet'=>'in:ChuaDuyet,DaDuyet,TuChoi'
        ]);
        $id = DB::table('BANG_NgayTinhNguyen')->insertGetId($r->only(
            'MaSV','TenHoatDong','NgayThamGia','SoNgayTN','TrangThaiDuyet'
        ));
        return response()->json(['message'=>'Đã thêm NTN','MaNTN'=>$id], 201);
    }

    public function updateNTN($id, Request $r)
    {
        DB::table('BANG_NgayTinhNguyen')->where('MaNTN',$id)->update($r->only(
            'MaSV','TenHoatDong','NgayThamGia','SoNgayTN','TrangThaiDuyet'
        ));
        return response()->json(['message'=>'Đã cập nhật NTN']);
    }

    public function destroyNTN($id)
    {
        DB::table('BANG_NgayTinhNguyen')->where('MaNTN',$id)->delete();
        return response()->json(['message'=>'Đã xóa NTN']);
    }

    public function importNTN(){ return response()->json(['message'=>'TODO import NTN']); }
    public function exportNTN(){ return response()->json(['message'=>'TODO export NTN']); }

    // Quản lý danh hiệu
    public function dsDanhHieu(Request $r)
    {
        $q = $r->get('q',''); $per=(int)$r->get('per',10);
        $qb = DB::table('BANG_DanhHieu');
        if ($q) $qb->where('TenDH','like',"%$q%");
        return response()->json($qb->orderBy('MaDH','desc')->paginate($per));
    }

    public function storeDanhHieu(Request $r)
    {
        $r->validate([
            'TenDH'=>'required', 'DieuKienGPA'=>'required|numeric',
            'DieuKienDRL'=>'required|integer', 'DieuKienNTN'=>'required|integer'
        ]);
        $id = DB::table('BANG_DanhHieu')->insertGetId($r->only(
            'TenDH','DieuKienGPA','DieuKienDRL','DieuKienNTN'
        ));
        return response()->json(['message'=>'Đã thêm danh hiệu','MaDH'=>$id],201);
    }

    public function updateDanhHieu($id, Request $r)
    {
        DB::table('BANG_DanhHieu')->where('MaDH',$id)->update($r->only(
            'TenDH','DieuKienGPA','DieuKienDRL','DieuKienNTN'
        ));
        return response()->json(['message'=>'Đã cập nhật danh hiệu']);
    }

    public function destroyDanhHieu($id)
    {
        DB::table('BANG_DanhHieu')->where('MaDH',$id)->delete();
        return response()->json(['message'=>'Đã xóa danh hiệu']);
    }
}
