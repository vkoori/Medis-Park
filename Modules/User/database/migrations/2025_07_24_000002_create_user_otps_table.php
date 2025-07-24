<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\User\Enums\OtpTypeEnum;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_otps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete()->restrictOnUpdate();
            $table->string('otp_hash', 255);
            $table->enum('type', OtpTypeEnum::values());
            $table->boolean('used')->default(false);
            $table->timestamp('created_at');
            $table->timestamp('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_otps');
    }
};
