<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KhachHangXoaRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'id'        =>'required|exists:khach_hangs,id'
        ];
    }
    public function messages()
    {
        return [
            'id.required'        =>'Không tìm thấy khách hàng',
            'id.exists'          =>'Khách hàng không tồn tại!'
        ];
    }
}
