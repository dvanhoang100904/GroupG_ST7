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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('order_id');
            $table->decimal('total_price', total: 10, places: 2);
            $table->enum('status', ['cho_xu_ly', 'dang_xu_ly', 'dang_van_chuyen', 'da_giao_hang', 'da_huy'])->default('cho_xu_ly');
            $table->string('notes', 255)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('shipping_address_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
