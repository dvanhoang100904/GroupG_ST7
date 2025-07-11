<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->bigIncrements('review_id');
            $table->text('content')->nullable();
            $table->integer('rating')->default(0);
            $table->string('type', 50)->default('review'); // phân loại review/chat
            $table->string('photo')->nullable();
            $table->unsignedBigInteger('user_id');     
            $table->unsignedBigInteger('product_id'); 
            $table->unsignedBigInteger('chat_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
