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
        Schema::table('flags', function (Blueprint $table) {
            $table->time('waktu_scan_pulang')->nullable()->change();
            $table->timestamp('waktu_flag')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flags', function (Blueprint $table) {
            $table->time('waktu_scan_pulang')->nullable(false)->change();
            $table->timestamp('waktu_flag')->nullable(false)->change();
        });
    }
};
