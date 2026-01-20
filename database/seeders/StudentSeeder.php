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
        
        // Data siswa sample sesuai permintaan
        $students = [
            [
                'nama' => 'Afdhal Gilang Aditya',
                'nis' => '3166443207',
                'jenis_kelamin' => 'L',
                'kelas' => '1',
                'rombel' => 'Kelas 1-A',
                'class_schedule_id' => $classes->where('kelas', 'Kelas 1')->first()->id ?? 1,
                'barcode' => Str::random(10),
                'ortu_id' => null, // Akan diisi setelah seeder ortu dibuat
            ],
            [
                'nama' => 'AQILLA PUTRI AZZAHRA',
                'nis' => '3185634794',
                'jenis_kelamin' => 'P',
                'kelas' => '1',
                'rombel' => 'Kelas 1-A',
                'class_schedule_id' => $classes->where('kelas', 'Kelas 1')->first()->id ?? 1,
                'barcode' => Str::random(10),
                'ortu_id' => null,
            ],
            [
                'nama' => 'ADINDA PUTRI ANJANI',
                'nis' => '3175051777',
                'jenis_kelamin' => 'P',
                'kelas' => '2',
                'rombel' => 'Kelas 2-A',
                'class_schedule_id' => $classes->where('kelas', 'Kelas 2')->first()->id ?? 2,
                'barcode' => Str::random(10),
                'ortu_id' => null,
            ],
            [
                'nama' => 'AKHTAR ALFAEYZA',
                'nis' => '3172544276',
                'jenis_kelamin' => 'L',
                'kelas' => '2',
                'rombel' => 'Kelas 2-A',
                'class_schedule_id' => $classes->where('kelas', 'Kelas 2')->first()->id ?? 2,
                'barcode' => Str::random(10),
                'ortu_id' => null,
            ],
            [
                'nama' => 'Aleisha Rizky Bachtiar',
                'nis' => '3172710194',
                'jenis_kelamin' => 'P',
                'kelas' => '2',
                'rombel' => 'Kelas 2-A',
                'class_schedule_id' => $classes->where('kelas', 'Kelas 2')->first()->id ?? 2,
                'barcode' => Str::random(10),
                'ortu_id' => null,
            ],
            [
                'nama' => 'ADEEVA UFAIRA RAMADHANI',
                'nis' => '3163864939',
                'jenis_kelamin' => 'P',
                'kelas' => '3',
                'rombel' => 'Kelas 3-A',
                'class_schedule_id' => $classes->where('kelas', 'Kelas 3')->first()->id ?? 3,
                'barcode' => Str::random(10),
                'ortu_id' => null,
            ],
            [
                'nama' => 'AHMAD NAWAS RIZKY YANTO',
                'nis' => '3151492199',
                'jenis_kelamin' => 'L',
                'kelas' => '3',
                'rombel' => 'Kelas 3-A',
                'class_schedule_id' => $classes->where('kelas', 'Kelas 3')->first()->id ?? 3,
                'barcode' => Str::random(10),
                'ortu_id' => null,
            ],
            [
                'nama' => 'Aditya Pradipta Amzari',
                'nis' => '3152366516',
                'jenis_kelamin' => 'L',
                'kelas' => '4',
                'rombel' => 'Kelas 4-A',
                'class_schedule_id' => $classes->where('kelas', 'Kelas 4')->first()->id ?? 4,
                'barcode' => Str::random(10),
                'ortu_id' => null,
            ],
            [
                'nama' => 'Afifah Azalia Sutrisno',
                'nis' => '3143771364',
                'jenis_kelamin' => 'P',
                'kelas' => '4',
                'rombel' => 'Kelas 4-A',
                'class_schedule_id' => $classes->where('kelas', 'Kelas 4')->first()->id ?? 4,
                'barcode' => Str::random(10),
                'ortu_id' => null,
            ],
            [
                'nama' => 'Abidah Daniyah Zarra',
                'nis' => '3158570556',
                'jenis_kelamin' => 'P',
                'kelas' => '5',
                'rombel' => 'Kelas 5-A',
                'class_schedule_id' => $classes->where('kelas', 'Kelas 5')->first()->id ?? 5,
                'barcode' => Str::random(10),
                'ortu_id' => null,
            ],
            [
                'nama' => 'Abyan Nandana Putra',
                'nis' => '3141019661',
                'jenis_kelamin' => 'L',
                'kelas' => '5',
                'rombel' => 'Kelas 5-A',
                'class_schedule_id' => $classes->where('kelas', 'Kelas 5')->first()->id ?? 5,
                'barcode' => Str::random(10),
                'ortu_id' => null,
            ],
            [
                'nama' => 'Achmad Alvino Sidqi',
                'nis' => '0147583277',
                'jenis_kelamin' => 'L',
                'kelas' => '6',
                'rombel' => 'Kelas 6-A',
                'class_schedule_id' => $classes->where('kelas', 'Kelas 6')->first()->id ?? 6,
                'barcode' => Str::random(10),
                'ortu_id' => null,
            ],
            [
                'nama' => 'Adzkiannisa Bintang Asshofi',
                'nis' => '3148749494',
                'jenis_kelamin' => 'P',
                'kelas' => '6',
                'rombel' => 'Kelas 6-A',
                'class_schedule_id' => $classes->where('kelas', 'Kelas 6')->first()->id ?? 6,
                'barcode' => Str::random(10),
                'ortu_id' => null,
            ],
        ];

        foreach ($students as $studentData) {
            Student::create($studentData);
        }

        $this->command->info('Data 13 siswa sample berhasil ditambahkan!');
    }
}
