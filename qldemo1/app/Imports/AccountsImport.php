<?php

namespace App\Imports;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AccountsImport implements OnEachRow, WithHeadingRow
{
    // File phải có hàng tiêu đề (heading row) như: MaTK, TenDangNhap, MatKhau, VaiTro, TrangThai, Email
    public function onRow(Row $row)
    {
        $r = $row->toArray();

        // Chuẩn hóa key theo đúng tên cột DB của T
        $MaTK        = $r['matk']        ?? null;
        $TenDangNhap = $r['tendangnhap'] ?? null;
        $MatKhau     = $r['matkhau']     ?? null;  // cho phép plain -> mình sẽ hash
        $VaiTro      = $r['vaitro']      ?? null;
        $TrangThai   = $r['trangthai']   ?? 'Active';
        $Email       = $r['email']       ?? null;

        if (!$MaTK || !$TenDangNhap || !$MatKhau || !$VaiTro) {
            // Bỏ qua dòng thiếu dữ liệu quan trọng
            return;
        }

        DB::table('BANG_TaiKhoan')->updateOrInsert(
            ['MaTK' => $MaTK],
            [
                'TenDangNhap' => $TenDangNhap,
                'MatKhau'     => self::needsRehash($MatKhau) ? Hash::make($MatKhau) : $MatKhau,
                'VaiTro'      => $VaiTro,
                'TrangThai'   => $TrangThai ?: 'Active',
                'Email'       => $Email,
            ]
        );
    }

    // Nếu user đưa vào là hash bcrypt sẵn thì giữ nguyên; nếu là plain text thì hash
    private static function needsRehash(string $value): bool
    {
        return !preg_match('/^\$2y\$\d+\$/', $value); // rất thô, chỉ để nhận diện bcrypt $2y$
    }
}
