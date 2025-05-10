<?php

namespace App\Http\Controllers;

use App\Models\KhachHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function chartTaiChinh(){
        $data = KhachHang::join('tai_chinhs', 'tai_chinhs.id_khach_hang', 'khach_hangs.id')
            ->select(
                'khach_hangs.id',
                'khach_hangs.ho_va_ten',
                DB::raw(
                    'SUM(tai_chinhs.so_tien_nap) AS tong_tien'
                )
            )
            ->groupBy('khach_hangs.id', 'khach_hangs.ho_va_ten')
            ->where('tai_chinhs.is_thanh_toan', 1)
            ->get();


        $labels = [];
        $data_x   = [];
        foreach ($data as $key => $value) {
            array_push($labels, $value->ho_va_ten);
            array_push($data_x,   $value->tong_tien);
        }
        return response()->json([
            'labels' => $labels,
            'data_x' => $data_x,
        ]);
    }
    public function chartKhachHang(){
        $data = KhachHang::select(
            DB::raw('YEAR(created_at) as nam'),
            DB::raw('COUNT(*) as tong_khach_hang')
            )
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->get();

        $labels = [];
        $data_x = [];
        foreach ($data as $value) {
            array_push($labels, $value->nam);
            array_push($data_x, $value->tong_khach_hang);
        }

        return response()->json([
            'labels' => $labels,
            'data_x' => $data_x,
        ]);
    }
    public function chartPhim(){
        $data = DB::table('phims')
            ->select(
            DB::raw('YEAR(created_at) as nam'),
            DB::raw('COUNT(*) as tong_phim')
            )
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->get();

        $labels = [];
        $data_x = [];
        foreach ($data as $value) {
            array_push($labels, $value->nam);
            array_push($data_x, $value->tong_phim);
        }

        return response()->json([
            'labels' => $labels,
            'data_x' => $data_x,
        ]);
    }
    public function chartTheLoai(){
        $data = DB::table('the_loais')
            ->join('phims', 'phims.id_the_loai', '=', 'the_loais.id')
            ->select(
                'the_loais.ten_the_loai',
                DB::raw('COUNT(phims.id) as tong_phim')
            )
            ->groupBy('the_loais.ten_the_loai')
            ->get();

        $labels = [];
        $data_x = [];
        foreach ($data as $value) {
            array_push($labels, $value->ten_the_loai);
            array_push($data_x, $value->tong_phim);
        }

        return response()->json([
            'labels' => $labels,
            'data_x' => $data_x,
        ]);
    }
}
