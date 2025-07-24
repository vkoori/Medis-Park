<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Reward\Enums\ProfileLevelEnum;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('profile_fields', function (Blueprint $table) {
            $table->id();
            $table->string('key', 64)->unique();
            $table->enum('level', ProfileLevelEnum::values());
            $table->foreignId('updated_by')->nullable()->constrained('users')->restrictOnDelete()->restrictOnUpdate();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_fields');
    }
};
