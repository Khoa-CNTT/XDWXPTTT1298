<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class KhachHangSeeder extends Seeder
{
    public function run(): void
    {
        // $startDate  = Carbon::create(2024, 10, 1);
        // $endDate    = Carbon::create(2024, 12, 1);
        DB::table('khach_hangs')->delete();

        DB::table('khach_hangs')->insert([
            [
                'ho_va_ten'     => 'Trần Gia Kiệt',
                'email'         => 'kietdk109@gmail.com',
                'password'      => '123456',
                'ngay_sinh'     => '2003-11-01',
                'so_du'         => '40000',
                'so_dien_thoai' => '0357250453',
                'is_active'     => 1,
                'created_at'    => '2024-10-01 00:00:00',
            ],
            [
                'ho_va_ten'     => 'Nguyễn Văn A',
                'email'         => 'nguyenvana@example.com',
                'password'      => '123456',
                'ngay_sinh'     => '1995-05-15',
                'so_du'         => '10000',
                'so_dien_thoai' => '0351234567',
                'is_active'     => 1,
                'created_at'    => '2023-10-01 00:00:00',
            ],
            [
                'ho_va_ten'     => 'Lê Thị B',
                'email'         => 'lethib@example.com',
                'password'      => '123456',
                'ngay_sinh'     => '1998-08-20',
                'so_du'         => '10000',
                'so_dien_thoai' => '0352345678',
                'is_active'     => 1,
                'created_at'    => '2025-10-01 00:00:00',
            ],
            [
                'ho_va_ten'     => 'Phạm Văn C',
                'email'         => 'phamvanc@example.com',
                'password'      => '123456',
                'ngay_sinh'     => '2000-12-10',
                'so_du'         => '10000',
                'so_dien_thoai' => '0353456789',
                'is_active'     => 1,
                'created_at'    => '2024-10-01 00:00:00',
            ],
            [
                'ho_va_ten'     => 'Trần Thị D',
                'email'         => 'tranthid@example.com',
                'password'      => '123456',
                'ngay_sinh'     => '2002-03-25',
                'so_du'         => '0',
                'so_dien_thoai' => '0354567890',
                'is_active'     => 1,
                'created_at'    => '2025-10-01 00:00:00',
            ],
            [
                'ho_va_ten'     => 'Đỗ Văn E',
                'email'         => 'dovane@example.com',
                'password'      => '123456',
                'ngay_sinh'     => '1997-07-30',
                'so_du'         => '0',
                'so_dien_thoai' => '0355678901',
                'is_active'     => 1,
                'created_at'    => '2024-10-01 00:00:00',
            ],
        ]);
    }
}
