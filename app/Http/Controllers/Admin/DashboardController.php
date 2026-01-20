<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\ClassSchedule;
use App\Models\Attendance;
use App\Models\Holiday;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        $stats = [
            'total_students' => Student::count(),
            'total_guru' => User::where('role', 'guru')->count(),
            'total_ortu' => User::where('role', 'ortu')->count(),
            'total_classes' => ClassSchedule::count(),
            'attendance_today' => Attendance::whereDate('tanggal', today())->count(),
            'pending_permissions' => 0, // akan diisi nanti
            'active_flags' => 0, // akan diisi nanti
        ];

        // Recent attendance
        $recent_attendances = Attendance::with('student')
            ->whereDate('tanggal', today())
            ->orderBy('waktu', 'desc')
            ->limit(10)
            ->get();

        // Class distribution
        $class_distribution = Student::with('classSchedule')
            ->selectRaw('class_schedule_id, count(*) as total')
            ->groupBy('class_schedule_id')
            ->get();

        // Check if today is a holiday
        $today = Carbon::today()->toDateString();
        $isHoliday = Holiday::where('tanggal', $today)->first();
        $holidayInfo = $isHoliday ? [
            'is_holiday' => true,
            'keterangan' => $isHoliday->keterangan
        ] : [
            'is_holiday' => false,
            'keterangan' => null
        ];

        return view('admin.dashboard', compact('stats', 'recent_attendances', 'class_distribution', 'holidayInfo'));
    }
}
