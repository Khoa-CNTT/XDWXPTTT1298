<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChucNangCapNhatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:chuc_nangs,id',
            'ten_chuc_nang' => 'required|string|max:255',
            'trang_thai' => 'required|boolean',
        ];
    }
    public function messages(): array
    {
        return [
            'id.required' => 'ID là bắt buộc.',
            'id.integer' => 'ID phải là một số nguyên.',
            'id.exists' => 'ID không tồn tại trong cơ sở dữ liệu.',
            'ten_chuc_nang.required' => 'Tên chức năng là bắt buộc.',
            'ten_chuc_nang.string' => 'Tên chức năng phải là một chuỗi.',
            'ten_chuc_nang.max' => 'Tên chức năng không được vượt quá 255 ký tự.',
            'trang_thai.required' => 'Trạng thái là bắt buộc.',
            'trang_thai.boolean' => 'Trạng thái phải là true hoặc false.',
        ];
    }
}
