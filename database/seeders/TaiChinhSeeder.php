<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaiChinhSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('tai_chinhs')->delete();
        DB::table('tai_chinhs')->insert([
            [
                'id_khach_hang' => 1,
                'id_nhan_vien'  => 1,
                'so_tien_nap'   => 10000,
                'kieu_nap'      => 1,
                'noi_dung'      => 'Nạp tiền vào tài khoản',
                'hash'         => 'GDPHIM_1',
                'is_thanh_toan' => 1,
                'created_at'    => now(),
            ],
            [
                'id_khach_hang' => 2,
                'id_nhan_vien'  => 2,
                'so_tien_nap'   => 10000,
                'kieu_nap'      => 0,
                'noi_dung'      => 'Nạp tiền qua ngân hàng',
                'hash'         => 'GDPHIM_2',
                'is_thanh_toan' => 1,
                'created_at'    => now(),
            ],
            [
                'id_khach_hang' => 3,
                'id_nhan_vien'  => 3,
                'so_tien_nap'   => 10000,
                'kieu_nap'      => 1,
                'noi_dung'      => 'Nạp tiền vào tài khoản',
                'hash'         => 'GDPHIM_3',
                'is_thanh_toan' => 1,
                'created_at'    => now(),
            ],
            [
                'id_khach_hang' => 4,
                'id_nhan_vien'  => 1,
                'so_tien_nap'   => 10000,
                'kieu_nap'      => 0,
                'noi_dung'      => 'Nạp tiền qua ngân hàng',
                'hash'         => 'GDPHIM_4',
                'is_thanh_toan' => 1,
                'created_at'    => now(),
            ],
            [
                'id_khach_hang' => 1,
                'id_nhan_vien'  => 1,
                'so_tien_nap'   => 20000,
                'kieu_nap'      => 0,
                'noi_dung'      => 'Nạp tiền qua ngân hàng',
                'hash'         => 'GDPHIM_5',
                'is_thanh_toan' => 1,
                'created_at'    => now(),
            ],
            [
                'id_khach_hang' => 1,
                'id_nhan_vien'  => 1,
                'so_tien_nap'   => 10000,
                'kieu_nap'      => 0,
                'noi_dung'      => 'Nạp tiền qua ngân hàng',
                'hash'         => 'GDPHIM_6',
                'is_thanh_toan' => 1,
                'created_at'    => now(),
            ],
            [
                'id_khach_hang' => 1,
                'id_nhan_vien'  => 2,
                'so_tien_nap'   => 10000,
                'kieu_nap'      => 1,
                'noi_dung'      => 'Nạp tiền vào tài khoản',
                'hash'         => 'GDPHIM_7',
                'is_thanh_toan' => 0,
                'created_at'    => now(),
            ],
            [
                'id_khach_hang' => 2,
                'id_nhan_vien'  => 1,
                'so_tien_nap'   => 25000,
                'kieu_nap'      => 0,
                'noi_dung'      => 'Nạp tiền qua ngân hàng',
                'hash'         => 'GDPHIM_8',
                'is_thanh_toan' => 0,
                'created_at'    => now(),
            ],
            [
                'id_khach_hang' => 2,
                'id_nhan_vien'  => 1,
                'so_tien_nap'   => 15000,
                'kieu_nap'      => 0,
                'noi_dung'      => 'Nạp tiền qua ngân hàng',
                'hash'         => 'GDPHIM_9',
                'is_thanh_toan' => 0,
                'created_at'    => now(),
            ],
            [
                'id_khach_hang' => 3,
                'id_nhan_vien'  => 1,
                'so_tien_nap'   => 30000,
                'kieu_nap'      => 0,
                'noi_dung'      => 'Nạp tiền qua ngân hàng',
                'hash'         => 'GDPHIM_10',
                'is_thanh_toan' => 0,
                'created_at'    => now(),
            ],
            [
                'id_khach_hang' => 1,
                'id_nhan_vien'  => 1,
                'so_tien_nap'   => 50000,
                'kieu_nap'      => 0,
                'noi_dung'      => 'Nạp tiền qua ngân hàng',
                'hash'         => 'GDPHIM_10',
                'is_thanh_toan' => 0,
                'created_at'    => now(),
            ],
        ]);
    }
}
