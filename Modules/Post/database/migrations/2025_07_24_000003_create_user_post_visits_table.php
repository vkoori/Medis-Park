<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Post\Enums\UserPostVisitEnum;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_post_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete()->restrictOnUpdate();
            $table->foreignId('post_id')->constrained()->restrictOnDelete()->restrictOnUpdate();
            $table->enum('type', UserPostVisitEnum::values());
            $table->timestamp('first_visited_at');
            $table->unique(['user_id', 'post_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_post_visits');
    }
};
