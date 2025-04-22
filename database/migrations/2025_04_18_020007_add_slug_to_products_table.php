<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Phương thức này được gọi khi thực hiện migration.
     * Nó sẽ thêm một cột 'slug' vào bảng 'products'.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Thêm cột 'slug' kiểu chuỗi (string), có thể để trống (nullable)
            // Cột này sẽ được thêm sau cột 'product_name'
            $table->string('slug')->nullable()->after('product_name');
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Phương thức này được gọi khi thực hiện rollback (hoàn tác) migration.
     * Nó sẽ xóa cột 'slug' khỏi bảng 'products'.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Loại bỏ cột 'slug' khỏi bảng 'products'
            $table->dropColumn('slug');
        });
    }
};
