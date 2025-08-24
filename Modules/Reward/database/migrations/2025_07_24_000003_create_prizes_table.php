<?php

use Illuminate\Support\Facades\Schema;
use Modules\Reward\Enums\PrizeTypeEnum;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prizes', function (Blueprint $table) {
            $table->id();
            $table->enum('type', PrizeTypeEnum::values());
            $table->unsignedInteger('prize_reference_id');
            $table->string('month')->index()->comment('e.g. "1404-07"');
            $table->unsignedSmallInteger('ordering');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prizes');
    }
};
