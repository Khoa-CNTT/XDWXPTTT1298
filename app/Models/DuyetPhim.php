<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DuyetPhim extends Model
{
    protected $table = 'duyet_phims';
    protected $fillable = [
        'id_phim',
        'id_nhan_vien',
        'ly_do_tu_choi',
    ];

}
