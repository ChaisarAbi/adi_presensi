<?php

namespace App\Http\Controllers\Ortu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Permission;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display ortu dashboard
     */
    public function index()
    {
        $user = auth()->user();
        $today = today();
        
        // Get student(s) for this parent
        $students = $user->students;
        
        if ($students->isEmpty()) {
            return view('ortu.dashboard', [
                'stats' => [],
                'student' => null,
                'recent_attendances' => collect(),
                'permissions' => collect(),
            ]);
        }

        // For simplicity, take first student
        $student = $students->first();
        
        $stats = [
            'total_attendance' => $student->attendances()->count(),
            'attendance_this_month' => $student->attendances()
                ->whereMonth('tanggal', $today->month)
                ->whereYear('tanggal', $today->year)
                ->count(),
            'hadir_count' => $student->attendances()
                ->where('status', 'Hadir Masuk')
                ->orWhere('status', 'Hadir Pulang')
                ->count(),
            'izin_count' => $student->permissions()
                ->where('status', 'Disetujui')
                ->count(),
            'today_status' => $student->attendanceToday() ? $student->attendanceToday()->status : 'Belum Absen',
        ];

        // Recent attendance for this student
        $recent_attendances = $student->attendances()
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu', 'desc')
            ->limit(10)
            ->get();

        // Recent permissions
        $permissions = $student->permissions()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('ortu.dashboard', compact('stats', 'student', 'recent_attendances', 'permissions'));
    }
}
