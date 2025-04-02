<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KhachHangCapNhatRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
           'id'            => 'required|exists:khach_hangs,id',
           'ho_va_ten'     => 'required|min:4|max:50',
           'email'         => 'required|email|unique:khach_hangs,email,'. $this->id. 'id,',
           'so_dien_thoai' => 'required|digits:10',
           'ngay_sinh'     => 'required|date',
        ];
    }
    public function messages()
    {
        return [
            'id.required'               =>'Không tìm thấy khách hàng',
            'id.exists'                 =>'Khách hàng không tồn tại!',
            'ho_va_ten.*'               =>"Họ và tên phải từ 4 đến 50 ký tự",
            'so_dien_thoai.required'    => 'Số điện thoại không được để trống',
            'so_dien_thoai.digits'      => 'Số điện thoại phải đủ 10 ký tự',
            'email.unique'              =>"Tài khoản đã tồn tại",
            'email.email'               =>"Email không đúng định dạng",
            'email.required'            =>"Nhập địa chỉ email",
            'ngay_sinh.required'        =>"Nhập ngày sinh",
        ];
    }
}
