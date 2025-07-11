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
       Schema::create('favorites', function (Blueprint $table) {
    $table->unsignedBigInteger('user_id');
    $table->unsignedBigInteger('product_id');

    $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
    $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');

    $table->primary(['user_id', 'product_id']); // Khóa chính kép
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
