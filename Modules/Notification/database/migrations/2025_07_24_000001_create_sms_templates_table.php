<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template', 100)->unique();
            $table->string('content', 500);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_templates');
    }
};
