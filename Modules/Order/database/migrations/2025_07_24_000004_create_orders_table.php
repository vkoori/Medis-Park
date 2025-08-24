<?php

use Illuminate\Support\Facades\Schema;
use Modules\Order\Enums\OrderStatusEnum;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete()->restrictOnUpdate();
            $table->foreignId('product_id')->nullable()->constrained()->restrictOnDelete()->restrictOnUpdate();
            $table->foreignId('post_id')->nullable()->constrained()->restrictOnDelete()->restrictOnUpdate();
            $table->enum('status', OrderStatusEnum::values());
            $table->unsignedBigInteger('coin_value');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
