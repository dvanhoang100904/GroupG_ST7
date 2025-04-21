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
        // Tạo bảng 'products'
        Schema::create('products', function (Blueprint $table) {
            // Tạo cột 'product_id' làm khóa chính tự tăng
            $table->bigIncrements('product_id');
            // Tạo cột 'product_name' kiểu chuỗi với độ dài tối đa là 255 ký tự
            $table->string('product_name', 255);
            // Tạo cột 'description' kiểu chuỗi với độ dài tối đa 255 ký tự, có thể null
            $table->string('description', 255)->nullable();
            // Tạo cột 'image' kiểu văn bản, có thể null, lưu đường dẫn ảnh
            $table->text('image')->nullable();
            // Tạo cột 'price' kiểu chuỗi, có thể null, dùng để lưu giá sản phẩm
            $table->string('price', 50)->nullable();
            // Tạo cột 'category_id' kiểu unsignedBigInteger, dùng làm khóa ngoại đến bảng 'categories'
            $table->unsignedBigInteger('category_id');
            // Tạo các cột 'created_at' và 'updated_at' tự động
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa bảng 'products' nếu tồn tại
        Schema::dropIfExists('products');
    }
};
