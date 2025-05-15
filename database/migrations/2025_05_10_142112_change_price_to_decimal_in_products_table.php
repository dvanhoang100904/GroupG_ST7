<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Chuyển kiểu dữ liệu từ string sang DECIMAL(15, 2)
            $table->decimal('price', 15, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Trả về kiểu string cũ nếu rollback
            $table->string('price', 50)->nullable()->change();
        });
    }
};

