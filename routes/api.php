<?php

use App\Http\Controllers\KhachHangController;
use App\Http\Controllers\NhanVienController;
use App\Http\Controllers\PhimController;
use App\Http\Controllers\TheLoaiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/khach-hang/dang-ky', [KhachHangController::class, 'dangKy']);





//ADMIN
Route::get('/admin/khach-hang/data', [KhachHangController::class, 'getData'])->middleware('nhanVienMiddleware');
Route::post('/admin/khach-hang/delete', [KhachHangController::class, 'xoaKhachHang'])->middleware('nhanVienMiddleware');
Route::post('/admin/khach-hang/update', [KhachHangController::class, 'capNhatKhachHang'])->middleware('nhanVienMiddleware');
Route::post('/admin/khach-hang/change-status', [KhachHangController::class, 'changeStatus'])->middleware('nhanVienMiddleware');
Route::post('/admin/khach-hang/tim-kiem', [KhachHangController::class, 'timKiem'])->middleware('nhanVienMiddleware');

Route::post('/admin/nhan-vien/create', [NhanVienController::class, 'create'])->middleware('nhanVienMiddleware');
Route::get('/admin/nhan-vien/data', [NhanVienController::class, 'getData'])->middleware('nhanVienMiddleware');
Route::post('/admin/nhan-vien/delete', [NhanVienController::class, 'xoaNhanVien'])->middleware('nhanVienMiddleware');
Route::post('/admin/nhan-vien/update', [NhanVienController::class, 'capNhatNhanVien'])->middleware('nhanVienMiddleware');
Route::post('/admin/nhan-vien/change-status', [NhanVienController::class, 'changeStatus'])->middleware('nhanVienMiddleware');
Route::post('/admin/nhan-vien/tim-kiem', [NhanVienController::class, 'timKiem'])->middleware('nhanVienMiddleware');

Route::post('/admin/the-loai/create', [TheLoaiController::class, 'create'])->middleware('nhanVienMiddleware');
Route::get('/admin/the-loai/data', [TheLoaiController::class, 'getData'])->middleware('nhanVienMiddleware');
Route::post('/admin/the-loai/delete', [TheLoaiController::class, 'xoaTheLoai'])->middleware('nhanVienMiddleware');
Route::post('/admin/the-loai/update', [TheLoaiController::class, 'capNhatTheLoai'])->middleware('nhanVienMiddleware');
Route::post('/admin/the-loai/change-status', [TheLoaiController::class, 'changeStatus'])->middleware('nhanVienMiddleware');
Route::get('/admin/the-loai/data-open', [TheLoaiController::class, 'dataOpen'])->middleware('nhanVienMiddleware');

Route::post('/admin/phim/create', [PhimController::class, 'create'])->middleware('nhanVienMiddleware');
Route::get('/admin/phim/data', [PhimController::class, 'getData'])->middleware('nhanVienMiddleware');
Route::post('/admin/dang-nhap', [NhanVienController::class, 'dangNhap']);
Route::get('/admin/check-login', [NhanVienController::class, 'checkLogin']);
