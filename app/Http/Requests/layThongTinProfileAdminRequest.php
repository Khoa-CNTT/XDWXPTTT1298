<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class layThongTinProfileAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ho_va_ten'     => 'required|min:3|max:50',
            'email'         => 'required|email|unique:khach_hangs,email,' . $this->id . 'id,',
            'so_dien_thoai' => 'required|digits:10',
        ];
    }

    public function messages(): array
    {
        return [
            'ho_va_ten.required'     => 'Họ và tên là bắt buộc.',
            'ho_va_ten.min'          => 'Họ và tên phải có ít nhất 3 ký tự.',
            'ho_va_ten.max'          => 'Họ và tên không được vượt quá 50 ký tự.',
            'email.required'         => 'Email là bắt buộc.',
            'email.email'            => 'Email không hợp lệ.',
            'email.unique'           => 'Email đã tồn tại.',
            'so_dien_thoai.required' => 'Số điện thoại là bắt buộc.',
            'so_dien_thoai.digits'   => 'Số điện thoại phải có 10 chữ số.',
        ];
    }
}
