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
        // Tạo bảng 'categories'
        Schema::create('categories', function (Blueprint $table) {
            // Tạo cột 'category_id' làm khóa chính tự tăng
            $table->bigIncrements('category_id');
            // Tạo cột 'category_name' với độ dài tối đa là 255 ký tự
            $table->string('category_name', 255);
            // Tạo cột 'description' có kiểu dữ liệu 'text', có thể null
            $table->text('description')->nullable();
            // Tạo các cột 'created_at' và 'updated_at' tự động
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa bảng 'categories' nếu tồn tại
        Schema::dropIfExists('categories');
    }
};

