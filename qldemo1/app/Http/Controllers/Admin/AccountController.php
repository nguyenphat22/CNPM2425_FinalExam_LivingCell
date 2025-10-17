<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AccountsImport; 

class AccountController extends Controller
{
    public function index(Request $r)
    {
        $q = $r->input('q');
        $query = DB::table('BANG_TaiKhoan')
            ->select('MaTK','TenDangNhap','MatKhau','VaiTro','TrangThai','Email');

        if ($q) {
            $query->where(function($s) use ($q){
                $s->where('TenDangNhap','like',"%$q%")
                  ->orWhere('VaiTro','like',"%$q%")
                  ->orWhere('TrangThai','like',"%$q%");
            });
        }

        $data = $query->orderBy('MaTK')->paginate(10)->withQueryString();
        return view('admin.accounts.index', compact('data','q'));
    }

    // Demo store/update/delete để form hoạt động (không phải sản phẩm cuối)
   public function store(Request $r)
{
    // Lỗi hiển thị trong modal Thêm (bag 'add')
    $r->validateWithBag('add', [
        'TenDangNhap' => 'required|string|max:50|unique:BANG_TaiKhoan,TenDangNhap',
        'MatKhau'     => 'required|min:6',
        'VaiTro'      => 'required|in:Admin,SinhVien,KhaoThi,CTCTHSSV,DoanTruong',
        'Email'       => 'nullable|email|max:100|unique:BANG_TaiKhoan,Email',
    ], [
        'TenDangNhap.unique' => 'Tên đăng nhập đã tồn tại.',
        'Email.unique'       => 'Email đã tồn tại.',
    ], [
        'TenDangNhap' => 'Tên đăng nhập',
        'MatKhau'     => 'Mật khẩu',
        'VaiTro'      => 'Vai trò',
        'Email'       => 'Email',
    ]);

    DB::table('BANG_TaiKhoan')->insert([
        'TenDangNhap' => $r->TenDangNhap,
        'MatKhau'     => Hash::make($r->MatKhau),
        'VaiTro'      => $r->VaiTro,
        'TrangThai'   => 'Active',
        'Email'       => $r->Email,
    ]);

    return back()->with('ok','Đã thêm tài khoản.');
}

public function update(Request $r)
{
    // Lỗi hiển thị trong modal Sửa (bag 'edit')
    $r->validateWithBag('edit', [
        'MaTK'        => 'required|string|max:50',
        'TenDangNhap' => [
            'required','string','max:50',
            Rule::unique('BANG_TaiKhoan','TenDangNhap')->ignore($r->MaTK, 'MaTK'),
        ],
        'VaiTro'      => 'required|in:Admin,SinhVien,KhaoThi,CTCTHSSV,DoanTruong',
        'Email'       => [
            'nullable','email','max:100',
            Rule::unique('BANG_TaiKhoan','Email')->ignore($r->MaTK, 'MaTK'),
        ],
    ], [
        'TenDangNhap.unique' => 'Tên đăng nhập đã tồn tại.',
        'Email.unique'       => 'Email đã tồn tại.',
    ], [
        'MaTK'        => 'MaTK(ID)',
        'TenDangNhap' => 'Tên đăng nhập',
        'VaiTro'      => 'Vai trò',
        'Email'       => 'Email',
    ]);

    $data = [
        'TenDangNhap' => $r->TenDangNhap,
        'VaiTro'      => $r->VaiTro,
        'TrangThai'   => $r->TrangThai ?? 'Active',
        'Email'       => $r->Email,
    ];
    if ($r->filled('MatKhau')) {
        $data['MatKhau'] = Hash::make($r->MatKhau);
    }

    DB::table('BANG_TaiKhoan')->where('MaTK', $r->MaTK)->update($data);

    return back()->with('ok','Đã cập nhật tài khoản.');
}



    public function delete(Request $r)
{
    $r->validate(['MaTK' => 'required|string']); // MaTK có thể là MSSV có dấu chấm/0 đầu
    DB::table('BANG_TaiKhoan')->where('MaTK', $r->MaTK)->delete();
    return back()->with('ok', 'Đã xóa tài khoản.');
}

    public function import(\Illuminate\Http\Request $r)
{
    $r->validate([
        'file' => 'required|file|mimes:xlsx,xls,csv|max:5120', // <=5MB
    ]);

    try {
        Excel::import(new AccountsImport, $r->file('file'));
    } catch (\Throwable $e) {
        return back()->withErrors('Import lỗi: '.$e->getMessage());
    }

    return back()->with('ok', 'Nhập danh sách tài khoản thành công.');
}
}
