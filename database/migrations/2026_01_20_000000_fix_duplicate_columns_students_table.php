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
        // Fix for duplicate column errors on VPS
        Schema::table('students', function (Blueprint $table) {
            // Check if 'jenis_kelamin' column exists before adding
            if (!Schema::hasColumn('students', 'jenis_kelamin')) {
                $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('nama');
            }
            
            // Check if 'rombel' column exists before adding
            if (!Schema::hasColumn('students', 'rombel')) {
                $table->string('rombel', 10)->nullable()->after('jenis_kelamin');
            }
            
            // Check if 'kelas' column exists before adding (from other migration)
            if (!Schema::hasColumn('students', 'kelas')) {
                $table->string('kelas', 20)->nullable()->after('rombel');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop columns in down() to avoid data loss
        // This is a fix migration, not a regular migration
    }
};
