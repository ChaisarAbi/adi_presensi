<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ClassScheduleSeeder::class,
            UserSeeder::class,
            WalikelasSeeder::class,
            StudentSeeder::class,
            OrtuAccountSeeder::class,
            HolidaySeeder::class,
            PermissionSeeder::class,
            FlagSeeder::class,
            AttendanceDummySeeder::class,    // Data 5-19 Jan 2026
            TwoWeekBackWithTodaySeeder::class, // Data 9-23 Jan 2026 (skip duplicate)
        ]);
    }
}
