<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuyenXoaRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'id' => 'required|exists:phan_quyens,id',
        ];
    }
    public function messages(): array
    {
        return [
            'id.required' => 'ID quyền không được để trống',
            'id.exists' => 'ID quyền không tồn tại trong hệ thống',
        ];
    }
}
