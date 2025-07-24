<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->id();
            $table->string('disk', 60);
            $table->string('bucket', 60);
            $table->string('path', 255);
            $table->string('name', 255);
            $table->string('original_name', 255);
            $table->string('mime', 50);
            $table->string('ext', 10);
            $table->unsignedBigInteger('size');
            $table->unsignedBigInteger('width')->nullable();
            $table->unsignedBigInteger('height')->nullable();
            $table->boolean('is_private')->default(false);
            $table->timestamp('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medias');
    }
};
