<?php

use Carbon\Carbon;
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
            $table->time('attend_opened_at')->nullable()->after('date')->default(Carbon::createFromTimeString(env('MAX_ATTENDANCE'))->subHour()->format('H:i:s'));
            $table->time('home_closed_at')->nullable()->after('home_time')->default(Carbon::createFromTimeString(env('MIN_HOME'))->addHour()->format('H:i:s'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lectures', function (Blueprint $table) {
            $table->dropColumn(['attend_opened_at', 'home_closed_at']);
        });
    }
};
