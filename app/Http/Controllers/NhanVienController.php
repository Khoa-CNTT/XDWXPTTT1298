<?php

namespace App\Http\Controllers;

use App\Http\Requests\NhanVienCreateRequest;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NhanVienController extends Controller
{
    public function create(NhanVienCreateRequest $request){
        NhanVien::create([
            'ho_va_ten'         =>$request->ho_va_ten,
            'email'             =>$request->email,
            'ngay_sinh'         =>$request->ngay_sinh,
            'so_dien_thoai'     =>$request->so_dien_thoai,
            'password'          =>$request->password,
            'id_quyen'          =>$request->id_quyen,
            'tinh_trang'        =>$request->tinh_trang,
        ]);
        return response()->json([
            'status'    =>1,
            'message'   =>'Đã thêm nhân viên ' . $request->ho_va_ten . ' thành công!'
        ]);
    }
    public function getData(Request $request){
        $data = NhanVien::get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function xoaNhanVien(Request $request){
        NhanVien::where('id', $request->id)->delete();
        return response()->json([
            'status'    =>  true,
            'message'   =>  'Bạn đã xóa khách hàng ' . $request->ho_va_ten . ' thành công'
        ]);
    }
    public function capNhatNhanVien(Request $request){
        NhanVien::where('id', $request->id)->update([
            'ho_va_ten'     => $request->ho_va_ten,
            'email'         => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'password'      => $request->password,
            'id_quyen'      => $request->id_quyen,
            'ngay_sinh'     => $request->ngay_sinh,
        ]);
        return response()->json([
            'status'    =>  1,
            'message'   =>  'Đã cập nhật khách hàng ' . $request->ho_va_ten . ' thành công'
        ]);
    }
    public function changeStatus(Request $request){
        $nhanVien = NhanVien::where('id', $request->id)->first();

        if ($nhanVien->tinh_trang == 1) {
            $nhanVien->tinh_trang = 0;
            $nhanVien->save();
        } else {
            $nhanVien->tinh_trang = 1;
            $nhanVien->save();
        }
        return response()->json([
            'status'    =>  true,
            'message'   =>  'Bạn đã cập nhật nhân viên ' . $request->ho_va_ten . ' thành công'
        ]);
    }
    public function timKiem(Request $request){
        $noi_dung = '%'. $request->noi_dung . '%';
        $noi_dung = '%' . $request->noi_dung . '%';

        $data = NhanVien::where('ho_va_ten', 'like', $noi_dung)
                        ->orwhere('email', 'like', $noi_dung)
                        ->orwhere('so_dien_thoai', 'like', $noi_dung)
                        ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function dangNhap(Request $request){
        $user = NhanVien::where('email', $request->email)
                         ->where('password', $request->password)
                         ->first();

        if ($user) {
            return response()->json([
                'status'    => 1,
                'message'   => 'Đăng nhập thành công',
                'token'     => $user->createToken('key_admin')->plainTextToken
            ]);
        } else {
            return response()->json([
                'status'    => 0,
                'message'   => 'Tài khoản hoặc mật khẩu không đúng',
            ]);
        }
    }
    public function checkLogin(){
        $user = Auth::guard('sanctum')->user();
        if($user && $user instanceof \App\Models\NhanVien){
            return response()->json([
                'status'    => 1,
                'name'      =>$user->ho_va_ten,
                'email'      =>$user->email
            ]);
        }else {
            return response()->json([
                'status'    => 0,
            ]);
        }
    }
}
