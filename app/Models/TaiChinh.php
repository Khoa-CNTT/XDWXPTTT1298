<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaiChinh extends Model
{
    protected $table = 'tai_chinhs';
    protected $fillable = [
        'id_khach_hang',
        'id_nhan_vien',
        'so_tien_nap',
        'kieu_nap',
        'noi_dung',
        'hash',
        'is_thanh_toan'
    ];
    const NAP_BANG_TAY = 1;
    const NAP_QUA_BANK = 0;
}
