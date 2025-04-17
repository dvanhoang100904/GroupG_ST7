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
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['chờ_xử_lý', 'đang_xử_lý', 'đang_vận_chuyển', 'đã_giao_hàng', 'đã_hủy'])->default('chờ_xử_lý');
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
