<?php

namespace App\Http\Controllers\Ortu;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ChartController extends Controller
{
    /**
     * Display attendance charts.
     */
    public function index()
    {
        $user = Auth::user();
        $student = Student::where('ortu_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('ortu.dashboard')
                ->with('error', 'Anda belum terhubung dengan siswa manapun.');
        }

        // Get data for charts
        $monthlyData = $this->getMonthlyAttendanceData($student);
        $weeklyData = $this->getWeeklyAttendanceData($student);
        $statusDistribution = $this->getStatusDistribution($student);
        $attendanceTrend = $this->getAttendanceTrend($student);

        return view('ortu.charts.index', compact(
            'student',
            'monthlyData',
            'weeklyData',
            'statusDistribution',
            'attendanceTrend'
        ));
    }

    /**
     * Get monthly attendance data.
     */
    private function getMonthlyAttendanceData($student)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $attendances = Attendance::where('student_id', $student->id)
            ->whereYear('tanggal', $currentYear)
            ->whereMonth('tanggal', $currentMonth)
            ->get();

        $daysInMonth = Carbon::now()->daysInMonth;
        $monthlyData = [
            'labels' => [],
            'hadir' => [],
            'izin' => [],
            'tidak_hadir' => []
        ];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($currentYear, $currentMonth, $day);
            $attendance = $attendances->firstWhere('tanggal', $date->toDateString());
            
            $monthlyData['labels'][] = $day;
            
            if ($attendance) {
                switch ($attendance->status) {
                    case 'Hadir Masuk':
                    case 'Hadir Pulang':
                        $monthlyData['hadir'][] = 1;
                        $monthlyData['izin'][] = 0;
                        $monthlyData['tidak_hadir'][] = 0;
                        break;
                    case 'Izin':
                        $monthlyData['hadir'][] = 0;
                        $monthlyData['izin'][] = 1;
                        $monthlyData['tidak_hadir'][] = 0;
                        break;
                    case 'Tidak Hadir':
                        $monthlyData['hadir'][] = 0;
                        $monthlyData['izin'][] = 0;
                        $monthlyData['tidak_hadir'][] = 1;
                        break;
                    default:
                        $monthlyData['hadir'][] = 0;
                        $monthlyData['izin'][] = 0;
                        $monthlyData['tidak_hadir'][] = 0;
                }
            } else {
                // Hari tanpa record tidak dihitung sebagai tidak hadir
                $monthlyData['hadir'][] = 0;
                $monthlyData['izin'][] = 0;
                $monthlyData['tidak_hadir'][] = 0;
            }
        }

        return $monthlyData;
    }

    /**
     * Get weekly attendance data.
     */
    private function getWeeklyAttendanceData($student)
    {
        $startDate = Carbon::now()->startOfWeek();
        $endDate = Carbon::now()->endOfWeek();
        
        $attendances = Attendance::where('student_id', $student->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();

        $daysOfWeek = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $weeklyData = [
            'labels' => $daysOfWeek,
            'hadir' => array_fill(0, 7, 0),
            'izin' => array_fill(0, 7, 0),
            'tidak_hadir' => array_fill(0, 7, 0)
        ];

        foreach ($attendances as $attendance) {
            $dayOfWeek = Carbon::parse($attendance->tanggal)->dayOfWeekIso - 1; // 0-6
            
            switch ($attendance->status) {
                case 'Hadir Masuk':
                case 'Hadir Pulang':
                    $weeklyData['hadir'][$dayOfWeek]++;
                    break;
                case 'Izin':
                    $weeklyData['izin'][$dayOfWeek]++;
                    break;
                case 'Tidak Hadir':
                    $weeklyData['tidak_hadir'][$dayOfWeek]++;
                    break;
            }
        }

        return $weeklyData;
    }

    /**
     * Get status distribution for pie chart (Semester ini).
     */
    private function getStatusDistribution($student)
    {
        // Semester ini: 6 bulan terakhir
        $startDate = Carbon::now()->subMonths(6)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        $attendances = Attendance::where('student_id', $student->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal')
            ->get();

        $distribution = [
            'Hadir' => 0,
            'Izin' => 0,
            'Tidak Hadir' => 0
        ];

        // Group by tanggal (hari unik)
        $attendanceByDate = $attendances->groupBy('tanggal');

        foreach ($attendanceByDate as $date => $dayAttendances) {
            // Tentukan status hari berdasarkan record yang ada
            $dayStatus = $this->determineDayStatus($dayAttendances);
            
            if ($dayStatus && isset($distribution[$dayStatus])) {
                $distribution[$dayStatus]++;
            }
        }

        return $distribution;
    }

    /**
     * Determine the status of a day based on attendance records.
     */
    private function determineDayStatus($dayAttendances)
    {
        // Priority: Tidak Hadir > Izin > Hadir
        // Jika ada record "Tidak Hadir", hari itu dianggap "Tidak Hadir"
        // Jika ada record "Izin" (dan tidak ada "Tidak Hadir"), hari itu dianggap "Izin"
        // Jika ada record "Hadir Masuk" atau "Hadir Pulang" (dan tidak ada "Tidak Hadir" atau "Izin"), hari itu dianggap "Hadir"
        
        $hasTidakHadir = false;
        $hasIzin = false;
        $hasHadir = false;

        foreach ($dayAttendances as $attendance) {
            switch ($attendance->status) {
                case 'Tidak Hadir':
                    $hasTidakHadir = true;
                    break;
                case 'Izin':
                    $hasIzin = true;
                    break;
                case 'Hadir Masuk':
                case 'Hadir Pulang':
                    $hasHadir = true;
                    break;
            }
        }

        if ($hasTidakHadir) {
            return 'Tidak Hadir';
        } elseif ($hasIzin) {
            return 'Izin';
        } elseif ($hasHadir) {
            return 'Hadir';
        }

        return null; // Tidak ada record yang valid
    }

    /**
     * Get attendance trend for line chart.
     */
    private function getAttendanceTrend($student)
    {
        $months = [];
        $attendanceRates = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->locale('id')->monthName;
            $months[] = $monthName;
            
            $startDate = $date->copy()->startOfMonth();
            $endDate = $date->copy()->endOfMonth();
            
            // Hitung hari dengan status Hadir (Masuk atau Pulang)
            $presentDays = Attendance::where('student_id', $student->id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->whereIn('status', ['Hadir Masuk', 'Hadir Pulang'])
                ->distinct('tanggal')
                ->count('tanggal');
            
            // Hitung hari sekolah aktif (Senin-Jumat, exclude libur)
            $totalSchoolDays = Holiday::countSchoolDays($startDate, $endDate);
            $attendanceRate = $totalSchoolDays > 0 ? round(($presentDays / $totalSchoolDays) * 100, 1) : 0;
            
            $attendanceRates[] = $attendanceRate;
        }

        return [
            'labels' => $months,
            'data' => $attendanceRates
        ];
    }

    /**
     * Get JSON data for AJAX requests.
     */
    public function getChartData(Request $request)
    {
        $user = Auth::user();
        $student = Student::where('ortu_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $chartType = $request->input('type', 'monthly');

        switch ($chartType) {
            case 'monthly':
                $data = $this->getMonthlyAttendanceData($student);
                break;
            case 'weekly':
                $data = $this->getWeeklyAttendanceData($student);
                break;
            case 'distribution':
                $data = $this->getStatusDistribution($student);
                break;
            case 'trend':
                $data = $this->getAttendanceTrend($student);
                break;
            default:
                $data = $this->getMonthlyAttendanceData($student);
        }

        return response()->json($data);
    }
}
