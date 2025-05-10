<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuyenCapNhatRequest;
use App\Http\Requests\QuyenCreateRequest;
use App\Http\Requests\QuyenXoaRequest;
use App\Models\ChiTietPhanQuyen;
use App\Models\PhanQuyen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhanQuyenController extends Controller
{
    public function getQuyen(){
        $id_chuc_nang = 35;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }

        $data = PhanQuyen::get();
        return response()->json([
            'data'      => $data
        ]);
    }
    public function themQuyen(QuyenCreateRequest $request)
    {
        $id_chuc_nang = 32;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }

        PhanQuyen::create([
                'ten_quyen' => $request->ten_quyen
            ]);
        return response()->json([
            'status'    => true,
            'message'   => 'Thêm mới quyền ' .$request->ten_quyen. ' thành công!'
        ]);

    }
    public function xoaQuyen(QuyenXoaRequest $request){
        $id_chuc_nang = 33;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }

        PhanQuyen::where('id', $request->id)->delete();
        return response()->json([
            'status'    => true,
            'message'   => 'Xóa quyền ' .$request->ten_quyen. ' thành công!'
        ]);
    }
    public function suaQuyen(QuyenCapNhatRequest $request){
        $id_chuc_nang = 34;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }

        PhanQuyen::where('id', $request->id)->update([
            'ten_quyen' => $request->ten_quyen
        ]);
        return response()->json([
            'status'    => true,
            'message'   => 'Cập nhật quyền ' .$request->ten_quyen. ' thành công!'
        ]);
    }
    public function searchQuyen(Request $request){

        $noi_dung = '%' . $request->noi_dung . '%';

        $data = PhanQuyen::where('ten_quyen', 'like', $noi_dung)
                ->get();
        return response()->json([
            'data' => $data
        ]);
    }
}
