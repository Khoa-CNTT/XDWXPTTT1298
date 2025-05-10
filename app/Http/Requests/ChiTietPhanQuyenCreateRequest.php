<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChiTietPhanQuyenCreateRequest extends FormRequest
{


    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'id_chuc_nang' => 'required|exists:chuc_nangs,id',
            'id_quyen' => 'required|exists:phan_quyens,id',
        ];
    }
}
