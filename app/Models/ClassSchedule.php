<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassSchedule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'kelas',
        'jam_masuk',
        'jam_pulang',
    ];

    /**
     * Relasi: ClassSchedule memiliki banyak Student
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Format jam untuk display
     */
    public function getJamMasukFormattedAttribute(): string
    {
        return date('H:i', strtotime($this->jam_masuk));
    }

    /**
     * Format jam untuk display
     */
    public function getJamPulangFormattedAttribute(): string
    {
        return date('H:i', strtotime($this->jam_pulang));
    }
}
