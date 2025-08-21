<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bonus_type_amounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bonus_type_id')->nullable()->constrained()->restrictOnDelete()->restrictOnUpdate();
            $table->unsignedInteger('amount');
            $table->foreignId('created_by')->nullable()->constrained('users')->restrictOnDelete()->restrictOnUpdate();
            $table->timestamp('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bonus_type_amounts');
    }
};
