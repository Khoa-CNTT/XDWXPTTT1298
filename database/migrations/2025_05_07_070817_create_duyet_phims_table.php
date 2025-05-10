<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('duyet_phims', function (Blueprint $table) {
            $table->id();
            $table->integer('id_phim');
            $table->integer('id_nhan_vien');
            $table->longText('ly_do_tu_choi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duyet_phims');
    }
};
