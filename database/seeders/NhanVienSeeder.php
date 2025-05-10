<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NhanVienSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('nhan_viens')->delete();

        DB::table('nhan_viens')->insert([
            [
                'email' => 'admin@gmail.com',
                'password' => '123456',
                'ho_va_ten' => 'Admin',
                'so_dien_thoai' => '0123456789',
                'ngay_sinh' => '1999-01-01',
                'id_quyen' => 1,
                'tinh_trang' => 0,
            ],[
                'email' => 'nguyenvanuy@gmail.com',
                'password' => '123456',
                'ho_va_ten' => 'Nguyễn Văn Uy',
                'so_dien_thoai' => '0123456781',
                'ngay_sinh' => '1999-01-01',
                'id_quyen' => 2,
                'tinh_trang' => 0,
            ],
            [
                'email' => 'tranquangxuan@gmail.com',
                'password' => '123456',
                'ho_va_ten' => 'Trần Quang Xuân',
                'so_dien_thoai' => '0123456782',
                'ngay_sinh' => '1999-01-01',
                'id_quyen' => 2,
                'tinh_trang' => 0,
            ],
            [
                'email' => 'lethic@gmail.com',
                'password' => '123456',
                'ho_va_ten' => 'Lê Thị C',
                'so_dien_thoai' => '0123456783',
                'ngay_sinh' => '1999-01-01',
                'id_quyen' => 3,
                'tinh_trang' => 0,
            ],
            [
                'email' => 'phamvand@gmail.com',
                'password' => '123456',
                'ho_va_ten' => 'Phạm Văn D',
                'so_dien_thoai' => '0123456784',
                'ngay_sinh' => '1999-01-01',
                'id_quyen' => 4,
                'tinh_trang' => 0,
            ],
            [
                'email' => 'hoangthie@gmail.com',
                'password' => '123456',
                'ho_va_ten' => 'Hoàng Thị E',
                'so_dien_thoai' => '0123456785',
                'ngay_sinh' => '1999-01-01',
                'id_quyen' => 5,
                'tinh_trang' => 0,
            ],
        ]);
    }
}
