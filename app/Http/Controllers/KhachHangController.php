<?php

namespace App\Http\Controllers;

use App\Http\Requests\KhachHangCapNhatRequest;
use App\Http\Requests\KhachHangDangKyRequest;
use App\Http\Requests\KhachHangXoaRequest;
use App\Models\KhachHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function dangNhap(Request $request){
        $user = KhachHang::where('email', $request->email)
            ->where('password', $request->password)
            ->first();
        if($user){
            if($user->is_active == 0){
                return response()->json([
                    'status'    => 0,
                    'message'   => 'Tài khoản của bạn chưa được kích hoạt!'
                ]);
            }else{
                return response()->json([
                    'status'    => 1,
                    'message'   => 'Đăng nhập thành công!',
                    'token'     => $user->createToken('key_khachhang')->plainTextToken,
                ]);
            }
        }else{
            return response()->json([
                'status'    => 0,
                'message'   => 'Tài khoản hoặc mật khẩu không đúng!'
            ]);
        }
    }
    public function checkLogin(){
        $user = Auth::guard('sanctum')->user();
        if($user && $user instanceof \App\Models\KhachHang){
            return response()->json([
                'status'    => 1,
                'name'      =>$user->ho_va_ten,
                'email'     =>$user->email,
                'so_du'     =>$user->so_du,
            ]);
        }else{
                return response()->json([
                    'status'    => 0,
                ]);
        }
    }
}
