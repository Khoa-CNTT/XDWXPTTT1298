<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('khach_hangs', function (Blueprint $table) {
            $table->id();
            $table->string('ho_va_ten');
            $table->string('email');
            $table->string('password');
            $table->date('ngay_sinh');
            $table->string('so_dien_thoai');
            $table->integer('so_du')->default(0);
            $table->integer('is_active')->default(0);
            $table->integer('is_block')->default(0);
            $table->integer('type-account')->default(0);
            $table->string('content_block')->nullable();
            $table->string('hash_active')->nullable();
            $table->string('hash_reset')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('khach_hangs');
    }
};
