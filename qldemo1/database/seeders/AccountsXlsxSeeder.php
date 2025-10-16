<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AccountsImport;

class AccountsXlsxSeeder extends Seeder
{
    public function run(): void
    {
        // ví dụ: import file Excel khi seeding
        $filePath = base_path('storage/app/accounts.xlsx');

        if (file_exists($filePath)) {
            Excel::import(new AccountsImport, $filePath);
            $this->command->info('✅ Đã import tài khoản từ file Excel.');
        } else {
            $this->command->warn('⚠️ Không tìm thấy file taikhoan.xlsx trong thư mục seeders.');
        }
    }
}
