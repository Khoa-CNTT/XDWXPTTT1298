<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            KhachHangSeeder::class,
            NhanVienSeeder::class,
            TheLoaiSeeder::class,
            PhimSeeder::class,
            TaiChinhSeeder::class,
            ChucNangSeeder::class,
            PhanQuyenSeeder::class,
            ChiTietPhanQuyenSeeder::class,
        ]);
    }
}
