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
            $table->id();
            $table->integer('notification_type')->comment('0 => user add comment on my post, 1 => user add like on my post, 2 => user send me a friend request, 3 => user accept my friend request');
            $table->integer('post_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('target_user_id');
            $table->string('content');
            $table->boolean('shown')->default(false);
            $table->timestamps();
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
