<?php

namespace App\Http\Controllers;

use App\Http\Requests\TheLoaiCapNhatRequest;
use App\Http\Requests\TheLoaiCreateRequest;
use App\Http\Requests\TheLoaiXoaRequest;
use App\Models\TheLoai;
use Illuminate\Http\Request;

class TheLoaiController extends Controller
{
    public function create(TheLoaiCreateRequest $request){
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
        $data = TheLoai::get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function xoaTheLoai(TheLoaiXoaRequest $request){
        TheLoai::where('id', $request->id)->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Đã xóa thể loại '. $request->ten_the_loai .' thành công'
        ]);
    }
    public function capNhatTheLoai(TheLoaiCapNhatRequest $request){
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
        $data = TheLoai::where('tinh_trang', 0)->get();
        return response()->json([
            'data' => $data
        ]);
    }
}
