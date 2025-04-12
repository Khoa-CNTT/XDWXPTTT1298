<?php

use App\Http\Controllers\KhachHangController;
use App\Http\Controllers\NhanVienController;
use App\Http\Controllers\PhimController;
use App\Http\Controllers\TheLoaiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/khach-hang/dang-ky', [KhachHangController::class, 'dangKy']);
Route::post('/khach-hang/dang-nhap', [KhachHangController::class, 'dangNhap']);
Route::get('/khach-hang/check-login', [KhachHangController::class, 'checkLogin']);

Route::get('/khach-hang/phim/data-phim-chieu-rap', [PhimController::class, 'getDataPhimChieuRap']);
Route::get('/khach-hang/phim/data-phim-bo', [PhimController::class, 'getDataPhimBo']);
Route::get('/khach-hang/phim/data-phim-de-cu', [PhimController::class, 'getDataPhimDeCu']);
Route::get('/khach-hang/phim/data-phim-top/phim-chieu-rap', [PhimController::class, 'getDataPhimTopChieuRap']);
Route::get('/khach-hang/phim/data-phim-top/phim-bo', [PhimController::class, 'getDataPhimTopPhimBo']);


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
Route::post('/admin/phim/delete', [PhimController::class, 'xoaPhim'])->middleware('nhanVienMiddleware');
Route::post('/admin/phim/update', [PhimController::class, 'capNhatPhim'])->middleware('nhanVienMiddleware');
Route::post('/admin/phim/change-status', [PhimController::class, 'changeStatus'])->middleware('nhanVienMiddleware');
Route::post('/admin/phim/tim-kiem', [PhimController::class, 'timKiem'])->middleware('nhanVienMiddleware');


Route::post('/admin/dang-nhap', [NhanVienController::class, 'dangNhap']);
Route::get('/admin/check-login', [NhanVienController::class, 'checkLogin']);
