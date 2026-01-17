<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'alasan',
        'foto_bukti',
        'status',
        'keterangan',
        'tanggal',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Get the student that owns the permission.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Scope a query to only include pending permissions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    /**
     * Scope a query to only include approved permissions.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'Disetujui');
    }

    /**
     * Scope a query to only include rejected permissions.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'Ditolak');
    }

    /**
     * Get the status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'Pending' => 'bg-warning',
            'Disetujui' => 'bg-success',
            'Ditolak' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Get the status text.
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'Pending' => 'Menunggu',
            'Disetujui' => 'Disetujui',
            'Ditolak' => 'Ditolak',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Get the photo URL.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->foto_bukti ? asset('storage/' . $this->foto_bukti) : null;
    }
}
