<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| WEB ROUTES (Minimal & Working)
|--------------------------------------------------------------------------
| - Root -> /login
| - Auth routes
| - Role landing routes (names must match AuthController::routeByRole())
|   Tạm thời dùng closure trả chuỗi để bạn test, sau này gắn controller/view.
*/

Route::get('/', fn () => redirect()->route('login.form'));

/* ---------- Auth ---------- */
Route::get ('/login',  [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/login',  [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get ('/forgot', [AuthController::class, 'showForgot'])->name('forgot.form');
Route::post('/forgot', [AuthController::class, 'verifyForgot'])->name('forgot.verify');
Route::post('/reset',  [AuthController::class, 'resetPassword'])->name('password.reset');

/* ---------- Role landing (placeholders to ensure no 404) ---------- */
/* Admin */
Route::get('/admin/taikhoan', function () {
    return 'Admin - Danh sách tài khoản (placeholder)';
})->name('admin.taikhoan.index');

/* Sinh viên */
Route::get('/sinhvien', function () {
    return 'Sinh viên - Dashboard (placeholder)';
})->name('sv.dashboard');

/* CTCT-HSSV */
Route::get('/ctct/sinhvien', function () {
    return 'CTCT-HSSV - Danh sách sinh viên (placeholder)';
})->name('ctct.sv.index');

/* Khảo thí */
Route::get('/khaothi/sinhvien', function () {
    return 'Khảo thí - Danh sách sinh viên (placeholder)';
})->name('kt.sv.index');

/* Đoàn trường */
Route::get('/doan/khenthuong', function () {
    return 'Đoàn trường - Danh sách khen thưởng (placeholder)';
})->name('doan.kt.index');
