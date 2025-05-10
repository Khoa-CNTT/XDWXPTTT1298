<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhanQuyenSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('phan_quyens')->delete();
        DB::table('phan_quyens')->truncate();
        DB::table('phan_quyens')->insert([
            ['id' => '1', 'ten_quyen' => 'Admin'],
            ['id' => '2', 'ten_quyen' => 'Quản Lý'],
            ['id' => '3', 'ten_quyen' => 'Nhân Viên'],
            ['id' => '4', 'ten_quyen' => 'Kế Toán'],
            ['id' => '5', 'ten_quyen' => 'Kiểm Duyệt Phim'],
        ]);
    }
}
