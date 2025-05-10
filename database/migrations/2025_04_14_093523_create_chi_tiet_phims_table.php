<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('chi_tiet_phims', function (Blueprint $table) {
            $table->id();
            $table->integer('id_phim');
            $table->integer('id_khach_hang');
            $table->integer('so_tien_mua');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_phims');
    }
};
