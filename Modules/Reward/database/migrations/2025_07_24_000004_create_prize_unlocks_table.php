<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Reward\Enums\PrizeUnlockTypeEnum;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prize_unlocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prize_id')->nullable()->constrained()->restrictOnDelete()->restrictOnUpdate();
            $table->enum('type', PrizeUnlockTypeEnum::values());
            $table->foreignId('user_id')->nullable()->constrained()->restrictOnDelete()->restrictOnUpdate();
            $table->timestamp('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prize_unlocks');
    }
};
