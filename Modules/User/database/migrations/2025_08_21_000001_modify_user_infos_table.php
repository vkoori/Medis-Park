<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('user_infos', function (Blueprint $table) {
            $table->string('email')->nullable()->after('national_code');
            $table->string('address', 500)->nullable()->after('last_name');
        });
    }

    public function down(): void
    {
        Schema::table('user_infos', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('address');
        });
    }
};
