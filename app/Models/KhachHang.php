<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhachHang extends Model
{
    protected $table = 'khach_hangs';
    protected $fillable = [
        'ho_va_ten',
        'email',
        'password',
        'ngay_sinh',
        'so_dien_thoai',
        'so_du',
        'is_active',
        'is_block',
        'type-account',
        'content_block',
        'hash_active',
        'hash_reset'
    ];
}
