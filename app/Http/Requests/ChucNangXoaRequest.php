<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChucNangXoaRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|exists:chuc_nangs,id',
        ];
    }
    public function messages()
    {
        return [
            'id.required' => 'Chọn chức năng cần xóa',
            'id.exists' => 'Chức năng không tồn tại'
        ];
    }
}
