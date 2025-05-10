<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NhanVienXoaRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|exists:nhan_viens,id'
        ];
    }
    public function messages()
    {
        return [
            'id.required' => 'Chọn nhân viên cần xóa',
            'id.exists' => 'Nhân viên không tồn tại'
        ];
    }
}
