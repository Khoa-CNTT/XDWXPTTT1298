<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TheLoaiCapNhatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'id'                => 'required|exists:the_loais,id',
            'ten_the_loai'      =>'required|min:3',
            'slug_the_loai'     =>'required|min:3|unique:the_loais,slug_the_loai,' . $this->id . 'id ,',
            'tinh_trang'        =>'required|boolean',
        ];
    }
    public function messages()
    {
        return [
            'id.exists'             =>'Id thể loại không tồn tại',
            'ten_the_loai.*'        =>'Tên thể loại không được bỏ trống',
            'slug_the_loai'         =>'Slug thể loại không được bỏ trống',
            'tinh_trang.*'          =>'Tình trạng yêu cầu phải chọn',
        ];
    }
}
