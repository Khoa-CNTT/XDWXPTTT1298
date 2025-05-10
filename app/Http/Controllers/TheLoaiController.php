<?php

namespace App\Http\Controllers;

use App\Http\Requests\TheLoaiCapNhatRequest;
use App\Http\Requests\TheLoaiCreateRequest;
use App\Http\Requests\TheLoaiXoaRequest;
use App\Models\ChiTietPhanQuyen;
use App\Models\TheLoai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TheLoaiController extends Controller
{
    public function create(TheLoaiCreateRequest $request){
        $id_chuc_nang = 15;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        TheLoai::create([
            'ten_the_loai' => $request->ten_the_loai,
            'slug_the_loai' => $request->slug_the_loai,
            'tinh_trang' => $request->tinh_trang,
        ]);
        return response()->json([
            'status' => 1,
            'message' => 'Đã thêm thể loại '. $request->ten_the_loai .' thành công'
        ]);
    }
    public function getData(){
        $id_chuc_nang = 16;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        $data = TheLoai::get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function xoaTheLoai(TheLoaiXoaRequest $request){
        $id_chuc_nang = 17;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        TheLoai::where('id', $request->id)->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Đã xóa thể loại '. $request->ten_the_loai .' thành công'
        ]);
    }
    public function capNhatTheLoai(TheLoaiCapNhatRequest $request){
        $id_chuc_nang = 18;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        TheLoai::where('id', $request->id)->update([
            'ten_the_loai' => $request->ten_the_loai,
            'slug_the_loai' => $request->slug_the_loai,
            'tinh_trang' => $request->tinh_trang,
        ]);
        return response()->json([
            'status' => 1,
            'message' => 'Đã cập nhật thể loại '. $request->ten_the_loai .' thành công'
        ]);
    }
    public function changeStatus(Request $request){
        $id_chuc_nang = 19;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        $theLoai = TheLoai::where('id', $request->id)->first();

        if ($theLoai->tinh_trang == 1) {
            $theLoai->tinh_trang = 0;
            $theLoai->save();
        } else {
            $theLoai->tinh_trang = 1;
            $theLoai->save();
        }
        return response()->json([
            'status'    =>  true,
            'message'   =>  'Đã cập nhật thể loại ' . $request->ten_the_loai . ' thành công'
        ]);
    }
    public function dataOpen(){
        $id_chuc_nang = 20;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        $data = TheLoai::where('tinh_trang', 0)->get();
        return response()->json([
            'data' => $data
        ]);
    }


    // Client
    public function dataTheLoai(){
        $data = TheLoai::where('tinh_trang', 0)->get();
        return response()->json([
            'data' => $data
        ]);
    }
}
