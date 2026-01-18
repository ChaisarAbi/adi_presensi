<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\ClassSchedule;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display scanner page for attendance
     */
    public function index()
    {
        return view('guru.attendance.scanner');
    }

    /**
     * Process scanned barcode
     */
    public function scan(Request $request)
    {
        try {
            $request->validate([
                'barcode' => 'required|string|exists:students,barcode',
            ]);

            $student = Student::where('barcode', $request->barcode)->first();
            $now = Carbon::now();
            $today = $now->toDateString();
            $currentTime = $now->toTimeString();

            // Check if student already has attendance for today
            $existingAttendance = Attendance::where('student_id', $student->id)
                ->whereDate('tanggal', $today)
                ->first();

            $classSchedule = $student->classSchedule;
            if (!$classSchedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa belum memiliki jadwal kelas. Silakan hubungi admin.',
                ], 400);
            }

            $jamMasuk = Carbon::parse($classSchedule->jam_masuk);
            $jamPulang = Carbon::parse($classSchedule->jam_pulang);

            // Determine attendance type
            if ($existingAttendance) {
                // Already has attendance for today
                if ($existingAttendance->status === 'Hadir Masuk') {
                    // Check if it's time for pulang
                    if ($now->greaterThanOrEqualTo($jamPulang)) {
                        // Update to Hadir Pulang
                        $existingAttendance->update([
                            'status' => 'Hadir Pulang',
                            'waktu' => $currentTime,
                        ]);
                        
                        return response()->json([
                            'success' => true,
                            'message' => 'Absensi pulang berhasil untuk ' . $student->nama,
                            'data' => [
                                'student' => $student,
                                'attendance' => $existingAttendance,
                                'type' => 'pulang'
                            ]
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Belum waktunya pulang. Jam pulang: ' . $classSchedule->jam_pulang,
                        ], 400);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Siswa sudah melakukan absensi hari ini dengan status: ' . $existingAttendance->status,
                    ], 400);
                }
            } else {
                // First attendance of the day
                $status = 'Hadir Masuk';
                $isLate = $now->greaterThan($jamMasuk->addMinutes(15)); // 15 minutes tolerance

                // Tetap simpan sebagai Hadir Masuk meskipun terlambat
                // Tidak ada status "Terlambat" dalam ENUM

                $attendance = Attendance::create([
                    'student_id' => $student->id,
                    'tanggal' => $today,
                    'waktu' => $currentTime,
                    'status' => $status,
                    'scanned_by' => auth()->id(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Absensi masuk berhasil untuk ' . $student->nama . ($isLate ? ' (Terlambat)' : ''),
                    'data' => [
                        'student' => $student,
                        'attendance' => $attendance,
                        'type' => 'masuk',
                        'is_late' => $isLate
                    ]
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Attendance scan error: ' . $e->getMessage(), [
                'exception' => $e,
                'barcode' => $request->barcode ?? 'null',
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show today's attendance list
     */
    public function today()
    {
        $today = Carbon::today()->toDateString();
        $attendances = Attendance::with('student.classSchedule')
            ->whereDate('tanggal', $today)
            ->orderBy('waktu', 'desc')
            ->paginate(20);

        // Get all students
        $allStudents = Student::with('classSchedule')->get();
        
        // Get student IDs who have attendance today (any status)
        $attendedStudentIds = Attendance::whereDate('tanggal', $today)
            ->pluck('student_id')
            ->toArray();
        
        // Get students who haven't been scanned at all today
        $notScannedStudents = $allStudents->whereNotIn('id', $attendedStudentIds);
        
        // Get students with approved sick permissions for today
        $sickPermissions = \App\Models\Permission::whereDate('created_at', $today)
            ->where('status', 'Disetujui')
            ->with('student')
            ->get();
        
        $sickStudentIds = $sickPermissions->pluck('student_id')->toArray();
        $sickStudents = $allStudents->whereIn('id', $sickStudentIds);

        return view('guru.attendance.today', compact(
            'attendances', 
            'notScannedStudents',
            'sickStudents'
        ));
    }

    /**
     * Show students who haven't left yet
     */
    public function belumPulang()
    {
        $today = Carbon::today()->toDateString();
        
        // Get students who had "Hadir Masuk" today but not "Hadir Pulang"
        $masukToday = Attendance::whereDate('tanggal', $today)
            ->where('status', 'Hadir Masuk')
            ->pluck('student_id');
            
        $pulangToday = Attendance::whereDate('tanggal', $today)
            ->where('status', 'Hadir Pulang')
            ->pluck('student_id');
            
        $belumPulangIds = $masukToday->diff($pulangToday);
        
        $students = Student::with(['classSchedule', 'attendances' => function($query) use ($today) {
                $query->whereDate('tanggal', $today);
            }])
            ->whereIn('id', $belumPulangIds)
            ->get();

        return view('guru.attendance.belum-pulang', compact('students'));
    }

    /**
     * Manual attendance entry
     */
    public function manual()
    {
        $students = Student::with('classSchedule')->get();
        return view('guru.attendance.manual', compact('students'));
    }

    /**
     * Store manual attendance
     */
    public function storeManual(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:masuk,pulang',
            'waktu' => 'required|date_format:H:i',
        ]);

        $student = Student::findOrFail($request->student_id);
        $today = Carbon::today()->toDateString();
        $status = $request->type === 'masuk' ? 'Hadir Masuk' : 'Hadir Pulang';

        // Check for existing attendance
        $existing = Attendance::where('student_id', $student->id)
            ->whereDate('tanggal', $today)
            ->where('status', $status)
            ->first();

        if ($existing) {
            return back()->with('error', 'Siswa sudah melakukan absensi ' . $request->type . ' hari ini.');
        }

        Attendance::create([
            'student_id' => $student->id,
            'tanggal' => $today,
            'waktu' => $request->waktu,
            'status' => $status,
            'scanned_by' => auth()->id(),
        ]);

        return back()->with('success', 'Absensi ' . $request->type . ' berhasil untuk ' . $student->nama);
    }
}
