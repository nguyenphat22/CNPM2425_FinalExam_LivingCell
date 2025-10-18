<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\TaiKhoan;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AccountsImport;

class AccountController extends Controller
{
    public function index(Request $r)
    {
        $q = $r->input('q');
        $query = TaiKhoan::query();

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

    public function store(Request $r)
    {
        $r->validateWithBag('add', [
            'TenDangNhap' => 'required|string|max:50|unique:BANG_TaiKhoan,TenDangNhap',
            'MatKhau'     => 'required|min:6',
            'VaiTro'      => 'required|in:Admin,SinhVien,KhaoThi,CTCTHSSV,DoanTruong',
            'Email'       => 'nullable|email|max:100|unique:BANG_TaiKhoan,Email',
        ]);

        TaiKhoan::create([
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
        ]);

        $tk = TaiKhoan::findOrFail($r->MaTK);

        $data = [
            'TenDangNhap' => $r->TenDangNhap,
            'VaiTro'      => $r->VaiTro,
            'TrangThai'   => $r->TrangThai ?? 'Active',
            'Email'       => $r->Email,
        ];
        if ($r->filled('MatKhau')) {
            $data['MatKhau'] = Hash::make($r->MatKhau);
        }

        $tk->update($data);

        return back()->with('ok','Đã cập nhật tài khoản.');
    }

    public function delete(Request $r)
    {
        $r->validate(['MaTK' => 'required|string']);
        TaiKhoan::where('MaTK', $r->MaTK)->delete();
        return back()->with('ok', 'Đã xóa tài khoản.');
    }

    public function import(Request $r)
    {
        $r->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            Excel::import(new AccountsImport, $r->file('file'));
        } catch (\Throwable $e) {
            return back()->withErrors('Import lỗi: '.$e->getMessage());
        }

        return back()->with('ok', 'Nhập danh sách tài khoản thành công.');
    }
}
