<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaiChinhRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id'                =>'required|exists:khach_hangs,id',
            'so_tien_can_nap'   =>'required|numeric',
            'ly_do_nap_tien'    =>'required|min:4|max:100',
        ];
    }
    public function messages()
    {
        return [
            'id.required'                   => 'Trường ID là bắt buộc.',
            'id.exists'                     => 'ID được cung cấp không tồn tại.',
            'so_tien_can_nap.required'          => 'Số tiền cần nạp là bắt buộc.',
            'so_tien_can_nap.numeric'           => 'Số tiền cần nạp phải là một số.',
            'ly_do_nap_tien.required'         => 'Lý do nạp tiền là bắt buộc.',
            'ly_do_nap_tien.min'              => 'Lý do nạp tiền phải có ít nhất :min ký tự.',
            'ly_do_nap_tien.max'              => 'Lý do nạp tiền không được vượt quá :max ký tự.',
        ];
    }
}
