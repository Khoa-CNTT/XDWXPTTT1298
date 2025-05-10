<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhimCapNhatRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id'                =>'required|exists:phims,id',
            'ten_phim'          =>'required',
            'slug_phim'         =>'required|unique:phims,slug_phim,'.$this->id . 'id,',
            'id_the_loai'       =>'required',
            'loai_phim'         =>'required',
            'hinh_anh'          =>'required',
            'dao_dien'          =>'required',
            'dien_vien'         =>'required',
            'quoc_gia'          =>'required',
            'thoi_luong'        =>'required|nullable',
            'mo_ta'             =>'required',
            'trailer'           =>'required',
            'link_phim'         =>'required',
            'ngay_khoi_chieu'   =>'required|date',
            'ngay_ket_thuc'     =>'required|date',
            'trang_thai'        =>'required|boolean',
            'gia_ban'           =>'required|integer',
        ];
    }
    public function messages()
    {
        return [
            'id.exists' => 'Id phim không tồn tại',
            'slug_phim.unique' => 'Slug phim đã tồn tại',
            'slug_phim.required' => 'Slug phim không được để trống',
            'ten_phim.required' => 'Tên phim không được để trống',
            'id_the_loai.required' => 'Thể loại không được để trống',
            'loai_phim.required' => 'Loại phim không được để trống',
            'hinh_anh.required' => 'Hình ảnh không được để trống',
            'dao_dien.required' => 'Đạo diễn không được để trống',
            'dien_vien.required' => 'Diễn viên không được để trống',
            'quoc_gia.required' => 'Quốc gia không được để trống',
            'thoi_luong.required' => 'Thời lượng không được để trống',
            'thoi_luong.nullable' => 'Thời lượng phải là số',
            'mo_ta.required' => 'Mô tả không được để trống',
            'trailer.required' => 'Trailer không được để trống',
            'link_phim.required' => 'Link phim không được để trống',
            'ngay_khoi_chieu.required' => 'Ngày khởi chiếu không được để trống',
            'ngay_ket_thuc.required' => 'Ngày kết thúc không được để trống',
            'trang_thai.required' => 'Trạng thái không được để trống',
            'ngay_khoi_chieu.date' => 'Ngày khởi chiếu phải là ngày',
            'ngay_ket_thuc.date' => 'Ngày kết thúc phải là ngày',
            'gia_ban.required' => 'Giá bán không được để trống',
        ];

    }
}
