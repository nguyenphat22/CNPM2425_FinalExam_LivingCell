<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SinhVienController extends Controller
{
    public function dashboard(Request $req)
    {
        $user = $req->session()->get('user');
        $sv = DB::table('BANG_SinhVien')->where('MaTK',$user['MaTK'])->first();

        if (!$sv) return response()->json(['message'=>'Không tìm thấy hồ sơ sinh viên'], 404);

        // Tổng hợp số liệu
        $gpa  = DB::table('BANG_DiemHocTap')->where('MaSV',$sv->MaSV)->orderByDesc('NamHoc')->orderByDesc('HocKy')->first();
        $drl  = DB::table('BANG_DiemRenLuyen')->where('MaSV',$sv->MaSV)->orderByDesc('NamHoc')->orderByDesc('HocKy')->first();
        $ntn  = DB::table('BANG_NgayTinhNguyen')->where('MaSV',$sv->MaSV)->sum('SoNgayTN');

        // Đề xuất danh hiệu gần đạt
        $suggest = null;
        $dhList = DB::table('BANG_DanhHieu')->get();
        foreach ($dhList as $dh) {
            $okGpa = $gpa ? ($gpa->DiemHe4 >= $dh->DieuKienGPA) : false;
            $okDrl = $drl ? ($drl->DiemRL   >= $dh->DieuKienDRL) : false;
            $okNtn = $ntn >= $dh->DieuKienNTN;
            if (!$okGpa || !$okDrl || !$okNtn) {
                $needNtn = max(0, $dh->DieuKienNTN - $ntn);
                if ($gpa && $drl) {
                    $suggest = "Gợi ý: bạn gần đạt '{$dh->TenDH}'. Còn thiếu ".($needNtn)." ngày TN.";
                    break;
                }
            }
        }

        return response()->json([
            'ThongTinSinhVien' => [
                'MSSV'=>$sv->MaSV, 'HoTen'=>$sv->HoTen,'NgaySinh'=>$sv->NgaySinh,
                'Lop'=>$sv->Lop,'Khoa'=>$sv->Khoa
            ],
            'HocTap'    => $gpa ? ['DiemHe4'=>$gpa->DiemHe4,'XepLoai'=>$gpa->XepLoai,'NamHoc'=>$gpa->NamHoc,'HocKy'=>$gpa->HocKy] : null,
            'RenLuyen'  => $drl ? ['DiemRL'=>$drl->DiemRL,'XepLoai'=>$drl->XepLoai,'NamHoc'=>$drl->NamHoc,'HocKy'=>$drl->HocKy] : null,
            'NgayTinhNguyenTong'=>$ntn,
            'DeXuatDanhHieu'=>$suggest
        ]);
    }
}
