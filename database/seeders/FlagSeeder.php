<?php

namespace Database\Seeders;

use App\Models\Flag;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class FlagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama
        Flag::truncate();

        // Ambil beberapa siswa
        $students = Student::take(3)->get();
        $admin = User::where('role', 'admin')->first();

        if ($students->isEmpty()) {
            $this->command->info('Tidak ada siswa untuk membuat data flag!');
            return;
        }

        $descriptions = [
            'Siswa belum pulang setelah jam 16:00',
            'Tidak ada absensi masuk hari ini',
            'Terlambat lebih dari 30 menit',
            'Tidak ada izin untuk ketidakhadiran',
            'Data absensi tidak konsisten',
            'Siswa belum scan pulang',
            'Kehadiran tidak lengkap minggu ini',
            'Terdapat ketidaksesuaian jadwal',
            'Siswa tidak hadir 3 hari berturut-turut',
            'Perlu konfirmasi orang tua'
        ];

        $statuses = ['Aktif', 'Selesai'];

        foreach ($students as $student) {
            // Buat 1-2 flag per siswa
            $count = rand(1, 2);
            
            for ($i = 0; $i < $count; $i++) {
                $status = $statuses[array_rand($statuses)];
                
                Flag::create([
                    'student_id' => $student->id,
                    'tanggal' => now()->subDays(rand(1, 7)),
                    'keterangan' => $descriptions[array_rand($descriptions)],
                    'status' => $status,
                    'flagged_by' => $admin ? $admin->id : null,
                    'waktu_scan_pulang' => '15:30:00',
                    'waktu_flag' => now()->subHours(rand(1, 24)),
                ]);
            }
        }

        $this->command->info('Data flag berhasil ditambahkan!');
    }
}
