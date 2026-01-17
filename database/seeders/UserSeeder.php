<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Presensi',
            'email' => 'admin@presensi.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Guru contoh
        User::create([
            'name' => 'Guru Budi',
            'email' => 'guru@presensi.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'guru',
        ]);

        // Orang tua contoh
        User::create([
            'name' => 'Orang Tua Andi',
            'email' => 'ortu@presensi.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'ortu',
        ]);

        $this->command->info('User admin, guru, dan ortu berhasil ditambahkan!');
        $this->command->info('Email: admin@presensi.sch.id | Password: password123');
        $this->command->info('Email: guru@presensi.sch.id | Password: password123');
        $this->command->info('Email: ortu@presensi.sch.id | Password: password123');
    }
}
