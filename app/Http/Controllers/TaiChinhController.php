<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaiChinhRequest;
use App\Models\ChiTietPhanQuyen;
use App\Models\KhachHang;
use App\Models\TaiChinh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaiChinhController extends Controller
{
    public function napTien(TaiChinhRequest $request ){
        $id_chuc_nang = 21;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        $user = Auth::guard('sanctum')->user();
        TaiChinh::create([
            'id_khach_hang' => $request->id,
            'id_nhan_vien'  => $user->id,
            'so_tien_nap'   => $request->so_tien_can_nap,
            'kieu_nap'      => \App\Models\TaiChinh::NAP_BANG_TAY,
            'noi_dung'      => $request->ly_do_nap_tien,
            'is_thanh_toan' => 0,
        ]);
        $KhachHang = KhachHang::where('id',$request->id)->first();
        $KhachHang->save();

        return response()->json([
            'status'    =>1,
            'message'   =>'Đã nạp tiền ' . number_format($request->so_tien_can_nap) . ' thành công. Yêu cầu này sẽ được duyệt sớm!'
        ]);
    }
    public function getData()
    {
        $id_chuc_nang = 22;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        $data = TaiChinh::select('nhan_viens.ho_va_ten as hoten_nv','khach_hangs.ho_va_ten as hoten_kh','khach_hangs.email','tai_chinhs.so_tien_nap','tai_chinhs.kieu_nap','tai_chinhs.noi_dung','tai_chinhs.created_at')
                        ->join('khach_hangs','khach_hangs.id','tai_chinhs.id_khach_hang')
                        ->join('nhan_viens','nhan_viens.id','tai_chinhs.id_nhan_vien')
                        ->where('tai_chinhs.is_thanh_toan',1)
                        ->orderBy('tai_chinhs.updated_at', 'desc')
                        ->get();
        return response()->json([
            'data'  =>$data
        ]);
    }
    public function getDataOnePerson(Request $request)
    {
        $id_chuc_nang = 23;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        $data = TaiChinh::select('nhan_viens.ho_va_ten as hoten_nv','khach_hangs.ho_va_ten as hoten_kh','khach_hangs.email','tai_chinhs.so_tien_nap','tai_chinhs.kieu_nap','tai_chinhs.noi_dung','tai_chinhs.created_at')
                        ->join('khach_hangs','khach_hangs.id','tai_chinhs.id_khach_hang')
                        ->join('nhan_viens','nhan_viens.id','tai_chinhs.id_nhan_vien')
                        ->where('tai_chinhs.id_khach_hang',$request->id)
                        ->where('tai_chinhs.is_thanh_toan',1)
                        ->get();
        return response()->json([
            'data'  =>$data
        ]);
    }

    public function dsNapTienDuyet(Request $request){
        $id_chuc_nang = 24;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        $data = TaiChinh::join('khach_hangs','khach_hangs.id','tai_chinhs.id_khach_hang')
                        ->join('nhan_viens','nhan_viens.id','tai_chinhs.id_nhan_vien')
                        ->select('tai_chinhs.*','khach_hangs.ho_va_ten as hoten_kh','nhan_viens.ho_va_ten as hoten_nv')
                        ->where('tai_chinhs.is_thanh_toan',0)
                        ->orderBy('tai_chinhs.updated_at', 'desc')
                        ->get();

        return response()->json([
            'data'  =>$data
        ]);
    }

    public function duyetTienNap(Request $request){
        $id_chuc_nang = 25;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        $user = Auth::guard('sanctum')->user();
        $taiChinh = TaiChinh::find($request->id);
        if ($taiChinh) {
            $taiChinh->id_nhan_vien = $user->id;
            $taiChinh->is_thanh_toan = 1;
            $taiChinh->save();

            $khachHang = KhachHang::find($taiChinh->id_khach_hang);
            if ($khachHang) {
            $khachHang->so_du += $taiChinh->so_tien_nap;
            $khachHang->save();
            }

            return response()->json([
            'status'    => 1,
            'message'   => 'Đã duyệt thành công'
            ]);
        }

        return response()->json([
            'status'    => 0,
            'message'   => 'Không tìm thấy thông tin'
        ]);
    }
}
