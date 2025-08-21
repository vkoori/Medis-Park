<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coin_available', function (Blueprint $table) {
            $table->id();
            $table->string('month')->index()->comment('e.g. "1404-07"');
            $table->unsignedInteger('amount');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coin_available');
    }
};
