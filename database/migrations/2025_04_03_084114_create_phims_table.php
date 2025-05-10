<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('phims', function (Blueprint $table) {
            $table->id();
            $table->string('ten_phim');
            $table->string('slug_phim');
            $table->integer('id_the_loai');
            $table->integer('loai_phim');
            $table->longText('hinh_anh');
            $table->string('trailer');
            $table->string('link_phim');
            $table->string('thoi_luong');
            $table->string('dao_dien');
            $table->string('dien_vien');
            $table->string('quoc_gia');
            $table->string('ngay_khoi_chieu');
            $table->string('ngay_ket_thuc');
            $table->longText('mo_ta');
            $table->integer('luot_xem')->default(0);
            $table->integer('gia_ban')->default(0);
            $table->integer('trang_thai');
            $table->integer('duyet_phim')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phims');
    }
};
