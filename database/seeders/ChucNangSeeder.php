<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChucNangSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('chuc_nangs')->delete();
        DB::table('chuc_nangs')->truncate();
        DB::table('chuc_nangs')->insert([
            ['id' => '1', 'ten_chuc_nang' => 'Lấy Dữ Liệu Khách Hàng', 'trang_thai' => 0],
            ['id' => '2', 'ten_chuc_nang' => 'Xóa Khách Hàng', 'trang_thai' => 0],
            ['id' => '3', 'ten_chuc_nang' => 'Cập Nhật Khách Hàng', 'trang_thai' => 0],
            ['id' => '4', 'ten_chuc_nang' => 'Thay Đổi Trạng Thái Khách Hàng', 'trang_thai' => 0],
            ['id' => '5', 'ten_chuc_nang' => 'Thêm Mới Nhân Viên', 'trang_thai' => 0],
            ['id' => '6', 'ten_chuc_nang' => 'Lấy Dữ Liệu Nhân Viên', 'trang_thai' => 0],
            ['id' => '7', 'ten_chuc_nang' => 'Xóa Nhân Viên', 'trang_thai' => 0],
            ['id' => '8', 'ten_chuc_nang' => 'Cập Nhật Nhân Viên', 'trang_thai' => 0],
            ['id' => '9', 'ten_chuc_nang' => 'Thay Đổi Trạng Thái Nhân Viên', 'trang_thai' => 0],
            ['id' => '10', 'ten_chuc_nang' => 'Thêm Mới Phim', 'trang_thai' => 0],
            ['id' => '11', 'ten_chuc_nang' => 'Lấy Dữ Liệu Phim', 'trang_thai' => 0],
            ['id' => '12', 'ten_chuc_nang' => 'Xóa Phim', 'trang_thai' => 0],
            ['id' => '13', 'ten_chuc_nang' => 'Cập Nhật Phim', 'trang_thai' => 0],
            ['id' => '14', 'ten_chuc_nang' => 'Đổi Trạng Thái Phim', 'trang_thai' => 0],
            ['id' => '15', 'ten_chuc_nang' => 'Thêm Mới Thể Loại', 'trang_thai' => 0],
            ['id' => '16', 'ten_chuc_nang' => 'Lấy Dữ Liệu Thể Loại', 'trang_thai' => 0],
            ['id' => '17', 'ten_chuc_nang' => 'Xóa Thể Loại', 'trang_thai' => 0],
            ['id' => '18', 'ten_chuc_nang' => 'Cập Nhật Thể Loại', 'trang_thai' => 0],
            ['id' => '19', 'ten_chuc_nang' => 'Đổi Trạng Thái Thể Loại', 'trang_thai' => 0],
            ['id' => '20', 'ten_chuc_nang' => 'Lấy Danh Sách Thể Loại', 'trang_thai' => 0],
            ['id' => '21', 'ten_chuc_nang' => 'Nạp Tiền Khách Hàng', 'trang_thai' => 0],
            ['id' => '22', 'ten_chuc_nang' => 'Lấy Danh Sách Nạp Tiền Đã Duyệt', 'trang_thai' => 0],
            ['id' => '23', 'ten_chuc_nang' => 'Lấy Danh Sách Nạp Tiền Đã Duyệt Của Từng Khách Hàng', 'trang_thai' => 0],
            ['id' => '24', 'ten_chuc_nang' => 'Lấy Danh Sách Nạp Tiền Chưa Duyệt', 'trang_thai' => 0],
            ['id' => '25', 'ten_chuc_nang' => 'Duyệt Thanh Toán', 'trang_thai' => 0],
            ['id' => '26', 'ten_chuc_nang' => 'Thêm Mới Chức Năng', 'trang_thai' => 0],
            ['id' => '27', 'ten_chuc_nang' => 'Lấy Dữ Liệu Chức Năng', 'trang_thai' => 0],
            ['id' => '28', 'ten_chuc_nang' => 'Lấy Danh Sách Chức Năng', 'trang_thai' => 0],
            ['id' => '29', 'ten_chuc_nang' => 'Xóa Chức Năng', 'trang_thai' => 0],
            ['id' => '30', 'ten_chuc_nang' => 'Cập Nhật Chức Năng', 'trang_thai' => 0],
            ['id' => '31', 'ten_chuc_nang' => 'Thay Đổi Trạng Thái Chức Năng', 'trang_thai' => 0],
            ['id' => '32', 'ten_chuc_nang' => 'Thêm Mới Quyền', 'trang_thai' => 0],
            ['id' => '33', 'ten_chuc_nang' => 'Xóa Quyền', 'trang_thai' => 0],
            ['id' => '34', 'ten_chuc_nang' => 'Cập Nhật Quyền', 'trang_thai' => 0],
            ['id' => '35', 'ten_chuc_nang' => 'Lấy Dữ Liệu Quyền', 'trang_thai' => 0],
            ['id' => '36', 'ten_chuc_nang' => 'Thêm Mới Chi Tiết Phân Quyền', 'trang_thai' => 0],
            ['id' => '37', 'ten_chuc_nang' => 'Xóa Chi Tiết Phân Quyền', 'trang_thai' => 0],
            ['id' => '38', 'ten_chuc_nang' => 'Lấy Dữ Liệu Chi Tiết Phân Quyền', 'trang_thai' => 0],
            ['id' => '39', 'ten_chuc_nang' => 'Lấy Danh Sách Phim Chưa Duyệt', 'trang_thai' => 0],
            ['id' => '40', 'ten_chuc_nang' => 'Duyệt Phim', 'trang_thai' => 0],
            ['id' => '41', 'ten_chuc_nang' => 'Từ Chối Phim', 'trang_thai' => 0],
            ['id' => '42', 'ten_chuc_nang' => 'Lấy Danh Sách Phim Bị Từ Chối', 'trang_thai' => 0],
        ]);
    }
}
