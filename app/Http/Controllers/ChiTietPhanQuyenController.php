<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChiTietPhanQuyenCreateRequest;
use App\Http\Requests\ChiTietPhanQuyenXoaRequest;
use App\Models\ChiTietPhanQuyen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChiTietPhanQuyenController extends Controller
{
    public function store(ChiTietPhanQuyenCreateRequest $request)
    {
        $id_chuc_nang = 36;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }

        ChiTietPhanQuyen::firstOrCreate([
            'id_quyen' => $request->id_quyen,
            'id_chuc_nang' => $request->id_chuc_nang,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Đã phân quyền thành công!'
        ]);
    }
    public function getData(Request $request){
        $id_chuc_nang = 38;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        $data = ChiTietPhanQuyen::where('id_quyen', $request->id)
            ->join('chuc_nangs', 'chuc_nangs.id', 'chi_tiet_phan_quyens.id_chuc_nang')
            ->join('phan_quyens', 'phan_quyens.id', 'chi_tiet_phan_quyens.id_quyen')
            ->select('chuc_nangs.ten_chuc_nang', 'chi_tiet_phan_quyens.*', 'phan_quyens.ten_quyen')
            ->get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function xoaPhanQuyen(ChiTietPhanQuyenXoaRequest $request){
        $id_chuc_nang = 37;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }

        ChiTietPhanQuyen::where('id', $request->id)->delete();

        return response()->json([
            'status'    =>  true,
            'message'   =>  'Đã xóa quyền thành công!'
        ]);
    }
}
