<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $holidays = [
            [
                'tanggal' => Carbon::now()->year . '-01-01',
                'keterangan' => 'Tahun Baru Masehi'
            ],
            [
                'tanggal' => Carbon::now()->year . '-03-11',
                'keterangan' => 'Hari Raya Nyepi'
            ],
            [
                'tanggal' => Carbon::now()->year . '-04-18',
                'keterangan' => 'Wafat Isa Al-Masih'
            ],
            [
                'tanggal' => Carbon::now()->year . '-05-01',
                'keterangan' => 'Hari Buruh Internasional'
            ],
            [
                'tanggal' => Carbon::now()->year . '-05-29',
                'keterangan' => 'Kenaikan Isa Al-Masih'
            ],
            [
                'tanggal' => Carbon::now()->year . '-06-01',
                'keterangan' => 'Hari Lahir Pancasila'
            ],
            [
                'tanggal' => Carbon::now()->year . '-06-17',
                'keterangan' => 'Hari Raya Idul Fitri 1446 H'
            ],
            [
                'tanggal' => Carbon::now()->year . '-06-18',
                'keterangan' => 'Hari Raya Idul Fitri 1446 H (2)'
            ],
            [
                'tanggal' => Carbon::now()->year . '-08-17',
                'keterangan' => 'Hari Kemerdekaan RI'
            ],
            [
                'tanggal' => Carbon::now()->year . '-09-16',
                'keterangan' => 'Hari Raya Idul Adha 1446 H'
            ],
            [
                'tanggal' => Carbon::now()->year . '-12-25',
                'keterangan' => 'Hari Raya Natal'
            ],
        ];

        foreach ($holidays as $holiday) {
            // Cek apakah tanggal sudah lewat
            if (Carbon::parse($holiday['tanggal'])->isFuture() || Carbon::parse($holiday['tanggal'])->isToday()) {
                Holiday::firstOrCreate(
                    ['tanggal' => $holiday['tanggal']],
                    ['keterangan' => $holiday['keterangan']]
                );
            }
        }

        $this->command->info('Holiday seeder completed.');
    }
}
