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
            $table->foreignId('flagged_by')->nullable()->after('student_id')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flags', function (Blueprint $table) {
            $table->dropForeign(['flagged_by']);
            $table->dropColumn('flagged_by');
        });
    }
};
