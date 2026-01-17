<?php

namespace App\Http\Controllers\Ortu;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $student = Student::where('ortu_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('ortu.dashboard')
                ->with('error', 'Anda belum terhubung dengan siswa manapun.');
        }

        // Get attendance for the last 30 days
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();
        
        $attendances = Attendance::where('student_id', $student->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu', 'desc')
            ->paginate(15);

        // Statistics
        $totalDays = 30;
        $presentDays = Attendance::where('student_id', $student->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('status', 'Hadir Masuk')
            ->distinct('tanggal')
            ->count('tanggal');
        
        $lateDays = Attendance::where('student_id', $student->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('status', 'Hadir Masuk')
            ->whereTime('waktu', '>', '07:00:00') // Assuming school starts at 07:00
            ->distinct('tanggal')
            ->count('tanggal');

        $absentDays = $totalDays - $presentDays;

        $stats = [
            'total_days' => $totalDays,
            'present_days' => $presentDays,
            'absent_days' => $absentDays,
            'late_days' => $lateDays,
            'attendance_rate' => $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0,
        ];

        // Recent attendance (last 7 days)
        $recentAttendances = Attendance::where('student_id', $student->id)
            ->whereBetween('tanggal', [Carbon::now()->subDays(7), Carbon::now()])
            ->orderBy('tanggal', 'desc')
            ->get()
            ->groupBy('tanggal');

        return view('ortu.attendance.index', compact(
            'student', 
            'attendances', 
            'stats',
            'recentAttendances'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ortu tidak bisa membuat absensi
        return redirect()->route('ortu.attendance.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Ortu tidak bisa membuat absensi
        return redirect()->route('ortu.attendance.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $student = Student::where('ortu_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('ortu.dashboard')
                ->with('error', 'Anda belum terhubung dengan siswa manapun.');
        }

        $attendance = Attendance::where('id', $id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        return view('ortu.attendance.show', compact('attendance', 'student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Ortu tidak bisa edit absensi
        return redirect()->route('ortu.attendance.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Ortu tidak bisa update absensi
        return redirect()->route('ortu.attendance.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Ortu tidak bisa menghapus absensi
        return redirect()->route('ortu.attendance.index');
    }

    /**
     * Get monthly attendance data for charts.
     */
    public function monthlyData()
    {
        $user = Auth::user();
        $student = Student::where('ortu_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $attendances = Attendance::where('student_id', $student->id)
            ->whereYear('tanggal', $currentYear)
            ->whereMonth('tanggal', $currentMonth)
            ->get();

        $daysInMonth = Carbon::now()->daysInMonth;
        $monthlyData = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($currentYear, $currentMonth, $day);
            $attendance = $attendances->firstWhere('tanggal', $date->toDateString());
            
            $monthlyData[] = [
                'day' => $day,
                'date' => $date->format('Y-m-d'),
                'status' => $attendance ? $attendance->status : 'Tidak Hadir',
                'time' => $attendance ? $attendance->waktu : null,
            ];
        }

        return response()->json($monthlyData);
    }
}
