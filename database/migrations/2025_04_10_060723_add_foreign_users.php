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
        // Foreign keys
        Schema::table('users', function (Blueprint $table) {
            // khóa ngoại
          $table->foreign('role_id')->references('role_id')->on('roles')->onDelete('cascade');
            $table->foreign('chat_id')->references('chat_id')->on('chats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['chat_id']);
        });
    }
};
