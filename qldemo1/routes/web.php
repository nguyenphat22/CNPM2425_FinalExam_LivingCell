<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\CtctController;
use App\Http\Controllers\KhaothiController;
use App\Http\Controllers\DoanController;
use App\Http\Controllers\SinhVienController;

Route::get('/', fn() => redirect()->route('login.show'));

// Auth
Route::get('/login',        [AuthController::class, 'showLogin'])->name('login.show');
Route::post('/login',       [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout',      [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot',       [AuthController::class, 'showForgot'])->name('forgot.show');
Route::post('/forgot',      [AuthController::class, 'handleForgot'])->name('forgot.handle');
Route::get('/reset',        [AuthController::class, 'showReset'])->name('reset.show');        // sau khi verify
Route::post('/reset',       [AuthController::class, 'handleReset'])->name('reset.handle');

// Dashboards theo vai trò (chỉ UI)
Route::middleware(['auth.session', 'active'])->group(function () {
    Route::get('/admin',        [DashboardController::class, 'admin'])->name('admin.home');
    Route::get('/sinhvien',     [DashboardController::class, 'sinhvien'])->name('sv.home');
    Route::get('/ctct-hssv',    [DashboardController::class, 'ctct'])->name('ctct.home');
    Route::get('/khaothi',      [DashboardController::class, 'khaothi'])->name('khaothi.home');
    Route::get('/doantruong',   [DashboardController::class, 'doan'])->name('doan.home');
    Route::post('/admin/accounts/import', [AccountController::class, 'import'])->name('admin.accounts.import');

    // Admin: Danh sách tài khoản (UI + phân trang đọc từ DB)
    Route::get('/admin/accounts',                  [AccountController::class, 'index'])->name('admin.accounts.index');
    Route::post('/admin/accounts/store',           [AccountController::class, 'store'])->name('admin.accounts.store');   // demo
    Route::post('/admin/accounts/update',          [AccountController::class, 'update'])->name('admin.accounts.update'); // demo
    Route::post('/admin/accounts/delete',          [AccountController::class, 'delete'])->name('admin.accounts.delete'); // demo
});
// Admin Home -> redirect sang danh sách tài khoản
Route::get('/admin', function () {
    return redirect()->route('admin.accounts.index');
})->name('admin.home');
// Khao Thi routes
Route::prefix('khaothi')
    ->middleware(['auth.session', 'active', 'role:KhaoThi'])
    ->name('khaothi.')
    ->group(function () {
        Route::get('/', fn() => redirect()->route('khaothi.sinhvien.index'))->name('home');
        Route::get('/sinhvien', [KhaothiController::class, 'sinhVienIndex'])->name('sinhvien.index');

        // === QUẢN LÝ ĐIỂM HỌC TẬP ===
        Route::get('/gpa',         [KhaothiController::class, 'gpaIndex'])->name('gpa.index');
        Route::post('/gpa/update', [KhaothiController::class, 'gpaUpdate'])->name('gpa.update');
        Route::post('/gpa/delete', [KhaothiController::class, 'gpaDelete'])->name('gpa.delete');
        Route::post('/gpa/import', [KhaothiController::class, 'gpaImport'])->name('gpa.import'); // ✅ thêm dòng này
        Route::get ('/gpa/export', [KhaothiController::class, 'gpaExport'])->name('gpa.export');
    });
// CTCT HSSV routes
Route::prefix('ctct')
    ->middleware(['auth.session', 'active', 'role:CTCTHSSV'])
    ->name('ctct.')                      // <-- mọi route bên trong sẽ có tiền tố ctct.
    ->group(function () {

        Route::get('/', fn () => redirect()->route('ctct.sinhvien.index'))
            ->name('home');

        // Danh sách sinh viên
        Route::get('/sinhvien',         [CtctController::class,'sinhVienIndex'])->name('sinhvien.index');
        Route::post('/sinhvien/store',  [CtctController::class,'svStore'])->name('sv.store');
        Route::post('/sinhvien/update', [CtctController::class,'svUpdate'])->name('sv.update');
        Route::post('/sinhvien/delete', [CtctController::class,'svDelete'])->name('sv.delete');
        Route::post('/sinhvien/import', [CtctController::class,'svImport'])->name('sv.import');

        // Điểm rèn luyện
        Route::get( '/drl',         [CtctController::class,'drlIndex'])->name('drl.index');
        Route::post('/drl/update',  [CtctController::class,'drlUpdate'])->name('drl.update');
        Route::post('/drl/delete',  [CtctController::class,'drlDelete'])->name('drl.delete');
        Route::post('/drl/import',  [CtctController::class,'drlImport'])->name('drl.import');
        Route::get( '/drl/export',  [CtctController::class,'drlExport'])->name('drl.export'); // <-- export là GET
    });

// Đoàn Trường routes
Route::prefix('doantruong')
    ->middleware(['auth.session', 'active', 'role:DoanTruong'])
    ->name('doan.') //  Thêm dòng này để Laravel nhận tên route như doan.danhhieu.store
    ->group(function () {
        Route::get('/', fn() => redirect()->route('doan.khenthuong.index'))->name('home');

        Route::get('/khenthuong', [DoanController::class, 'khenThuongIndex'])->name('khenthuong.index');
        Route::get('/tinhnguyen', [DoanController::class, 'tinhNguyenIndex'])->name('tinhnguyen.index');
        Route::get('/danhhieu',   [DoanController::class, 'danhHieuIndex'])->name('danhhieu.index');

        // Export khen thưởng
        Route::get('/khenthuong/export', [DoanController::class, 'exportExcel'])
    ->name('khenthuong.export');

        // Thêm 3 route POST cho danh hiệu
        Route::post('/danhhieu/store',  [DoanController::class, 'dhStore'])->name('danhhieu.store');
        Route::post('/danhhieu/update', [DoanController::class, 'dhUpdate'])->name('danhhieu.update');
        Route::post('/danhhieu/delete', [DoanController::class, 'dhDelete'])->name('danhhieu.delete');
        // Ngày tình nguyện
        Route::post('/tinhnguyen/store',  [DoanController::class,'ntnStore'])->name('tinhnguyen.store');
        Route::post('/tinhnguyen/update', [DoanController::class,'ntnUpdate'])->name('tinhnguyen.update');
        Route::post('/tinhnguyen/delete', [DoanController::class,'ntnDelete'])->name('tinhnguyen.delete');
        
        // Import Excel
        Route::post('/tinhnguyen/import', [DoanController::class,'ntnImport'])->name('tinhnguyen.import');
    });

// Sinh Viên routes
Route::prefix('sinhvien')
    ->middleware(['auth.session', 'active', 'role:SinhVien'])
    ->group(function () {
        Route::get('/', [SinhVienController::class, 'index'])->name('sv.home');
    }); 