<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Walikelas extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'kelas',
    ];

    /**
     * Get the students for the walikelas.
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'kelas', 'kelas');
    }
}
