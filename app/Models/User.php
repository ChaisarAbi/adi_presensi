<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'nip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
        ];
    }

    /**
     * Relasi: User (ortu) memiliki banyak siswa
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'ortu_id');
    }

    /**
     * Relasi: User (guru) melakukan banyak scan absensi
     */
    public function attendancesScanned()
    {
        return $this->hasMany(Attendance::class, 'scanned_by');
    }

    /**
     * Cek jika user adalah admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Cek jika user adalah guru
     */
    public function isGuru()
    {
        return $this->role === 'guru';
    }

    /**
     * Cek jika user adalah orang tua
     */
    public function isOrtu()
    {
        return $this->role === 'ortu';
    }
}
