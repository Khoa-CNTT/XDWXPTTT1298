<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhimCapNhatRequest;
use App\Http\Requests\PhimCreateRequest;
use App\Http\Requests\PhimXoaRequest;
use App\Models\ChiTietPhanQuyen;
use App\Models\ChiTietPhim;
use App\Models\Phim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhimController extends Controller
{
    public function create(PhimCreateRequest $request){
        $id_chuc_nang = 10;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
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
            'trang_thai'        =>1,
            'duyet_phim'        =>1,
        ]);
        return response()->json([
            'status' => 1,
            'message' => 'Đã thêm phim '. $request->ten_phim .' thành công, vui lòng chờ duyệt phim!'
        ]);
    }
    public function getData(){
        $id_chuc_nang = 11;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        $data = Phim::join('the_loais', 'phims.id_the_loai', '=', 'the_loais.id')
            ->select('phims.*', 'the_loais.ten_the_loai')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function xoaPhim(PhimXoaRequest $request){
        $id_chuc_nang = 12;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        Phim::where('id', $request->id)->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Đã xóa phim '. $request->ten_phim .' thành công'
        ]);
    }
    public function capNhatPhim(PhimCapNhatRequest $request){
        $id_chuc_nang = 13;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
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
            'trang_thai'        =>1,
            'duyet_phim'        =>1,
        ]);
        return response()->json([
            'status' => 1,
            'message' => 'Đã cập nhật phim '. $request->ten_phim .' thành công, vui lòng chờ duyệt phim!'
        ]);
    }
    public function changeStatus(Request $request){
        $id_chuc_nang = 14;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
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

    public function timKiemPhim(Request $request){
        $noi_dung = '%' . $request->noi_dung . '%';

        $data = Phim::join('the_loais', 'phims.id_the_loai', '=', 'the_loais.id')
                ->where('ten_phim', 'like', $noi_dung)
                ->orwhere('slug_phim', 'like', $noi_dung)
                ->orwhere('ten_the_loai', 'like', $noi_dung)
                ->orwhere('dao_dien', 'like', $noi_dung)
                ->orwhere('dien_vien', 'like', $noi_dung)
                ->orwhere('quoc_gia', 'like', $noi_dung)
                ->orwhere('thoi_luong', 'like', $noi_dung)
                ->orwhere('ngay_khoi_chieu', 'like', $noi_dung)
                ->orwhere('ngay_ket_thuc', 'like', $noi_dung)
                ->select('phims.*', 'the_loais.ten_the_loai')
                ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function locPhimRap(Request $request){
        $query = Phim::join('the_loais', 'phims.id_the_loai', '=', 'the_loais.id')
                    ->where('loai_phim', 1)
                    ->select('phims.*', 'the_loais.ten_the_loai');

        if ($request->filled('the_loai')) {
            $query->where('id_the_loai', $request->the_loai);
        }

        if ($request->filled('quoc_gia')) {
            $query->where('quoc_gia', 'like', '%' . $request->quoc_gia . '%');
        }

        if ($request->filled('nam')) {
            $query->whereYear('ngay_khoi_chieu', $request->nam);
        }

        if ($request->filled('noi_dung')) {
            $noi_dung = '%' . $request->noi_dung . '%';
            $query->where(function ($q) use ($noi_dung) {
                $q->where('ten_phim', 'like', $noi_dung)
                  ->orWhere('slug_phim', 'like', $noi_dung)
                  ->orWhere('dao_dien', 'like', $noi_dung)
                  ->orWhere('dien_vien', 'like', $noi_dung)
                  ->orWhere('quoc_gia', 'like', $noi_dung)
                  ->orWhere('thoi_luong', 'like', $noi_dung)
                  ->orWhere('ngay_khoi_chieu', 'like', $noi_dung)
                  ->orWhere('ngay_ket_thuc', 'like', $noi_dung);
            });
        }

        if ($request->filled('sap_xep')) {
            switch ($request->sap_xep) {
                case 'ten_az':
                    $query->orderBy('ten_phim', 'asc');
                    break;
                case 'nam_xuat_ban':
                    $query->orderBy('ngay_khoi_chieu', 'asc');
                    break;
                case 'ngay_dang':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $data = $query->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function locPhimBo(Request $request){
        $query = Phim::join('the_loais', 'phims.id_the_loai', '=', 'the_loais.id')
                    ->where('loai_phim', 2)
                    ->select('phims.*', 'the_loais.ten_the_loai');

        if ($request->filled('the_loai')) {
            $query->where('id_the_loai', $request->the_loai);
        }

        if ($request->filled('quoc_gia')) {
            $query->where('quoc_gia', 'like', '%' . $request->quoc_gia . '%');
        }

        if ($request->filled('nam')) {
            $query->whereYear('ngay_khoi_chieu', $request->nam);
        }

        if ($request->filled('noi_dung')) {
            $noi_dung = '%' . $request->noi_dung . '%';
            $query->where(function ($q) use ($noi_dung) {
                $q->where('ten_phim', 'like', $noi_dung)
                  ->orWhere('slug_phim', 'like', $noi_dung)
                  ->orWhere('dao_dien', 'like', $noi_dung)
                  ->orWhere('dien_vien', 'like', $noi_dung)
                  ->orWhere('quoc_gia', 'like', $noi_dung)
                  ->orWhere('thoi_luong', 'like', $noi_dung)
                  ->orWhere('ngay_khoi_chieu', 'like', $noi_dung)
                  ->orWhere('ngay_ket_thuc', 'like', $noi_dung);
            });
        }

        if ($request->filled('sap_xep')) {
            switch ($request->sap_xep) {
                case 'ten_az':
                    $query->orderBy('ten_phim', 'asc');
                    break;
                case 'nam_xuat_ban':
                    $query->orderBy('ngay_khoi_chieu', 'asc');
                    break;
                case 'ngay_dang':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $data = $query->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function loadPhim(Request $request)
    {
        $user = Auth::guard('sanctum')->user();

        if ($user && $user instanceof \App\Models\KhachHang) {
            $check = ChiTietPhim::where('id_phim', $request->id_phim)
                ->where('id_khach_hang', $user->id)
                ->first();
            if ($check) {
                $data = Phim::where('id', $request->id_phim)
                    ->first();
                $data->increment('luot_xem');
                return response()->json([
                    'status'  => 1,
                    'data'    => $data,
                ]);
            } else {
                return response()->json([
                    'status'  => 0,
                    'message' => "Bạn chưa mua phim này!",
                ]);
            }
        } else {
            return response()->json([
                'status'  => 2,
                'message' => "Có lỗi xảy ra",
            ]);
        }
    }

    public function getDataKinhDi()
    {
        $data = Phim::where('trang_thai', 0)
        ->where('id_the_loai', 1)
        ->orderBy('created_at', 'desc')
        ->get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function getDataLangMan()
    {
        $data = Phim::where('trang_thai', 0)
        ->where('id_the_loai', 2)
        ->orderBy('created_at', 'desc')
        ->get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function getDataHaiHuoc()
    {
        $data = Phim::where('trang_thai', 0)
        ->where('id_the_loai', 3)
        ->orderBy('created_at', 'desc')
        ->get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function getDataPhieuLuu()
    {
        $data = Phim::where('trang_thai', 0)
        ->where('id_the_loai', 4)
        ->orderBy('created_at', 'desc')
        ->get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function getDataVienTuong()
    {
        $data = Phim::where('trang_thai', 0)
        ->where('id_the_loai', 5)
        ->orderBy('created_at', 'desc')
        ->get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function getDataLichSu()
    {
        $data = Phim::where('trang_thai', 0)
        ->where('id_the_loai', 6)
        ->orderBy('created_at', 'desc')
        ->get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function getDataTamLy()
    {
        $data = Phim::where('trang_thai', 0)
        ->where('id_the_loai', 7)
        ->orderBy('created_at', 'desc')
        ->get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function getDataGiaTuong()
    {
        $data = Phim::where('trang_thai', 0)
        ->where('id_the_loai', 8)
        ->orderBy('created_at', 'desc')
        ->get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function getDataHanhDong()
    {
        $data = Phim::where('trang_thai', 0)
        ->where('id_the_loai', 9)
        ->orderBy('created_at', 'desc')
        ->get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function getDataThanThoai()
    {
        $data = Phim::where('trang_thai', 0)
        ->where('id_the_loai', 10)
        ->orderBy('created_at', 'desc')
        ->get();
        return response()->json([
            'data' => $data
        ]);
    }

    public function getDataDuyetPhim()
    {
        $id_chuc_nang = 39;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        $data = Phim::join('the_loais', 'phims.id_the_loai', '=', 'the_loais.id')
            ->select('phims.*', 'the_loais.ten_the_loai')
            ->where('phims.trang_thai', 1)
            ->where('phims.duyet_phim', 1)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function dsTuChoi(){
        $id_chuc_nang = 42;
        $id_quyen = Auth::guard('sanctum')->user()->id_quyen;
        $check = ChiTietPhanQuyen::where('id_quyen', $id_quyen)->where('id_chuc_nang', $id_chuc_nang)->first();
        if(!$check){
            return response()->json([
                'status'    =>  0,
                'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
            ]);
        }
        $data = Phim::join('the_loais', 'phims.id_the_loai', '=', 'the_loais.id')
                ->join('duyet_phims', 'phims.id', '=', 'duyet_phims.id_phim')
                ->join('nhan_viens', 'duyet_phims.id_nhan_vien', '=', 'nhan_viens.id')
                ->select('phims.*', 'the_loais.ten_the_loai', 'duyet_phims.*', 'nhan_viens.*')
                ->orderBy('duyet_phims.created_at', 'desc')
                ->where('phims.duyet_phim', 0)
                ->get();
        return response()->json([
            'data' => $data
        ]);
    }
}
