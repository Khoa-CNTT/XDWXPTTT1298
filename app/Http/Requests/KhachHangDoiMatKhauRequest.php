<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KhachHangDoiMatKhauRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hash_reset' => 'required',
            'password' => 'required|min:6|max:30',
            're_password' => 'required|same:password',
        ];
    }
}
