<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reward_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('amount');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reward_posts');
    }
};
