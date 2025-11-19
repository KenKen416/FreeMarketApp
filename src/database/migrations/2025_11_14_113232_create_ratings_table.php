<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rater_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ratee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('purchase_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('score')->unsigned();
            $table->timestamps();

            $table->unique(['rater_id', 'purchase_id']); // 同一取引で同じ人が複数回評価できないように
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
}
