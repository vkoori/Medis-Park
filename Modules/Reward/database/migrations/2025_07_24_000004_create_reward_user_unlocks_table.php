<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reward_user_unlocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete()->restrictOnUpdate();
            $table->foreignId('reward_id')->constrained()->restrictOnDelete()->restrictOnUpdate();
            $table->timestamp('unlocked_at');
            $table->timestamp('created_at');
            $table->unique(['user_id', 'reward_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reward_user_unlocks');
    }
};
