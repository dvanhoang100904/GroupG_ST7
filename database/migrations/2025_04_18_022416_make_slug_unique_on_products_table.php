<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Phương thức này sẽ được chạy khi thực hiện migration.
     * Thực hiện việc thêm ràng buộc unique cho cột 'slug' trong bảng 'products'.
     */
    public function up(): void
    {
        // Thêm ràng buộc unique cho cột 'slug' trong bảng 'products'
        Schema::table('products', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->unique('slug');  // Tạo chỉ mục duy nhất cho cột 'slug'
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Phương thức này sẽ được chạy khi thực hiện rollback (hoàn tác) migration.
     * Loại bỏ ràng buộc unique cho cột 'slug' trong bảng 'products'.
     */
    public function down(): void
    {
        // Xóa ràng buộc unique cho cột 'slug' trong bảng 'products'
        Schema::table('products', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->dropUnique(['slug']);  // Loại bỏ chỉ mục duy nhất của cột 'slug'
        });
    }
};
