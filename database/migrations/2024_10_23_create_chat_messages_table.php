<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('chat_messages')) {
            Schema::create('chat_messages', function (Blueprint $table) {
                $table->id();
                // Đảm bảo rằng foreign key được liên kết đúng
                $table->foreignId('khach_hang_id')
                      ->nullable()  // Allow nulls if needed
                      ->constrained('khach_hangs')  // Đảm bảo ràng buộc vào bảng 'khach_hangs'
                      ->onDelete('cascade');  // Nếu khách hàng bị xóa, tin nhắn cũng sẽ bị xóa
                $table->text('message');
                $table->text('response');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('chat_messages');
    }
};
