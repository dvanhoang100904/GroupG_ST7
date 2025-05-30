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
        // Thêm ràng buộc unique cho cột 'slug' trong bảng 'categories'
        Schema::table('categories', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->unique('slug');  // Tạo chỉ mục duy nhất cho cột 'slug'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa ràng buộc unique cho cột 'slug' trong bảng 'categories'
        Schema::table('categories', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->dropUnique(['slug']);  // Loại bỏ chỉ mục duy nhất của cột 'slug'
        });
    }
};
