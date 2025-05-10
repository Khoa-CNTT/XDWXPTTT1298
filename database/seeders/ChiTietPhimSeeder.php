<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChiTietPhimSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('chi_tiet_phims')->delete();
        DB::table('chi_tiet_phims')->insert([
            [
                'id_phim' => 20,
                'id_khach_hang' => 1,
                'so_tien_mua' => 5000,
                'created_at' => now(),
            ],
            [
                'id_phim' => 22,
                'id_khach_hang' => 1,
                'so_tien_mua' => 5000,
                'created_at' => now(),
            ],
            [
                'id_phim' => 25,
                'id_khach_hang' => 1,
                'so_tien_mua' => 5000,
                'created_at' => now(),
            ],
            [
                'id_phim' => 10,
                'id_khach_hang' => 2,
                'so_tien_mua' => 5000,
                'created_at' => now(),
            ],
            [
                'id_phim' => 4,
                'id_khach_hang' => 2,
                'so_tien_mua' => 5000,
                'created_at' => now(),
            ],
            [
                'id_phim' => 17,
                'id_khach_hang' => 2,
                'so_tien_mua' => 5000,
                'created_at' => now(),
            ],
            [
                'id_phim' => 29,
                'id_khach_hang' => 2,
                'so_tien_mua' => 5000,
                'created_at' => now(),
            ],
            [
                'id_phim' => 10,
                'id_khach_hang' => 3,
                'so_tien_mua' => 5000,
                'created_at' => now(),
            ],
            [
                'id_phim' => 2,
                'id_khach_hang' => 3,
                'so_tien_mua' => 5000,
                'created_at' => now(),
            ],
            [
                'id_phim' => 21,
                'id_khach_hang' => 3,
                'so_tien_mua' => 5000,
                'created_at' => now(),
            ],
            
        ]);
    }
}
