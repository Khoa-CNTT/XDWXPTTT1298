<?php

namespace App\Http\Controllers;

use App\Http\Requests\CapNhatThongTinRequest;
use App\Http\Requests\DoiMatKhauRequest;
use App\Http\Requests\KhachHangCapNhatRequest;
use App\Http\Requests\KhachHangDangKyRequest;
use App\Http\Requests\KhachHangDoiMatKhauRequest;
use App\Http\Requests\KhachHangXoaRequest;
use App\Models\ChiTietPhanQuyen;
use App\Models\ChiTietPhim;
use App\Models\KhachHang;
use App\Models\TaiChinh;
use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KhachHangController extends Controller
{
    public function dangKy(KhachHangDangKyRequest $request){

        $hash_active = Str::uuid();
        $khachHang = KhachHang::create([
            'ho_va_ten'         =>$request->ho_va_ten,
            'email'             =>$request->email,
            'password'          =>$request->password,
            'ngay_sinh'         =>$request->ngay_sinh,
            'so_dien_thoai'     =>$request->so_dien_thoai,
            'hash_active'       =>$hash_active,
        ]);

        $data['ho_va_ten'] = $request->ho_va_ten;
        $data['link_kich_hoat']  = 'http://localhost:5173/kich-hoat/'. $khachHang->hash_active;
        Mail::to($request->email)->send(new \App\Mail\MasterMail('Phim Tổng Hợp', 'kichhoat', $data));

        return response()->json([
            'status'    =>1,
            'message'   =>'Đã đăng ký khách hàng ' . $request->ho_va_ten . ' thành công!'
        ]);
    }
    public function getData(Request $request){
        $id_chuc_nang = 1;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        $data = KhachHang::get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function xoaKhachHang(KhachHangXoaRequest $request){
        $id_chuc_nang = 2;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        KhachHang::where('id', $request->id)->delete();
        return response()->json([
            'status'    =>1,
            'message'   =>'Đã xóa khách hàng '. $request->ho_va_ten .' thành công!'
        ]);
    }
    public function capNhatKhachHang(KhachHangCapNhatRequest $request){
        $id_chuc_nang = 3;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
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
        $id_chuc_nang = 4;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }

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
            }else if($user->is_block == 1){
                return response()->json([
                    'status'    => 0,
                    'message'   => 'Tài khoản của bạn đã bị khóa, hãy liên hệ với chúng tôi để được hỗ trợ!'
                ]);
            }else{
                return response()->json([
                    'status'    => 1,
                    'message'   => 'Đăng nhập thành công!',
                    'token'     => $user->createToken('key_khachhang')->plainTextToken,
                    'id'        => $user->id,
                ]);
            }
        }else{
            return response()->json([
                'status'    => 0,
                'message'   => 'Tài khoản hoặc mật khẩu không đúng!'
            ]);
        }
    }

    public function loginGoogle(Request $request){

        $client = new Google_Client(['client_id' => env("CLIENT_ID ")]);

        $payload = $client->verifyIdToken($request->credential);

        if($payload){
            $ho_va_ten  = $payload['name'];
            $email      = $payload['email'];

            $user = KhachHang::where('email', $email )->first();

        if($user) {
            if ($user) {
                return response()->json([
                    'status'    => 1,
                    'message'   => 'Đăng nhập thành công',
                    'token'     =>$user->createToken('key_khachhang')->plainTextToken,
                    'id'        =>$user->id
                ]);
            }
        }else{
            $khachHang = KhachHang::create([
                'ho_va_ten'         =>$ho_va_ten,
                'email'             =>$email ,
                'password'          =>'123456',
                'ngay_sinh'         =>'1999-01-01',
                'so_dien_thoai'     =>'0123456789',
                'is_active'         =>1
            ]);
            return response()->json([
                'status'  => 1,
                'message' => 'Bạn Đăng Ký Tài Khoản  ' . $email  . '  Thành Công',
                'key'       => $khachHang->createToKen('key_khachhang')->plainTextToken,
                'id'        => $khachHang->id
            ]);
        }
        }else {
            return response()->json([
                'status'  => 0,
                'message' => 'Đã có lỗi xảy ra!',
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
    public function kichHoatTaiKhoan(Request $request){
        $khachHang = KhachHang::where('hash_active', $request->id_khach_hang)->first();
        if($khachHang){
            if($khachHang->is_active == 1){
                return response()->json([
                    'status'    => 2,
                    'message'   => 'Tài khoản đã được kích hoạt!'
                ]);
            }else{
                $khachHang->is_active = 1;
                $khachHang->save();
                return response()->json([
                    'status'    => 1,
                    'message'   => 'Kích hoạt tài khoản thành công!'
                ]);
            }
        }else{
            return response()->json([
                'status'    => 0,
                'message'   => 'Kích hoạt tài khoản thất bại!'
            ]);
        }
    }
    public function dangXuat(){
        $user = Auth::guard('sanctum')->user();
        if($user && $user instanceof \App\Models\KhachHang){
            DB::table('personal_access_tokens')
                ->where('id', $user->currentAccessToken()->id)
                ->delete();
            return response()->json([
                'status'    => 1,
                'message'   => 'Đăng xuất thành công!'
            ]);
        }else{
            return response()->json([
                'status'    => 0,
                'message'   => 'Có lỗi xảy ra!'
            ]);
        }
    }
    public function quenMatKhau(Request $request){
        $user = KhachHang::where('email', $request->email)->first();
        if($user){
            $hash_reset = Str::uuid();
            $user->hash_reset = $hash_reset;
            $user->save();

            $data['ho_va_ten'] = $user->ho_va_ten;
            $data['link']  = 'http://localhost:5173/quen-mat-khau/'. $hash_reset;

            Mail::to($request->email)->send(new \App\Mail\MasterMail('Phim Tổng Hợp', 'quenmatkhau', $data));

            return response()->json([
                'status'    => 1,
                'message'   => 'Vui lòng kiểm tra email của bạn!'
            ]);
        }else {
            return response()->json([
                'status'    => 0,
                'message'   => 'Tài khoản không tồn tại!'
            ]);
        }
    }
    public function doiMatKhau(KhachHangDoiMatKhauRequest $request){
        $user = KhachHang::where('hash_reset', $request->hash_reset)->first();

        if($user){
            $user->password = $request->password;
            $user->hash_reset = null;
            $user->save();

            return response()->json([
                'status'  => 1,
                'message' => "Đặt lại tài khoản thành công!",
            ]);
        }
        else{
            return response()->json([
                'status'  => 0,
                'message' => "Mã đổi mật khẩu không tồn tại!",
            ]);
        }
    }
    public function layThongTin(){
        $user = Auth::guard('sanctum')->user();
        if ($user && $user instanceof \App\Models\KhachHang) {
            return response()->json([
                'status'  => 1,
                'thong_tin'    => $user,
            ]);
        } else {
            return response()->json([
                'status'  => 0,
                'message' => "Có lỗi xảy ra",
            ]);
        }
    }
    public function capNhatThongTin(CapNhatThongTinRequest $request){
        $user = Auth::guard('sanctum')->user();
        if ($user && $user instanceof \App\Models\KhachHang) {
            $user->ho_va_ten = $request->ho_va_ten;
            $user->so_dien_thoai = $request->so_dien_thoai;
            $user->ngay_sinh = $request->ngay_sinh;
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
    public function doiMatKhauProfile(DoiMatKhauRequest $request){
        $user = Auth::guard('sanctum')->user();
        if ($user && $user instanceof \App\Models\KhachHang) {
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

    public function napTienTK(Request $request){
        $user = Auth::guard('sanctum')->user();
        $tai_chinh = TaiChinh::create([
            'id_khach_hang' => $user->id,
            'id_nhan_vien' => 1,
            'so_tien_nap'   => $request->so_tien_nap,
            'kieu_nap'      => \App\Models\TaiChinh::NAP_QUA_BANK,
            'noi_dung'  => "Nạp tiền qua ngân hàng",
        ]);
        $ma_giao_dich = "GDPHIM_" . ( 000 + $tai_chinh->id);

        $tai_chinh->update([
            'hash' => $ma_giao_dich
        ]);

        return response()->json([
            'status'    => 1,
            'message'   => 'Bạn đã tạo lệnh nạp tiền, vui lòng thanh toán!',
            'noi_dung_chuyen_khoan' => $ma_giao_dich
        ]);
    }

    public function lichSuNap(){
        $user = Auth::guard('sanctum')->user();
        if ($user && $user instanceof \App\Models\KhachHang) {
            $data = TaiChinh::select('nhan_viens.ho_va_ten as hoten_nv','khach_hangs.ho_va_ten as hoten_kh','khach_hangs.email','tai_chinhs.so_tien_nap','tai_chinhs.kieu_nap','tai_chinhs.noi_dung','tai_chinhs.created_at')
                ->join('khach_hangs','khach_hangs.id','tai_chinhs.id_khach_hang')
                ->join('nhan_viens','nhan_viens.id','tai_chinhs.id_nhan_vien')
                ->where('tai_chinhs.id_khach_hang',$user->id)
                ->where('tai_chinhs.is_thanh_toan',1)
                ->orderBy('tai_chinhs.created_at', 'desc')
                ->get();
            return response()->json([
                'status'    => 1,
                'data'      => $data
            ]);
        } else {
            return response()->json([
                'status'  => 0,
                'message' => "Có lỗi xảy ra",
            ]);
        }
    }

    public function danhSachPhim(){
        $user = Auth::guard('sanctum')->user();
        $data = ChiTietPhim::select('phims.ten_phim', 'chi_tiet_phims.so_tien_mua', 'chi_tiet_phims.created_at')
                            ->join('phims', 'phims.id', '=', 'chi_tiet_phims.id_phim')
                            ->join('khach_hangs', 'khach_hangs.id', '=', 'chi_tiet_phims.id_khach_hang')
                            ->where('chi_tiet_phims.id_khach_hang', $user->id)
                            ->orderBy('chi_tiet_phims.created_at', 'desc')
                            ->get();
        return response()->json([
            'data' => $data
        ]);
    }
}
