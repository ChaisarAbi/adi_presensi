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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('waktu');
            $table->enum('status', ['Hadir Masuk', 'Hadir Pulang', 'Izin', 'Tidak Hadir']);
            $table->foreignId('scanned_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Unique constraint: satu siswa hanya bisa absen masuk dan pulang sekali per hari
            $table->unique(['student_id', 'tanggal', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
