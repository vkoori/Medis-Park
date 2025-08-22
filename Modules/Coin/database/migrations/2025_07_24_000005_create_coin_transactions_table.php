<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Coin\Enums\TransactionReferenceTypeEnum;
use Modules\Coin\Enums\TransactionStatusEnum;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coin_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete()->restrictOnUpdate();
            $table->integer('amount')->comment('positive is earn and negative is burn');
            $table->string('reason')->comment('e.g. profile_level1, purchased');
            $table->enum('reference_type', TransactionReferenceTypeEnum::values());
            $table->unsignedBigInteger('reference_id');
            $table->enum('status', TransactionStatusEnum::values());
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coin_transactions');
    }
};
