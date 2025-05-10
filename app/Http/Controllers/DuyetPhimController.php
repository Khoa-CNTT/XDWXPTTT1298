<?php

namespace App\Http\Controllers;

use App\Http\Requests\TuChoiPhimRequest;
use App\Models\ChiTietPhanQuyen;
use App\Models\DuyetPhim;
use App\Models\Phim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DuyetPhimController extends Controller
{

    public function duyetPhim(Request $request){
        $id_chuc_nang = 40;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        Phim::where('id',$request->id)->update([
            'trang_thai' => 0
        ]);
        return response()->json([
            'status'    =>  1,
            'message'   =>  'Đã duyệt phim ' . $request->ten_phim . ' thành công'
        ]);
    }
    public function tuChoiPhim(TuChoiPhimRequest $request){
        $id_chuc_nang = 41;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        $user = Auth::guard('sanctum')->user();
        Phim::where('id', $request->id)->update([
            'duyet_phim' => 0
        ]);

        DuyetPhim::create([
            'id_phim'   => $request->id,
            'id_nhan_vien'  => $user->id,
            'ly_do_tu_choi' => $request->ly_do_tu_choi,
        ]);

        return response()->json([
            'status'    =>  1,
            'message'   =>  'Đã từ chối phim ' . $request->ten_phim . ' thành công'
        ]);
    }

}
