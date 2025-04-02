<?php

namespace App\Http\Controllers;

use App\Http\Requests\KhachHangCapNhatRequest;
use App\Http\Requests\KhachHangDangKyRequest;
use App\Http\Requests\KhachHangXoaRequest;
use App\Models\KhachHang;
use Illuminate\Http\Request;

class KhachHangController extends Controller
{
    public function dangKy(KhachHangDangKyRequest $request){
        $khachHang = KhachHang::create([
            'ho_va_ten'         =>$request->ho_va_ten,
            'email'             =>$request->email,
            'password'          =>$request->password,
            'ngay_sinh'         =>$request->ngay_sinh,
            'so_dien_thoai'     =>$request->so_dien_thoai,
        ]);
        return response()->json([
            'status'    =>1,
            'message'   =>'Đã đăng ký khách hàng ' . $request->ho_va_ten . ' thành công!'
        ]);
    }
    public function getData(Request $request){
        $data = KhachHang::get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function xoaKhachHang(KhachHangXoaRequest $request){
        KhachHang::where('id', $request->id)->delete();
        return response()->json([
            'status'    =>1,
            'message'   =>'Đã xóa khách hàng '. $request->ho_va_ten .' thành công!'
        ]);
    }
    public function capNhatKhachHang(KhachHangCapNhatRequest $request){
        KhachHang::where('id', $request->id)->update([
            'ho_va_ten'     => $request->ho_va_ten,
            'email'         => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'ngay_sinh'     => $request->ngay_sinh,
        ]);
        return response()->json([
            'status'    =>  1,
            'message'   =>  'Đã cập nhật khách hàng ' . $request->ho_va_ten . ' thành công'
        ]);
    }
    public function changeStatus(Request $request){
        $khachHang = KhachHang::where('id', $request->id)->first();

        if($khachHang->is_block == 1) {
            $khachHang->is_block = 0;
            $khachHang->save();
        }else{
            $khachHang->is_block = 1;
            $khachHang->save();
        }
            return response()->json([
                'status'    =>1,
                'message'   =>'Đã cập nhật khách hàng '. $request->ho_va_ten .' thành công!'
            ]);

    }
    public function timKiem(Request $request){
        $noi_dung = '%'. $request->noi_dung . '%';

        $data = KhachHang::where('ho_va_ten', 'like', $noi_dung)
            ->orWhere('email', 'like', $noi_dung)
            ->orWhere('so_dien_thoai', 'like', $noi_dung)
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
