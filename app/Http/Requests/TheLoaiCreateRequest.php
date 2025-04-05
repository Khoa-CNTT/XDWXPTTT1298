<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TheLoaiCreateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ten_the_loai'      =>'required|min:3',
            'slug_the_loai'     =>'required|min:3|unique:the_loais,slug_the_loai',
            'tinh_trang'        =>'required|boolean',
        ];
    }
    public function messages()
    {
        return [
            'ten_the_loai.*'        =>'Tên thể loại không được bỏ trống',
            'slug_the_loai'         =>'Slug thể loại không được bỏ trống',
            'tinh_trang.*'          =>'Tình trạng yêu cầu phải chọn',
        ];
    }
}
