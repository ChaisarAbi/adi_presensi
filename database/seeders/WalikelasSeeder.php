<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WalikelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $walikelas = [
            ['nama' => 'RIEZKA AYU NURSAFITRI, S.Pd', 'kelas' => '1'],
            ['nama' => 'AMARIZA FATHIA, S.Pd', 'kelas' => '2'],
            ['nama' => 'NURSIAH, S.Pd', 'kelas' => '3'],
            ['nama' => 'MAWADAH KHOLIQIYAH', 'kelas' => '4'],
            ['nama' => 'INDI NURSIFA, S.Pd', 'kelas' => '5'],
            ['nama' => 'ADITIA PERMANA PUTRA, S.Pd', 'kelas' => '6'],
        ];

        DB::table('walikelas')->insert($walikelas);
    }
}
