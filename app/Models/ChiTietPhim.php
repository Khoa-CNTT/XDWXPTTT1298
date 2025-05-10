<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChiTietPhim extends Model
{
    protected $table = 'chi_tiet_phims';
    protected $fillable = [
        'id_phim',
        'id_khach_hang',
        'so_tien_mua'
    ];

    /**
     * Get the movie that owns the purchase detail
     */
    public function phim(): BelongsTo
    {
        return $this->belongsTo(Phim::class, 'id_phim');
    }

    /**
     * Get the customer that owns the purchase detail
     */
    public function khachHang(): BelongsTo
    {
        return $this->belongsTo(KhachHang::class, 'id_khach_hang');
    }
}
