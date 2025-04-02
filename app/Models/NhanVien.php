<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class NhanVien extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $table = 'nhan_viens';
    protected $fillable = [
        'ho_va_ten',
        'email',
        'password',
        'ngay_sinh',
        'so_dien_thoai',
        'tinh_trang',
        'is_master',
        'id_quyen'
    ];
}
