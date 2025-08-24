<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prize_coins', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('amount');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prize_coins');
    }
};
