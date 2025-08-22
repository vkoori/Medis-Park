<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_available', function (Blueprint $table) {
            $table->id();
            $table->string('month')->index()->comment('e.g. "1404-07"');
            $table->foreignId('product_id')->constrained()->restrictOnDelete()->restrictOnUpdate();
            $table->unsignedSmallInteger('ordering');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_available');
    }
};
