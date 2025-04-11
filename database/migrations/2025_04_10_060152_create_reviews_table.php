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
        Schema::create('reviews', function (Blueprint $table) {
            $table->bigIncrements('review_id');

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('chat_id');
            $table->string('photo', 255)->nullable();
            $table->enum('type', ['review', 'chat_reply']);
            $table->timestamps();
        
        });       

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
