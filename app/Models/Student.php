<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'nama',
        'nis',
        'class_schedule_id',
        'barcode',
        'ortu_id',
    ];

    /**
     * Relasi: Student belongs to ClassSchedule
     */
    public function classSchedule(): BelongsTo
    {
        return $this->belongsTo(ClassSchedule::class);
    }

    /**
     * Relasi: Student belongs to User (ortu)
     */
    public function ortu(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ortu_id');
    }

    /**
     * Relasi: Student memiliki banyak Attendance
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Relasi: Student memiliki banyak Permission
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * Relasi: Student memiliki banyak Flag
     */
    public function flags(): HasMany
    {
        return $this->hasMany(Flag::class);
    }

    /**
     * Cek apakah siswa sudah absen masuk hari ini
     */
    public function hasAbsenMasukToday(): bool
    {
        return $this->attendances()
            ->whereDate('tanggal', today())
            ->where('status', 'Hadir Masuk')
            ->exists();
    }

    /**
     * Cek apakah siswa sudah absen pulang hari ini
     */
    public function hasAbsenPulangToday(): bool
    {
        return $this->attendances()
            ->whereDate('tanggal', today())
            ->where('status', 'Hadir Pulang')
            ->exists();
    }

    /**
     * Get attendance today
     */
    public function attendanceToday()
    {
        return $this->attendances()
            ->whereDate('tanggal', today())
            ->first();
    }
}
