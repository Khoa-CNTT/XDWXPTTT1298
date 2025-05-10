<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TheLoai extends Model
{
    protected $table = 'the_loais';
    protected $fillable = [
        'ten_the_loai',
        'slug_the_loai',
        'tinh_trang',
    ];

    /**
     * Get all movies for this genre
     */
    public function phims(): HasMany
    {
        return $this->hasMany(Phim::class, 'id_the_loai');
    }

    /**
     * Scope a query to only include active genres
     */
    public function scopeActive($query)
    {
        return $query->where('tinh_trang', 0);
    }
}
