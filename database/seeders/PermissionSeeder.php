<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama
        Permission::truncate();

        // Ambil beberapa siswa
        $students = Student::take(5)->get();

        if ($students->isEmpty()) {
            $this->command->info('Tidak ada siswa untuk membuat data izin!');
            return;
        }

        $reasons = [
            'Sakit demam tinggi',
            'Keluarga ada acara penting',
            'Izin ke dokter gigi',
            'Mengikuti lomba di luar kota',
            'Keperluan keluarga mendadak',
            'Ibadah keluarga',
            'Kunjungan ke rumah sakit',
            'Persiapan ujian di rumah',
            'Kegiatan ekstrakurikuler',
            'Perjalanan keluarga'
        ];

        $statuses = ['Pending', 'Disetujui', 'Ditolak'];

        foreach ($students as $student) {
            // Buat 2-3 izin per siswa
            $count = rand(2, 3);
            
            for ($i = 0; $i < $count; $i++) {
                $status = $statuses[array_rand($statuses)];
                
                Permission::create([
                    'student_id' => $student->id,
                    'alasan' => $reasons[array_rand($reasons)],
                    'foto_bukti' => 'permissions/dummy.jpg', // File dummy
                    'status' => $status,
                    'keterangan' => $status == 'Ditolak' ? 'Dokumen tidak lengkap' : null,
                    'tanggal' => now()->subDays(rand(1, 30)),
                ]);
            }
        }

        $this->command->info('Data izin berhasil ditambahkan!');
    }
}
