<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TheLoaiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('the_loais')->delete();
        DB::table('the_loais')->insert([
            [
                'ten_the_loai'  => 'Kinh Dị',
                'slug_the_loai' => 'kinh-di',
                'tinh_trang'    => 0
            ],
            [
                'ten_the_loai'  => 'Lãng Mạn',
                'slug_the_loai' => 'lang-man',
                'tinh_trang'    => 0
            ],
            [
                'ten_the_loai'  => 'Hài Hước',
                'slug_the_loai' => 'hai-huoc',
                'tinh_trang'    => 0
            ],
            [
                'ten_the_loai'  => 'Phiêu Lưu',
                'slug_the_loai' => 'phieu-luu',
                'tinh_trang'    => 0
            ],
            [
                'ten_the_loai'  => 'Viễn Tưởng',
                'slug_the_loai' => 'vien-tuong',
                'tinh_trang'    => 0
            ],
            [
                'ten_the_loai'  => 'Lịch Sử',
                'slug_the_loai' => 'lich-su',
                'tinh_trang'    => 0
            ],
            [
                'ten_the_loai'  => 'Tâm Lý',
                'slug_the_loai' => 'tam-ly',
                'tinh_trang'    => 0
            ],
            [
                'ten_the_loai'  => 'Giả Tưởng',
                'slug_the_loai' => 'gia-tuong',
                'tinh_trang'    => 0
            ],
            [
                'ten_the_loai'  => 'Hành Động',
                'slug_the_loai' => 'hanh-dong',
                'tinh_trang'    => 0
            ],
            [
                'ten_the_loai'  => 'Thần Thoại',
                'slug_the_loai' => 'than-thoai',
                'tinh_trang'    => 0
            ],
        ]);
    }
}
