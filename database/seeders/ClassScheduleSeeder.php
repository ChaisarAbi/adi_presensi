<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ClassSchedule;

class ClassScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schedules = [
            ['kelas' => 'Kelas 1', 'jam_masuk' => '06:30:00', 'jam_pulang' => '09:30:00'],
            ['kelas' => 'Kelas 2', 'jam_masuk' => '10:00:00', 'jam_pulang' => '12:00:00'],
            ['kelas' => 'Kelas 3', 'jam_masuk' => '12:30:00', 'jam_pulang' => '17:00:00'],
            ['kelas' => 'Kelas 4', 'jam_masuk' => '12:30:00', 'jam_pulang' => '17:00:00'],
            ['kelas' => 'Kelas 5', 'jam_masuk' => '06:30:00', 'jam_pulang' => '12:00:00'],
            ['kelas' => 'Kelas 6', 'jam_masuk' => '06:30:00', 'jam_pulang' => '12:00:00'],
        ];

        foreach ($schedules as $schedule) {
            ClassSchedule::create($schedule);
        }

        $this->command->info('Jadwal kelas berhasil ditambahkan!');
    }
}
