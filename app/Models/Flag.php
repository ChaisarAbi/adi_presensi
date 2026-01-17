<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Flag extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'tanggal',
        'keterangan',
        'status',
        'flagged_by',
        'waktu_scan_pulang',
        'waktu_flag',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date',
        'waktu_flag' => 'datetime',
    ];

    /**
     * Get the student that owns the flag.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the user who flagged.
     */
    public function flaggedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'flagged_by');
    }

    /**
     * Scope a query to only include active flags.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Aktif');
    }

    /**
     * Scope a query to only include resolved flags.
     */
    public function scopeResolved($query)
    {
        return $query->where('status', 'Selesai');
    }

    /**
     * Get the status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'Aktif' => 'bg-danger',
            'Selesai' => 'bg-success',
            default => 'bg-secondary',
        };
    }

    /**
     * Get the status text.
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'Aktif' => 'Aktif',
            'Selesai' => 'Selesai',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Get the formatted date.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->tanggal->format('d/m/Y');
    }

    /**
     * Get the formatted time.
     */
    public function getFormattedTimeAttribute(): ?string
    {
        return $this->waktu_flag ? $this->waktu_flag->format('H:i') : null;
    }
}
