<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AccountController;

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
