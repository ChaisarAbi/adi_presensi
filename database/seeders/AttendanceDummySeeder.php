<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $currentYear = 2026; // Tahun 2026
        $currentMonth = 1;   // Januari
        
        // Tanggal mulai: 5 Januari 2026
        $startDate = Carbon::create($currentYear, $currentMonth, 5);
        // Tanggal akhir: hari ini (19 Januari 2026)
        $endDate = Carbon::create(2026, 1, 19);
        
        foreach ($students as $student) {
            $date = $startDate->copy();
            
            while ($date->lte($endDate)) {
                // Skip weekends (Sabtu & Minggu)
                if (!$date->isWeekend()) {
                    $dayOfMonth = $date->day;
                    
                    // Tentukan status hari ini
                    $status = $this->determineStatus($dayOfMonth, $student->id);
                    
                    if ($status === 'Hadir') {
                        // Buat 2 record: Masuk dan Pulang
                        $masukTime = Carbon::createFromTime(rand(6, 8), rand(0, 59), 0);
                        $pulangTime = Carbon::createFromTime(rand(13, 15), rand(0, 59), 0);
                        
                        // Record Masuk
                        Attendance::create([
                            'student_id' => $student->id,
                            'tanggal' => $date->format('Y-m-d'),
                            'waktu' => $masukTime->format('H:i:s'),
                            'status' => 'Hadir Masuk',
                            'scanned_by' => 1,
                        ]);
                        
                        // Record Pulang
                        Attendance::create([
                            'student_id' => $student->id,
                            'tanggal' => $date->format('Y-m-d'),
                            'waktu' => $pulangTime->format('H:i:s'),
                            'status' => 'Hadir Pulang',
                            'scanned_by' => 1,
                        ]);
                    } elseif ($status === 'Izin') {
                        // Record Izin
                        $izinTime = Carbon::createFromTime(rand(8, 10), rand(0, 59), 0);
                        
                        Attendance::create([
                            'student_id' => $student->id,
                            'tanggal' => $date->format('Y-m-d'),
                            'waktu' => $izinTime->format('H:i:s'),
                            'status' => 'Izin',
                            'scanned_by' => 1,
                        ]);
                    } else {
                        // Tidak Hadir
                        $tidakHadirTime = Carbon::createFromTime(rand(8, 12), rand(0, 59), 0);
                        
                        Attendance::create([
                            'student_id' => $student->id,
                            'tanggal' => $date->format('Y-m-d'),
                            'waktu' => $tidakHadirTime->format('H:i:s'),
                            'status' => 'Tidak Hadir',
                            'scanned_by' => 1,
                        ]);
                    }
                }
                
                $date->addDay();
            }
        }
        
        $this->command->info('Dummy attendance data created from 5 Januari to 19 Januari 2026!');
    }
    
    /**
     * Determine attendance status based on day and student ID
     */
    private function determineStatus($day, $studentId)
    {
        // Pola berdasarkan hari dan ID siswa untuk variasi
        $dayPattern = $day % 10;
        $studentPattern = $studentId % 10;
        
        // Buat pola yang realistis:
        // - 80% Hadir (dengan masuk & pulang)
        // - 10% Izin
        // - 10% Tidak Hadir
        
        $combined = ($dayPattern + $studentPattern) % 20;
        
        if ($combined < 16) { // 80%
            return 'Hadir';
        } elseif ($combined < 18) { // 10%
            return 'Izin';
        } else { // 10%
            return 'Tidak Hadir';
        }
    }
    
    /**
     * Weighted random selection
     */
    private function weightedRandom($items, $weights)
    {
        $total = array_sum($weights);
        $n = rand(1, $total);
        
        foreach ($items as $i => $item) {
            $n -= $weights[$i];
            if ($n <= 0) {
                return $item;
            }
        }
        
        return $items[0];
    }
}
