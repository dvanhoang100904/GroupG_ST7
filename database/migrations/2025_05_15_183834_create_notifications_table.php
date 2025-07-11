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
        Schema::create('notifications', function (Blueprint $table) {
        $table->id('notification_id');
        $table->unsignedBigInteger('user_id');
        $table->string('title');
        $table->text('content')->nullable();
        $table->boolean('is_read')->default(false);
        $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

        $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
