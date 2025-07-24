<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Reward\Enums\ProfileLevelEnum;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reward_profiles', function (Blueprint $table) {
            $table->id();
            $table->enum('level', ProfileLevelEnum::values());
            $table->unsignedInteger('amount');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reward_profiles');
    }
};
