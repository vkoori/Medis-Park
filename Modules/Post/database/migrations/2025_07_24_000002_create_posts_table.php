<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banner')->nullable()->constrained('medias')->restrictOnDelete()->restrictOnUpdate();
            $table->string('title', 100);
            $table->text('content');
            $table->timestamp('available_at');
            $table->timestamp('expired_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->restrictOnDelete()->restrictOnUpdate();
            $table->timestamps();
            $table->index(['available_at', 'expired_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
