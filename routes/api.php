<?php

use App\Http\Controllers\ChartController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ChiTietPhanQuyenController;
use App\Http\Controllers\ChiTietPhimController;
use App\Http\Controllers\ChucNangController;
use App\Http\Controllers\DuyetPhimController;
use App\Http\Controllers\KhachHangController;
use App\Http\Controllers\NhanVienController;
use App\Http\Controllers\PhanQuyenController;
use App\Http\Controllers\PhimController;
use App\Http\Controllers\TaiChinhController;
use App\Http\Controllers\TheLoaiController;
use App\Models\ChucNang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/khach-hang/dang-xuat', [KhachHangController::class, 'dangXuat'])->middleware('khachHangMiddleware');
Route::post('/khach-hang/doi-mat-khau', [KhachHangController::class, 'doiMatKhau']);
Route::get('/khach-hang/lay-thong-tin', [KhachHangController::class, 'layThongTin'])->middleware('khachHangMiddleware');
Route::post('/khach-hang/cap-nhat-thong-tin', [KhachHangController::class, 'capNhatThongTin'])->middleware('khachHangMiddleware');
Route::post('/khach-hang/doi-mat-khau-profile', [KhachHangController::class, 'doiMatKhauProfile'])->middleware('khachHangMiddleware');
Route::post('/khach-hang/mua-phim',[ChiTietPhimController::class,'store'])->middleware('khachHangMiddleware');
Route::post('/khach-hang/load-phim',[PhimController::class,'loadPhim'])->middleware('khachHangMiddleware');
Route::post('/khach-hang/nap-tien',[KhachHangController::class,'napTienTK'])->middleware('khachHangMiddleware');
Route::get('/khach-hang/lich-su-nap',[KhachHangController::class,'lichSuNap'])->middleware('khachHangMiddleware');
Route::get('/khach-hang/danh-sach-phim-da-mua',[KhachHangController::class,'danhSachPhim'])->middleware('khachHangMiddleware');

Route::post('/khach-hang/dang-ky', [KhachHangController::class, 'dangKy']);
Route::post('/khach-hang/dang-nhap', [KhachHangController::class, 'dangNhap']);
Route::post('/khach-hang/dang-nhap-google',[KhachHangController::class,'loginGoogle']);
Route::get('/khach-hang/check-login', [KhachHangController::class, 'checkLogin']);
Route::post('/khach-hang/kich-hoat-tai-khoan', [KhachHangController::class, 'kichHoatTaiKhoan']);
Route::post('/khach-hang/quen-mat-khau', [KhachHangController::class, 'quenMatKhau']);
Route::get('/khach-hang/phim/data-phim-chieu-rap', [PhimController::class, 'getDataPhimChieuRap']);
Route::get('/khach-hang/phim/data-phim-bo', [PhimController::class, 'getDataPhimBo']);
Route::get('/khach-hang/phim/data-phim-de-cu', [PhimController::class, 'getDataPhimDeCu']);
Route::get('/khach-hang/phim/data-phim-top/phim-chieu-rap', [PhimController::class, 'getDataPhimTopChieuRap']);
Route::get('/khach-hang/phim/data-phim-top/phim-bo', [PhimController::class, 'getDataPhimTopPhimBo']);
Route::get('khach-hang/phim/loc-phim-rap', [PhimController::class, 'locPhimRap']);
Route::get('khach-hang/phim/loc-phim-bo', [PhimController::class, 'locPhimBo']);
Route::post('khach-hang/phim/tim-kiem', [PhimController::class, 'timKiemPhim']);
Route::get('phim/chi-tiet/{id}', [ChiTietPhimController::class, 'chiTietPhim']);
Route::get('/khach-hang/the-loai/data', [TheLoaiController::class, 'dataTheLoai']);


Route::get('/khach-hang/phim/data-kinhdi',[PhimController::class,'getDataKinhDi']);
Route::get('/khach-hang/phim/data-langman',[PhimController::class,'getDataLangMan']);
Route::get('/khach-hang/phim/data-haihuoc',[PhimController::class,'getDataHaiHuoc']);
Route::get('/khach-hang/phim/data-phieuluu',[PhimController::class,'getDataPhieuLuu']);
Route::get('/khach-hang/phim/data-vientuong',[PhimController::class,'getDataVienTuong']);
Route::get('/khach-hang/phim/data-lichsu',[PhimController::class,'getDataLichSu']);
Route::get('/khach-hang/phim/data-tamly',[PhimController::class,'getDataTamLy']);
Route::get('/khach-hang/phim/data-giatuong',[PhimController::class,'getDataGiaTuong']);
Route::get('/khach-hang/phim/data-hanhdong',[PhimController::class,'getDataHanhDong']);
Route::get('/khach-hang/phim/data-thanthoai',[PhimController::class,'getDataThanThoai']);

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

