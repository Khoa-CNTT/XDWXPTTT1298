<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChucNangCapNhatRequest;
use App\Http\Requests\ChucNangCreateRequest;
use App\Http\Requests\ChucNangXoaRequest;
use App\Models\ChiTietPhanQuyen;
use App\Models\ChucNang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChucNangController extends Controller
{
    public function store(ChucNangCreateRequest $request)
    {
        $id_chuc_nang = 26;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
         ChucNang::create([
            'ten_chuc_nang' => $request->ten_chuc_nang,
            'trang_thai'    => $request->trang_thai,
         ]);
        return response()->json([
            'status' => 1,
            'message' => 'Đã thêm chức năng ' . $request->ten_chuc_nang . ' thành công!'
        ]);
    }
    public function getData()
    {
        $id_chuc_nang = 27;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        $data = ChucNang::get();
        return response()->json([
            'data' => $data
        ]);
    }

    public function getDataOpen(){
        $id_chuc_nang = 28;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        $data = ChucNang::where('trang_thai', 0)->get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function xoaChucNang(ChucNangXoaRequest $request){
        $id_chuc_nang = 29;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        ChucNang::where('id', $request->id)->delete();
        return response()->json([
            'status'    =>  true,
            'message'   =>  'Đã xóa chức năng ' . $request->ten_chuc_nang . ' thành công'
        ]);
    }
    public function capNhatChucNang(ChucNangCapNhatRequest $request){
        $id_chuc_nang = 30;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        ChucNang::where('id', $request->id)->update([
            'ten_chuc_nang' => $request->ten_chuc_nang,
            'trang_thai'    => $request->trang_thai,
        ]);
        return response()->json([
            'status'    =>  1,
            'message'   =>  'Đã cập nhật chức năng ' . $request->ten_chuc_nang . ' thành công'
        ]);
    }
    public function changeStatus(Request $request){
        $id_chuc_nang = 31;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        $chucNang = ChucNang::where('id', $request->id)->first();

        if ($chucNang->trang_thai == 1) {
            $chucNang->trang_thai = 0;
            $chucNang->save();
        } else {
            $chucNang->trang_thai = 1;
            $chucNang->save();
        }
        return response()->json([
            'status'    =>  true,
            'message'   =>  'Đã cập nhật chức năng ' . $request->ten_chuc_nang . ' thành công'
        ]);
    }
    public function timKiem(Request $request){
        $noi_dung = '%'. $request->noi_dung . '%';
        $noi_dung = '%' . $request->noi_dung . '%';

        $data = ChucNang::where('ten_chuc_nang', 'like', $noi_dung)
                        ->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
