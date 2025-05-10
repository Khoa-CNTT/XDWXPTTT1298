<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Phim extends Model
{
    protected $table = 'phims';
    protected $fillable = [
        'ten_phim',
        'slug_phim',
        'id_the_loai',
        'loai_phim',
        'hinh_anh',
        'trailer',
        'link_phim',
        'thoi_luong',
        'dao_dien',
        'dien_vien',
        'quoc_gia',
        'ngay_khoi_chieu',
        'ngay_ket_thuc',
        'mo_ta',
        'luot_xem',
        'gia_ban',
        'trang_thai',
        'duyet_phim',
    ];

}
