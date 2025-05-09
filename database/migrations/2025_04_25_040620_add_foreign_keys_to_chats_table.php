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
        Schema::table('chats', function (Blueprint $table) {
            if (!Schema::hasColumn('chats', 'receiver_id')) {
                $table->unsignedBigInteger('receiver_id'); // thêm cột receiver_id nếu chưa có
            }
            $table->foreign('assessment_star_id')
                ->references('assessment_star_id')
                ->on('assessments')
                ->onDelete('set null');

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('receiver_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->dropForeign(['assessment_star_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['receiver_id']);
        });
    }
};
