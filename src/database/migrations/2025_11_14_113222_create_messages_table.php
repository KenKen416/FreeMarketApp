<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            // purchase (取引) に紐づけ
            $table->foreignId('purchase_id')->constrained()->onDelete('cascade');
            // 送信者（ユーザー）
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->text('body');
            $table->string('image')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // インデックス：未読カウントや最新ソートに役立つ
            $table->index(['purchase_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
}
