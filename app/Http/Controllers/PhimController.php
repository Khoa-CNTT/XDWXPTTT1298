<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhimCreateRequest;
use App\Models\Phim;
use Illuminate\Http\Request;

class PhimController extends Controller
{
    public function create(PhimCreateRequest $request){
        Phim::create([
            'ten_phim'          =>$request->ten_phim,
            'slug_phim'         =>$request->slug_phim,
            'id_the_loai'       =>$request->id_the_loai,
            'loai_phim'         =>$request->loai_phim,
            'hinh_anh'          =>$request->hinh_anh,
            'trailer'           =>$request->trailer,
            'link_phim'         =>$request->link_phim,
            'thoi_luong'        =>$request->thoi_luong,
            'dao_dien'          =>$request->dao_dien,
            'dien_vien'         =>$request->dien_vien,
            'quoc_gia'          =>$request->quoc_gia,
            'ngay_khoi_chieu'   =>$request->ngay_khoi_chieu,
            'ngay_ket_thuc'     =>$request->ngay_ket_thuc,
            'mo_ta'             =>$request->mo_ta,
            'gia_ban'           =>$request->gia_ban,
            'trang_thai'        =>$request->trang_thai,
        ]);
        return response()->json([
            'status' => 1,
            'message' => 'Đã thêm phim '. $request->ten_phim .' thành công'
        ]);
    }
    public function getData(){
        $data = Phim::get();
        return response()->json([
            'data' => $data
        ]);
    }
}
