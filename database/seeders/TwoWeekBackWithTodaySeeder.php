<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\ClassSchedule;
use Carbon\Carbon;

class TwoWeekBackWithTodaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua siswa
        $students = Student::all();
        
        // Ambil jadwal per kelas (key by kelas)
        $classSchedules = ClassSchedule::all()->keyBy('kelas');
        
        // Periode: 9-23 Januari 2026
        $today = Carbon::today(); // 23 Jan 2026
        $startDate = $today->copy()->subDays(14); // 9 Jan 2026
        $endDate = $today; // 23 Jan 2026 (hari ini)
        
        $totalDays = 0;
        $totalRecords = 0;
        
        foreach ($students as $student) {
            $date = $startDate->copy();
            
            while ($date->lte($endDate)) {
                // Skip weekend (Sabtu & Minggu)
                if (!$date->isWeekend()) {
                    // Cek apakah data sudah ada untuk tanggal ini
                    $existing = Attendance::where('student_id', $student->id)
                        ->whereDate('tanggal', $date)
                        ->exists();
                    
                    if (!$existing) {
                        // Dapatkan jadwal kelas siswa
                        // Handle kedua format: "1" dan "Kelas 1"
                        $kelasKey = $student->kelas;
                        $schedule = $classSchedules[$kelasKey] ?? $classSchedules["Kelas " . $kelasKey] ?? null;
                        
                        if ($schedule) {
                            // Tentukan status (90% Hadir, 10% Izin)
                            $status = $this->determineStatus($date->day, $student->id);
                            
                            if ($status === 'Hadir') {
                                // Buat record Masuk
                                $masukTime = $this->generateMasukTime($schedule->jam_masuk);
                                
                                Attendance::create([
                                    'student_id' => $student->id,
                                    'tanggal' => $date->format('Y-m-d'),
                                    'waktu' => $masukTime->format('H:i:s'),
                                    'status' => 'Hadir Masuk',
                                    'scanned_by' => 1,
                                ]);
                                $totalRecords++;
                                
                                // Jika bukan hari ini, buat record Pulang juga
                                if (!$date->isSameDay($today)) {
                                    $pulangTime = $this->generatePulangTime($schedule->jam_pulang);
                                    
                                    Attendance::create([
                                        'student_id' => $student->id,
                                        'tanggal' => $date->format('Y-m-d'),
                                        'waktu' => $pulangTime->format('H:i:s'),
                                        'status' => 'Hadir Pulang',
                                        'scanned_by' => 1,
                                    ]);
                                    $totalRecords++;
                                }
                            } elseif ($status === 'Izin') {
                                // Buat record Izin
                                $izinTime = $this->generateIzinTime($schedule->jam_masuk, $schedule->jam_pulang);
                                
                                Attendance::create([
                                    'student_id' => $student->id,
                                    'tanggal' => $date->format('Y-m-d'),
                                    'waktu' => $izinTime->format('H:i:s'),
                                    'status' => 'Izin',
                                    'scanned_by' => 1,
                                ]);
                                $totalRecords++;
                            }
                            // Tidak ada status "Tidak Hadir" (0%)
                            
                            $totalDays++;
                        } else {
                            $this->command->warn("⚠️ No schedule found for student {$student->id} ({$student->nama}) with kelas: '{$student->kelas}'");
                        }
                    }
                }
                
                $date->addDay();
            }
        }
        
        $this->command->info("✅ Dummy attendance created successfully!");
        $this->command->info("📅 Period: {$startDate->format('d M Y')} to {$endDate->format('d M Y')}");
        $this->command->info("👥 Students: " . $students->count());
        $this->command->info("📊 Total days with attendance: {$totalDays}");
        $this->command->info("📈 Total records created: {$totalRecords}");
    }
    
    /**
     * Determine attendance status (90% Hadir, 10% Izin)
     */
    private function determineStatus($day, $studentId)
    {
        // Pola berdasarkan hari dan ID siswa untuk variasi
        $dayPattern = $day % 10;
        $studentPattern = $studentId % 10;
        
        // 90% Hadir, 10% Izin
        $combined = ($dayPattern + $studentPattern) % 20;
        
        if ($combined < 18) { // 90% (18/20)
            return 'Hadir';
        } else { // 10% (2/20)
            return 'Izin';
        }
    }
    
    /**
     * Generate waktu masuk dengan variance ±15 menit dari jadwal
     */
    private function generateMasukTime($jamMasuk)
    {
        $time = Carbon::parse($jamMasuk);
        $variance = rand(-15, 15); // ±15 menit
        return $time->addMinutes($variance);
    }
    
    /**
     * Generate waktu pulang dengan variance ±30 menit dari jadwal
     */
    private function generatePulangTime($jamPulang)
    {
        $time = Carbon::parse($jamPulang);
        $variance = rand(-30, 30); // ±30 menit
        return $time->addMinutes($variance);
    }
    
    /**
     * Generate waktu izin (random dalam jam sekolah)
     */
    private function generateIzinTime($jamMasuk, $jamPulang)
    {
        $start = Carbon::parse($jamMasuk);
        $end = Carbon::parse($jamPulang);
        
        // Random waktu antara jam masuk dan jam pulang
        $diffInMinutes = $start->diffInMinutes($end);
        $randomMinutes = rand(0, $diffInMinutes);
        
        return $start->addMinutes($randomMinutes);
    }
}
