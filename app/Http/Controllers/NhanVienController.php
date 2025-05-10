<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminDoiMatKhauProfileRequest;
use App\Http\Requests\layThongTinProfileAdminRequest;
use App\Http\Requests\NhanVienCapNhatRequest;
use App\Http\Requests\NhanVienCreateRequest;
use App\Http\Requests\NhanVienXoaRequest;
use App\Models\ChiTietPhanQuyen;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NhanVienController extends Controller
{
    public function create(NhanVienCreateRequest $request){
        $id_chuc_nang = 5;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }

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
    public function getData(){
        $id_chuc_nang = 6;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }

        $data = NhanVien::query()->join('phan_quyens', 'phan_quyens.id', 'nhan_viens.id_quyen')
        ->select('nhan_viens.*', 'phan_quyens.ten_quyen')
        ->get();
        return response()->json([
        'data' => $data
        ]);
    }
    public function xoaNhanVien(NhanVienXoaRequest $request){
        $id_chuc_nang = 7;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        NhanVien::where('id', $request->id)->delete();
        return response()->json([
            'status'    =>  true,
            'message'   =>  'Đã xóa khách hàng ' . $request->ho_va_ten . ' thành công'
        ]);
    }
    public function capNhatNhanVien(NhanVienCapNhatRequest $request){
        $id_chuc_nang = 8;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
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
        $id_chuc_nang = 9;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
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
            'message'   =>  'Đã cập nhật nhân viên ' . $request->ho_va_ten . ' thành công'
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
                'message'   => 'Đăng nhập thành công!',
                'token'     => $user->createToken('key_admin')->plainTextToken
            ]);
        } else if ($user->tinh_trang == 1) {
            return response()->json([
                'status'    => 0,
                'message'   => 'Tài khoản đã bị khóa, hãy liên hệ với quản trị viên!',
            ]);
        }else {
            return response()->json([
                'status'    => 0,
                'message'   => 'Tài khoản hoặc mật khẩu không đúng!',
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
    public function dangXuat(){
        $user = Auth::guard('sanctum')->user();
        if ($user && $user instanceof \App\Models\NhanVien) {
            DB::table('personal_access_tokens')
                ->where('id', $user->currentAccessToken()->id)
                ->delete();
            return response()->json([
                'status'  => 1,
                'message' => "Đăng xuất thành công",
            ]);
        } else {
            return response()->json([
                'status'  => 0,
                'message' => "Có lỗi xảy ra",
            ]);
        }
    }

    public function layThongTinProfile()
    {
        $admin = Auth::guard('sanctum')->user();
        if ($admin && $admin instanceof \App\Models\NhanVien) {
            $data = NhanVien::join('phan_quyens', 'nhan_viens.id_quyen', 'phan_quyens.id')
                ->select('nhan_viens.*', 'phan_quyens.ten_quyen')
                ->where('nhan_viens.id', $admin->id)
                ->first();
            return response()->json([
                'status'  => 1,
                'thong_tin'    => $data,
            ]);
        } else {
            return response()->json([
                'status'  => 0,
                'message' => "Có lỗi xảy ra",
            ]);
        }
    }
    public function thaydoiProfile(layThongTinProfileAdminRequest $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user instanceof \App\Models\NhanVien) {
            $user->ho_va_ten = $request->ho_va_ten;
            $user->so_dien_thoai = $request->so_dien_thoai;
            $user->save();
            return response()->json([
                'status'  => 1,
                'message' => "Thay Đổi Thông Tin Thành Công",
            ]);
        } else {
            return response()->json([
                'status'  => 0,
                'message' => "Có lỗi xảy ra",
            ]);
        }
    }
    public function changePasswordProfile(AdminDoiMatKhauProfileRequest $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user instanceof \App\Models\NhanVien) {
            if ($request->old_password != $user->password) {
                return response()->json([
                    'status'  => 0,
                    'message' => "Mật khẩu cũ không đúng",
                ]);
            }
            $user->password = $request->new_password;
            $user->save();
            return response()->json([
                'status'  => 1,
                'message' => "Đổi Mật Khẩu Thành Công",
            ]);
        } else {
            return response()->json([
                'status'  => 0,
                'message' => "Có lỗi xảy ra",
            ]);
        }
    }
}
