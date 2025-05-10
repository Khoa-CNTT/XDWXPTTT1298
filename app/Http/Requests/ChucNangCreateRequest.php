<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChucNangCreateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ten_chuc_nang' => 'required',
            'trang_thai'    => 'required|boolean',
        ];
    }
    public function messages(): array
    {
        return [
            'ten_chuc_nang.required' => 'Tên chức năng không được để trống',
            'trang_thai.required'    => 'Trạng thái không được để trống',
        ];
    }
}
