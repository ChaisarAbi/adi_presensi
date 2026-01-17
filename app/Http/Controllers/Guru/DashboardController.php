<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\ClassSchedule;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display guru dashboard
     */
    public function index()
    {
        $today = today();
        
        $stats = [
            'total_students' => Student::count(),
            'attendance_today' => Attendance::whereDate('tanggal', $today)->count(),
            'attendance_masuk' => Attendance::whereDate('tanggal', $today)
                ->where('status', 'Hadir Masuk')
                ->count(),
            'attendance_pulang' => Attendance::whereDate('tanggal', $today)
                ->where('status', 'Hadir Pulang')
                ->count(),
            'pending_permissions' => 0, // akan diisi nanti
            'active_flags' => 0, // akan diisi nanti
        ];

        // Recent scans
        $recent_scans = Attendance::with('student')
            ->whereDate('tanggal', $today)
            ->where('scanned_by', auth()->id())
            ->orderBy('waktu', 'desc')
            ->limit(10)
            ->get();

        // Students belum pulang
        $students_belum_pulang = Student::whereDoesntHave('attendances', function($query) use ($today) {
            $query->whereDate('tanggal', $today)
                  ->where('status', 'Hadir Pulang');
        })->with(['classSchedule', 'attendances' => function($query) use ($today) {
            $query->whereDate('tanggal', $today);
        }])->limit(10)->get();

        return view('guru.dashboard', compact('stats', 'recent_scans', 'students_belum_pulang'));
    }
}
