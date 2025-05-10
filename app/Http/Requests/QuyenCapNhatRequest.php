<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuyenCapNhatRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer',
            'ten_quyen' => 'required|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'id.required' => 'ID quyền không được để trống',
            'id.integer' => 'ID quyền phải là một số nguyên',
            'ten_quyen.required' => 'Tên quyền không được để trống',
            'ten_quyen.max' => 'Tên quyền không được vượt quá 255 ký tự',
        ];
    }
}
