<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhimCapNhatRequest;
use App\Http\Requests\PhimCreateRequest;
use App\Http\Requests\PhimXoaRequest;
use App\Models\Phim;
use Illuminate\Http\Request;

class PhimController extends Controller
{
    public function create(PhimCreateRequest $request){
        Phim::create([
            'ten_phim'          =>$request->ten_phim,
            'slug_phim'         =>$request->slug_phim,
            'id_the_loai'       =>$request->id_the_loai,
            'loai_phim'         =>$request->loai_phim,
            'hinh_anh'          =>$request->hinh_anh,
            'trailer'           =>$request->trailer,
            'link_phim'         =>$request->link_phim,
            'thoi_luong'        =>$request->thoi_luong,
            'dao_dien'          =>$request->dao_dien,
            'dien_vien'         =>$request->dien_vien,
            'quoc_gia'          =>$request->quoc_gia,
            'ngay_khoi_chieu'   =>$request->ngay_khoi_chieu,
            'ngay_ket_thuc'     =>$request->ngay_ket_thuc,
            'mo_ta'             =>$request->mo_ta,
            'gia_ban'           =>$request->gia_ban,
            'trang_thai'        =>$request->trang_thai,
        ]);
        return response()->json([
            'status' => 1,
            'message' => 'Đã thêm phim '. $request->ten_phim .' thành công'
        ]);
    }
    public function getData(){
        $data = Phim::join('the_loais', 'phims.id_the_loai', '=', 'the_loais.id')
            ->select('phims.*', 'the_loais.ten_the_loai')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function xoaPhim(PhimXoaRequest $request){
        Phim::where('id', $request->id)->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Đã xóa phim '. $request->ten_phim .' thành công'
        ]);
    }
    public function capNhatPhim(PhimCapNhatRequest $request){
        Phim::where('id', $request->id)->update([
            'ten_phim'          =>$request->ten_phim,
            'slug_phim'         =>$request->slug_phim,
            'id_the_loai'       =>$request->id_the_loai,
            'loai_phim'         =>$request->loai_phim,
            'hinh_anh'          =>$request->hinh_anh,
            'trailer'           =>$request->trailer,
            'link_phim'         =>$request->link_phim,
            'thoi_luong'        =>$request->thoi_luong,
            'dao_dien'          =>$request->dao_dien,
            'dien_vien'         =>$request->dien_vien,
            'quoc_gia'          =>$request->quoc_gia,
            'ngay_khoi_chieu'   =>$request->ngay_khoi_chieu,
            'ngay_ket_thuc'     =>$request->ngay_ket_thuc,
            'mo_ta'             =>$request->mo_ta,
            'gia_ban'           =>$request->gia_ban,
            'trang_thai'        =>$request->trang_thai,
        ]);
        return response()->json([
            'status' => 1,
            'message' => 'Đã cập nhật phim '. $request->ten_phim .' thành công'
        ]);
    }
    public function changeStatus(Request $request){
        $phim = Phim::where('id', $request->id)->first();

        if ($phim->trang_thai == 1) {
            $phim->trang_thai = 0;
            $phim->save();
        } else {
            $phim->trang_thai = 1;
            $phim->save();
        }
        return response()->json([
            'status'    =>  true,
            'message'   =>  'Đã cập nhật phim ' . $request->ten_phim . ' thành công'
        ]);
    }
    public function timKiem(Request $request){
        $noi_dung = '%' . $request->noi_dung . '%';

        $data = Phim::join('the_loais', 'phims.id_the_loai', '=', 'the_loais.id')
                        ->select('phims.*', 'the_loais.ten_the_loai')
                        ->where('ten_phim', 'like', $noi_dung)
                        ->orwhere('slug_phim', 'like', $noi_dung)
                        ->orwhere('ten_the_loai', 'like', $noi_dung)
                        ->orwhere('dao_dien', 'like', $noi_dung)
                        ->orwhere('dien_vien', 'like', $noi_dung)
                        ->orwhere('quoc_gia', 'like', $noi_dung)
                        ->orwhere('thoi_luong', 'like', $noi_dung)
                        ->orwhere('ngay_khoi_chieu', 'like', $noi_dung)
                        ->orwhere('ngay_ket_thuc', 'like', $noi_dung)
                        ->orderBy('created_at', 'desc')
                        ->get();
        return response()->json([
            'data' => $data
        ]);
    }

    public function getDataPhimChieuRap(){
        $data = Phim::where('trang_thai', 0)
            ->where('loai_phim', 1)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function getDataPhimBo(){
        $data = Phim::where('trang_thai', 0)
            ->where('loai_phim', 2)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function getDataPhimDeCu(){
        $data = Phim::where('trang_thai', 0)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function getDataPhimTopChieuRap(){
        $data = Phim::where('trang_thai', 0)
            ->where('loai_phim', 1)
            ->orderBy('luot_xem', 'desc') // Sắp xếp lượt xem từ cao nhất đến thấp nhất
            ->get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function getDataPhimTopPhimBo(){
        $data = Phim::where('trang_thai', 0)
            ->where('loai_phim', 2)
            ->orderBy('luot_xem', 'desc')
            ->get();
        return response()->json([
            'data' => $data
        ]);
    }
}