Route::post('/admin/nap-tien/create',[TaiChinhController::class,'napTien'])->middleware('nhanVienMiddleware');
Route::get('/admin/nap-tien/data',[TaiChinhController::class,'getData'])->middleware('nhanVienMiddleware');
Route::post('/admin/nap-tien/data-one',[TaiChinhController::class,'getDataOnePerson'])->middleware('nhanVienMiddleware');
Route::post('/admin/nap-tien/data-duyet',[TaiChinhController::class,'dsNapTienDuyet'])->middleware('nhanVienMiddleware');
Route::post('/admin/nap-tien/duyet',[TaiChinhController::class,'duyetTienNap'])->middleware('nhanVienMiddleware');

Route::post('/admin/chuc-nang/create',[ChucNangController::class,'store'])->middleware('nhanVienMiddleware');
Route::get('/admin/chuc-nang/data', [ChucNangController::class, 'getData'])->middleware('nhanVienMiddleware');
Route::get('/admin/chuc-nang/data-open', [ChucNangController::class, 'getDataOpen'])->middleware('nhanVienMiddleware');
Route::post('/admin/chuc-nang/delete', [ChucNangController::class, 'xoaChucNang'])->middleware('nhanVienMiddleware');
Route::post('/admin/chuc-nang/update', [ChucNangController::class, 'capNhatChucNang'])->middleware('nhanVienMiddleware');
Route::post('/admin/chuc-nang/change-status', [ChucNangController::class, 'changeStatus'])->middleware('nhanVienMiddleware');
Route::post('/admin/chuc-nang/tim-kiem', [ChucNangController::class, 'timKiem'])->middleware('nhanVienMiddleware');

Route::post('/admin/phan-quyen/create', [PhanQuyenController::class, 'themQuyen'])->middleware("nhanVienMiddleware");
Route::post('/admin/phan-quyen/delete', [PhanQuyenController::class, 'xoaQuyen'])->middleware("nhanVienMiddleware");
Route::post('/admin/phan-quyen/update', [PhanQuyenController::class, 'suaQuyen'])->middleware("nhanVienMiddleware");
Route::get('/admin/phan-quyen/data', [PhanQuyenController::class, 'getQuyen'])->middleware("nhanVienMiddleware");
Route::post('/admin/phan-quyen/search', [PhanQuyenController::class, 'searchQuyen'])->middleware("nhanVienMiddleware");

Route::post('/admin/chi-tiet-phan-quyen/create', [ChiTietPhanQuyenController::class, 'store'])->middleware("nhanVienMiddleware");
Route::post('/admin/chi-tiet-phan-quyen/delete', [ChiTietPhanQuyenController::class, 'xoaPhanQuyen'])->middleware("nhanVienMiddleware");
Route::post('/admin/chi-tiet-phan-quyen/data', [ChiTietPhanQuyenController::class, 'getData'])->middleware("nhanVienMiddleware");

Route::get('/admin/chart-tai-chinh', [ChartController::class, 'chartTaiChinh'])->middleware('nhanVienMiddleware');
Route::get('/admin/chart-khach-hang', [ChartController::class, 'chartKhachHang'])->middleware('nhanVienMiddleware');
Route::get('/admin/chart-phim', [ChartController::class, 'chartPhim'])->middleware('nhanVienMiddleware');
Route::get('/admin/chart-the-loai', [ChartController::class, 'chartTheLoai'])->middleware('nhanVienMiddleware');

Route::get('/admin/phim/data-duyet-phim', [PhimController::class, 'getDataDuyetPhim'])->middleware('nhanVienMiddleware');
Route::post('/admin/phim/duyet-phim', [DuyetPhimController::class, 'duyetPhim'])->middleware('nhanVienMiddleware');
Route::post('/admin/phim/tu-choi-phim', [DuyetPhimController::class, 'tuChoiPhim'])->middleware('nhanVienMiddleware');
Route::get('/admin/phim/ds-tu-choi', [PhimController::class, 'dsTuChoi'])->middleware('nhanVienMiddleware');

Route::post('/admin/dang-nhap', [NhanVienController::class, 'dangNhap']);
Route::get('/admin/check-login', [NhanVienController::class, 'checkLogin']);
Route::get('/admin/dang-xuat', [NhanVienController::class, 'dangXuat'])->middleware('nhanVienMiddleware');
Route::get('/admin/lay-thong-tin-profile', [NhanVienController::class, 'layThongTinProfile']);
Route::post('/admin/thay-doi-thong-tin-profile', [NhanVienController::class, 'thaydoiProfile']);
Route::post('/admin/doi-mat-khau-profile', [NhanVienController::class, 'changePasswordProfile']);


Route::post('/chatbot/query', [ChatbotController::class, 'query']);
Route::get('/chatbot/suggest-movies', [ChatbotController::class, 'suggestMovies']);
Route::get('/chatbot/check-balance', [ChatbotController::class, 'checkBalance']);
Route::get('/chatbot/deposit-history', [ChatbotController::class, 'getDepositHistory']);
Route::get('/chatbot/purchase-history', [ChatbotController::class, 'getPurchaseHistory']);
