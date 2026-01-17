<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\User;
use App\Models\ClassSchedule;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing classes
        $classes = ClassSchedule::all();
        $ortu = User::where('role', 'ortu')->first();
        
        if (!$ortu) {
            $this->command->error('User ortu tidak ditemukan. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        $students = [
            [
                'nama' => 'Andi Wijaya',
                'nis' => '2024001',
                'class_schedule_id' => $classes->where('kelas', 'Kelas 1')->first()->id ?? 1,
                'barcode' => Str::random(10),
                'ortu_id' => $ortu->id,
            ],
            [
                'nama' => 'Budi Santoso',
                'nis' => '2024002',
                'class_schedule_id' => $classes->where('kelas', 'Kelas 2')->first()->id ?? 2,
                'barcode' => Str::random(10),
                'ortu_id' => $ortu->id,
            ],
            [
                'nama' => 'Citra Lestari',
                'nis' => '2024003',
                'class_schedule_id' => $classes->where('kelas', 'Kelas 3')->first()->id ?? 3,
                'barcode' => Str::random(10),
                'ortu_id' => $ortu->id,
            ],
            [
                'nama' => 'Dewi Anggraini',
                'nis' => '2024004',
                'class_schedule_id' => $classes->where('kelas', 'Kelas 4')->first()->id ?? 4,
                'barcode' => Str::random(10),
                'ortu_id' => $ortu->id,
            ],
            [
                'nama' => 'Eko Prasetyo',
                'nis' => '2024005',
                'class_schedule_id' => $classes->where('kelas', 'Kelas 5')->first()->id ?? 5,
                'barcode' => Str::random(10),
                'ortu_id' => $ortu->id,
            ],
            [
                'nama' => 'Fajar Nugroho',
                'nis' => '2024006',
                'class_schedule_id' => $classes->where('kelas', 'Kelas 6')->first()->id ?? 6,
                'barcode' => Str::random(10),
                'ortu_id' => $ortu->id,
            ],
        ];

        foreach ($students as $studentData) {
            Student::create($studentData);
        }

        $this->command->info('Data siswa berhasil ditambahkan!');
    }
}
