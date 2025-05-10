<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NhanVienCreateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ho_va_ten'     => "required|min:4",
            'email'         => "required|email|unique:nhan_viens,email",
            'ngay_sinh'     => "required",
            'so_dien_thoai' => "required|digits:10",
            'password'      => "required|min:6|max:30",
            'tinh_trang'    => "required|boolean",
            'id_quyen'      => "required",
        ];
    }
    public function messages()
    {
        return [
            'ho_va_ten.*'               => "Họ và tên phải từ 4 ký tự trở lên",
            'email.unique'              => "Email đã tồn tại",
            'email.email'               => "Email không đúng định dạng",
            'email.required'            => "Nhập địa chỉ email",
            'ngay_sinh.required'        => "Chọn ngày sinh",
            'so_dien_thoai.required'    => 'Số điện thoại không được để trống',
            'so_dien_thoai.digits'      => 'Số điện thoại phải đủ 10 ký tự',
            'password.*'                => "Mật khẩu phải từ 6 đến 30 ký tự",
            'tinh_trang.*'              => "Chọn trạng thái",
            'id_quyen.*'                => "Chọn quyền",
        ];
    }
}
