<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TaiKhoanController extends Controller
{
    // Danh sách + tìm kiếm + phân trang
    public function index(Request $req)
    {
        $q = trim($req->get('q',''));
        $pageSize = (int)($req->get('per', 10));

        $query = DB::table('BANG_TaiKhoan')
            ->select('MaTK as Ma','TenDangNhap','MatKhau','VaiTro','TrangThai');

        if ($q !== '') {
            $query->where(function($s) use ($q){
                $s->where('TenDangNhap','like',"%$q%")
                  ->orWhere('MaTK','like',"%$q%");
            });
        }

        $rows = $query->orderBy('MaTK','desc')->paginate($pageSize);

        // map STT
        $sttStart = ($rows->currentPage()-1)*$rows->perPage();
        $data = $rows->getCollection()->map(function($r,$i) use ($sttStart){
            $r->STT = $sttStart + $i + 1;
            return $r;
        });

        return response()->json([
            'columns' => ['STT','MSSV/MSCB','Tên đăng nhập','Mật khẩu','Vai trò','Trạng thái'],
            'data'    => $data,
            'pagination'=> [
                'total'=>$rows->total(),'per_page'=>$rows->perPage(),
                'current'=>$rows->currentPage(),'last'=>$rows->lastPage()
            ]
        ]);
    }

    // Thêm
    public function store(Request $req)
    {
        $req->validate([
            'MaTK'        => 'nullable|integer', // cho phép truyền trống để AUTO_INCREMENT
            'TenDangNhap' => 'required|string|max:50|unique:BANG_TaiKhoan,TenDangNhap',
            'MatKhau'     => 'required|string|min:6',
            'VaiTro'      => 'required|in:Admin,SinhVien,KhaoThi,CTCTHSSV,DoanTruong',
            'TrangThai'   => 'nullable|in:Active,Inactive,Locked',
            'Email'       => 'nullable|email'
        ]);

        $id = DB::table('BANG_TaiKhoan')->insertGetId([
            'TenDangNhap' => $req->TenDangNhap,
            'MatKhau'     => Hash::make($req->MatKhau),
            'VaiTro'      => $req->VaiTro,
            'TrangThai'   => $req->TrangThai ?? 'Active',
            'Email'       => $req->Email
        ]);

        return response()->json(['message'=>'Đã thêm tài khoản','MaTK'=>$id], 201);
    }

    // Sửa
    public function update($id, Request $req)
    {
        $req->validate([
            'TenDangNhap' => "sometimes|string|max:50|unique:BANG_TaiKhoan,TenDangNhap,$id,MaTK",
            'MatKhau'     => 'sometimes|string|min:6',
            'VaiTro'      => 'sometimes|in:Admin,SinhVien,KhaoThi,CTCTHSSV,DoanTruong',
            'TrangThai'   => 'sometimes|in:Active,Inactive,Locked',
            'Email'       => 'sometimes|nullable|email'
        ]);

        $data = $req->only(['TenDangNhap','VaiTro','TrangThai','Email']);
        if ($req->filled('MatKhau')) $data['MatKhau'] = Hash::make($req->MatKhau);

        DB::table('BANG_TaiKhoan')->where('MaTK',$id)->update($data);
        return response()->json(['message'=>'Đã cập nhật']);
    }

    // Xóa
    public function destroy($id)
    {
        DB::table('BANG_TaiKhoan')->where('MaTK',$id)->delete();
        return response()->json(['message'=>'Đã xóa']);
    }

    // Placeholder import/export: để bạn gắn PhpSpreadsheet sau
    public function importExcel(Request $req){ return response()->json(['message'=>'TODO import']); }
    public function exportExcel(){ return response()->json(['message'=>'TODO export']); }
}
