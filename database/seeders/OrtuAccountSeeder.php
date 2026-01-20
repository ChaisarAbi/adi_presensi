<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class OrtuAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data orang tua sesuai dengan data siswa
        $ortuData = [
            ['nama_ortu' => 'Nurma Karnita', 'nama_siswa' => 'Afdhal Gilang Aditya'],
            ['nama_ortu' => 'ELI ERMAWATI', 'nama_siswa' => 'AQILLA PUTRI AZZAHRA'],
            ['nama_ortu' => 'ENI HERYANI', 'nama_siswa' => 'ADINDA PUTRI ANJANI'],
            ['nama_ortu' => 'PINGKI FARADILA', 'nama_siswa' => 'AKHTAR ALFAEYZA'],
            ['nama_ortu' => 'Ratu Siti Djubaedah', 'nama_siswa' => 'Aleisha Rizky Bachtiar'],
            ['nama_ortu' => 'SANTI SUSILAWATI', 'nama_siswa' => 'ADEEVA UFAIRA RAMADHANI'],
            ['nama_ortu' => 'NIYEHAH', 'nama_siswa' => 'AHMAD NAWAS RIZKY YANTO'],
            ['nama_ortu' => 'Satiyah', 'nama_siswa' => 'Aditya Pradipta Amzari'],
            ['nama_ortu' => 'Wasti', 'nama_siswa' => 'Afifah Azalia Sutrisno'],
            ['nama_ortu' => 'Rika Haryani', 'nama_siswa' => 'Abidah Daniyah Zarra'],
            ['nama_ortu' => 'Rica Marta', 'nama_siswa' => 'Abyan Nandana Putra'],
            ['nama_ortu' => 'Kiki Damayanti', 'nama_siswa' => 'Achmad Alvino Sidqi'],
            ['nama_ortu' => 'Latifah Hasan', 'nama_siswa' => 'Adzkiannisa Bintang Asshofi'],
        ];

        foreach ($ortuData as $data) {
            // Buat username dari nama ortu (hapus spasi, lowercase)
            $username = strtolower(str_replace(' ', '', $data['nama_ortu']));
            $email = $username . '@example.com';
            
            // Cek apakah user sudah ada
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => $data['nama_ortu'],
                    'email' => $email,
                    'password' => Hash::make('password123'),
                    'role' => 'ortu',
                    'phone' => '08123456789', // Default phone
                ]);
            }

            // Update student dengan ortu_id
            $student = Student::where('nama', $data['nama_siswa'])->first();
            if ($student) {
                $student->ortu_id = $user->id;
                $student->save();
            }
        }

        $this->command->info('13 akun orang tua berhasil dibuat dan dihubungkan dengan siswa!');
    }
}
