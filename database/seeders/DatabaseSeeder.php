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
            PermissionSeeder::class,
            FlagSeeder::class,
        ]);
    }
}
