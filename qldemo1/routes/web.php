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
Route::middleware('auth.session')->group(function () {
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
// CTCT HSSV routes
Route::prefix('ctct')
    ->middleware(['auth.session','role:CTCTHSSV'])
    ->group(function () {
        Route::get('/', fn() => redirect()->route('ctct.sinhvien.index'))->name('ctct.home');
        Route::get('/sinhvien', [CtctController::class,'sinhVienIndex'])->name('ctct.sinhvien.index');
        Route::get('/drl',      [CtctController::class,'drlIndex'])->name('ctct.drl.index');
    });
// Khao Thi routes
Route::prefix('khaothi')
  ->middleware(['auth.session','role:KhaoThi'])
  ->group(function () {
    Route::get('/', fn() => redirect()->route('khaothi.sinhvien.index'))->name('khaothi.home');
    Route::get('/sinhvien', [KhaothiController::class,'sinhVienIndex'])->name('khaothi.sinhvien.index');
    Route::get('/gpa',      [KhaothiController::class,'gpaIndex'])->name('khaothi.gpa.index');
  });
Route::prefix('ctct')->middleware('role:CTCTHSSV')->group(function () {
    Route::get('/', fn () => redirect()->route('ctct.sinhvien.index'))->name('ctct.home');

    Route::get('/sinhvien', [\App\Http\Controllers\CtctController::class, 'sinhVienIndex'])
        ->name('ctct.sinhvien.index');

    Route::post('/sinhvien/store',  [\App\Http\Controllers\CtctController::class, 'svStore'])
        ->name('ctct.sv.store');

    Route::post('/sinhvien/update', [\App\Http\Controllers\CtctController::class, 'svUpdate'])
        ->name('ctct.sv.update');

    Route::post('/sinhvien/delete', [\App\Http\Controllers\CtctController::class, 'svDelete'])
        ->name('ctct.sv.delete');
    Route::post('sinhvien/import', [CtctController::class, 'svImport'])->name('ctct.sv.import');
});

// Đoàn Trường routes
Route::prefix('doantruong')
    ->middleware(['auth.session','role:DoanTruong'])
    ->group(function () {
        Route::get('/', fn() => redirect()->route('doan.khenthuong.index'))->name('doan.home');

        Route::get('/khenthuong',   [DoanController::class,'khenThuongIndex'])->name('doan.khenthuong.index');
        Route::get('/tinhnguyen',   [DoanController::class,'tinhNguyenIndex'])->name('doan.tinhnguyen.index');
        Route::get('/danhhieu',     [DoanController::class,'danhHieuIndex'])->name('doan.danhhieu.index');
    });

// Sinh Viên routes
Route::prefix('sinhvien')
    ->middleware(['auth.session','role:SinhVien'])
    ->group(function () {
        Route::get('/', [SinhVienController::class, 'index'])->name('sv.home');
    });