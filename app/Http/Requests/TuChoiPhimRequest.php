<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TuChoiPhimRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|exists:phims,id',
            'ly_do_tu_choi' => 'required|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'id.required' => 'ID phim là bắt buộc.',
            'id.exists' => 'Phim không tồn tại.',
            'ly_do_tu_choi.required' => 'Lý do từ chối là bắt buộc.',
            'ly_do_tu_choi.max' => 'Lý do từ chối không được vượt quá 255 ký tự.',
        ];
    }
}
