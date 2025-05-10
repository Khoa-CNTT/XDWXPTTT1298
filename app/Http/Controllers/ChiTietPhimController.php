<?php

namespace App\Http\Controllers;

use App\Models\ChiTietPhim;
use App\Models\KhachHang;
use App\Models\Phim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChiTietPhimController extends Controller
{
    public function chiTietPhim($id){
        $data = Phim::where('id', $id)
                    ->where('trang_thai', 0)
                    ->first();
        if($data){
            return response()->json([
                'status'    =>  1,
                'data'      =>  $data
            ]);
        }else{
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Không tìm thấy phim!'
            ]);
        }
    }
    public function store(Request $request){
        $so_tien_mua = Phim::where('id', $request->id_phim)->first()->gia_ban;
        $user = Auth::guard('sanctum')->user();
        $check = ChiTietPhim::where('id_phim', $request->id_phim)
                     ->where('id_khach_hang', $user->id)
                     ->first();
        if($check){
            return response()->json([
                'status' => 0,
                'message' => 'Bạn đã mua phim này rồi!'
            ]);
        }else {
            if($user->so_du >= $so_tien_mua){
                $khachHang = KhachHang::where('id', $user->id)->first();
                $khachHang->so_du = $khachHang->so_du - $so_tien_mua;
                $khachHang->save();

                ChiTietPhim::create([
                    'id_phim' => $request->id_phim,
                    'id_khach_hang' => $user->id,
                    'so_tien_mua' => $request->so_tien_mua
                ]);

                return response()->json([
                    'status' => 1,
                    'message' => 'Mua phim thành công!'
                ]);
            }else {
                return response()->json([
                    'status' => 2,
                    'message' => 'Số dư không đủ để mua phim này!'
                ]);
            }
        }

    }

}
