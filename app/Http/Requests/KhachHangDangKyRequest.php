<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KhachHangDangKyRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'ho_va_ten'         =>"required|min:4|max:50",
            'email'             =>"required|email|unique:khach_hangs,email",
            'ngay_sinh'         =>"required",
            'so_dien_thoai'     => 'required|digits:10',
            'password'          =>"required|min:6|max:30",
            're_password'       =>"required|same:password",
        ];
    }
    public function messages()
    {
        return [
            'ho_va_ten.*'               =>"Họ và tên phải từ 4 đến 50 ký tự",
            'email.unique'              =>"Tài khoản đã tồn tại",
            'email.email'               =>"Email không đúng định dạng",
            'email.required'            =>"Nhập địa chỉ email",
            'so_dien_thoai.required'    => 'Số điện thoại không được để trống',
            'so_dien_thoai.digits'      => 'Số điện thoại phải đủ 10 ký tự',
            'ngay_sinh.required'        =>"Nhập ngày sinh",
            'password.*'                =>"Nhập mật khẩu từ 6 đến 30 ký tự",
            're_password.*'             =>"Mật khẩu nhập lại không trùng khớp",
        ];
    }
}
