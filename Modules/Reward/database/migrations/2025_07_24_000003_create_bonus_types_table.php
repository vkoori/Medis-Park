<?php

use Illuminate\Support\Facades\Schema;
use Modules\Reward\Enums\BonusTypeEnum;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bonus_types', function (Blueprint $table) {
            $table->id();
            $table->enum('type', BonusTypeEnum::values());
            $table->string('sub_type', 50)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bonus_types');
    }
};
