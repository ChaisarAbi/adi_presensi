<?php

namespace App\Http\Controllers\Ortu;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Attendance;
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
            'present' => [],
            'absent' => [],
            'late' => [],
            'permission' => []
        ];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($currentYear, $currentMonth, $day);
            $attendance = $attendances->firstWhere('tanggal', $date->toDateString());
            
            $monthlyData['labels'][] = $day;
            
            if ($attendance) {
                switch ($attendance->status) {
                    case 'Hadir Masuk':
                        // Check if late (after 07:00)
                        $isLate = strtotime($attendance->waktu) > strtotime('07:00:00');
                        $monthlyData['present'][] = $isLate ? 0 : 1;
                        $monthlyData['late'][] = $isLate ? 1 : 0;
                        $monthlyData['absent'][] = 0;
                        $monthlyData['permission'][] = 0;
                        break;
                    case 'Izin':
                        $monthlyData['present'][] = 0;
                        $monthlyData['late'][] = 0;
                        $monthlyData['absent'][] = 0;
                        $monthlyData['permission'][] = 1;
                        break;
                    default:
                        $monthlyData['present'][] = 0;
                        $monthlyData['late'][] = 0;
                        $monthlyData['absent'][] = 1;
                        $monthlyData['permission'][] = 0;
                }
            } else {
                $monthlyData['present'][] = 0;
                $monthlyData['late'][] = 0;
                $monthlyData['absent'][] = 1;
                $monthlyData['permission'][] = 0;
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
            'present' => array_fill(0, 7, 0),
            'absent' => array_fill(0, 7, 0),
            'late' => array_fill(0, 7, 0),
            'permission' => array_fill(0, 7, 0)
        ];

        foreach ($attendances as $attendance) {
            $dayOfWeek = Carbon::parse($attendance->tanggal)->dayOfWeekIso - 1; // 0-6
            
            switch ($attendance->status) {
                case 'Hadir Masuk':
                    $isLate = strtotime($attendance->waktu) > strtotime('07:00:00');
                    if ($isLate) {
                        $weeklyData['late'][$dayOfWeek]++;
                    } else {
                        $weeklyData['present'][$dayOfWeek]++;
                    }
                    break;
                case 'Izin':
                    $weeklyData['permission'][$dayOfWeek]++;
                    break;
                default:
                    $weeklyData['absent'][$dayOfWeek]++;
            }
        }

        return $weeklyData;
    }

    /**
     * Get status distribution for pie chart.
     */
    private function getStatusDistribution($student)
    {
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();
        
        $attendances = Attendance::where('student_id', $student->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();

        $distribution = [
            'Hadir Tepat Waktu' => 0,
            'Terlambat' => 0,
            'Izin' => 0,
            'Tidak Hadir' => 0
        ];

        foreach ($attendances as $attendance) {
            switch ($attendance->status) {
                case 'Hadir Masuk':
                    $isLate = strtotime($attendance->waktu) > strtotime('07:00:00');
                    if ($isLate) {
                        $distribution['Terlambat']++;
                    } else {
                        $distribution['Hadir Tepat Waktu']++;
                    }
                    break;
                case 'Izin':
                    $distribution['Izin']++;
                    break;
                default:
                    $distribution['Tidak Hadir']++;
            }
        }

        // Add days without any attendance as "Tidak Hadir"
        $totalDays = 30;
        $daysWithAttendance = $attendances->unique('tanggal')->count();
        $distribution['Tidak Hadir'] += ($totalDays - $daysWithAttendance);

        return $distribution;
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
            
            $presentDays = Attendance::where('student_id', $student->id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->where('status', 'Hadir Masuk')
                ->distinct('tanggal')
                ->count('tanggal');
            
            $totalDays = $startDate->diffInDays($endDate) + 1;
            $attendanceRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;
            
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
