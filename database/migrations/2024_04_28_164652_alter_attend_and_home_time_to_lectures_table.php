<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lectures', function (Blueprint $table) {
            $table->time('attend_time')->nullable()->after('date')->default(env('MAX_ATTENDANCE'));
            $table->time('home_time')->nullable()->after('attend_time')->default(env('MIN_HOME'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lectures', function (Blueprint $table) {
            $table->dropColumn(['attend_time', 'home_time']);
        });
    }
};
