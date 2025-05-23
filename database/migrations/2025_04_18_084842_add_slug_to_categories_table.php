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
        // Thêm cột 'slug' vào bảng 'categories', kiểu dữ liệu string và có thể null, đứng sau cột 'category_name'
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('category_name'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa cột 'slug' khỏi bảng 'categories'
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
