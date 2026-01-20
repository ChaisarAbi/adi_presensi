<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Walikelas;
use App\Models\Holiday;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display form for generating reports
     */
    public function index()
    {
        $kelasList = Student::select('kelas')->distinct()->orderBy('kelas')->get();
        $waliKelasList = Walikelas::all();
        
        return view('admin.reports.index', compact('kelasList', 'waliKelasList'));
    }

    /**
     * Generate PDF report
     */
    public function generatePDF(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2030',
            'kelas' => 'required|string',
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $kelas = $request->kelas;

        // Get students in the selected class
        $students = Student::where('kelas', $kelas)
            ->orderBy('nama')
            ->get();

        // Get wali kelas
        $waliKelas = Walikelas::where('kelas', $kelas)->first();

            // Calculate attendance for each student
            $reportData = [];
            foreach ($students as $student) {
                // Get attendance for the selected month
                $attendances = Attendance::where('student_id', $student->id)
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->get();

                // Count attendance types
                $izin = $attendances->where('status', 'Izin')->count();
                $tidakHadir = $attendances->where('status', 'Tidak Hadir')->count();
                
                // Calculate present days - UNIQUE DAYS (hitung hari unik untuk Hadir Masuk/Hadir Pulang)
                $hadirDates = $attendances
                    ->whereIn('status', ['Hadir Masuk', 'Hadir Pulang'])
                    ->pluck('tanggal')
                    ->unique()
                    ->count();
                
                $hadirDays = $hadirDates;

                $reportData[] = [
                    'student' => $student,
                    'hadir' => $hadirDays,
                    'izin' => $izin,
                    'tidak_hadir' => $tidakHadir,
                ];
            }

        // Month name in Indonesian
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $monthName = $monthNames[$bulan];

        $data = [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'kelas' => $kelas,
            'monthName' => $monthName,
            'waliKelas' => $waliKelas,
            'reportData' => $reportData,
            'generatedDate' => Carbon::now()->format('d F Y H:i:s'),
        ];

        $pdf = Pdf::loadView('admin.reports.pdf', $data);
        
        // Set paper size and orientation
        $pdf->setPaper('A4', 'landscape');
        
        // Download PDF
        return $pdf->download("Laporan-Absensi-Kelas-{$kelas}-{$monthName}-{$tahun}.pdf");
    }

    /**
     * Preview PDF report
     */
    public function previewPDF(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2030',
            'kelas' => 'required|string',
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $kelas = $request->kelas;

        // Get students in the selected class
        $students = Student::where('kelas', $kelas)
            ->orderBy('nama')
            ->get();

        // Get wali kelas
        $waliKelas = Walikelas::where('kelas', $kelas)->first();

        // Calculate attendance for each student
        $reportData = [];
        foreach ($students as $student) {
            // Get attendance for the selected month
            $attendances = Attendance::where('student_id', $student->id)
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->get();

                // Count attendance types
                $izin = $attendances->where('status', 'Izin')->count();
                $tidakHadir = $attendances->where('status', 'Tidak Hadir')->count();
                
                // Calculate present days - UNIQUE DAYS (hitung hari unik untuk Hadir Masuk/Hadir Pulang)
                $hadirDates = $attendances
                    ->whereIn('status', ['Hadir Masuk', 'Hadir Pulang'])
                    ->pluck('tanggal')
                    ->unique()
                    ->count();
                
                $hadirDays = $hadirDates;

            $reportData[] = [
                'student' => $student,
                'hadir' => $hadirDays,
                'izin' => $izin,
                'tidak_hadir' => $tidakHadir,
            ];
        }

        // Month name in Indonesian
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $monthName = $monthNames[$bulan];

        $data = [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'kelas' => $kelas,
            'monthName' => $monthName,
            'waliKelas' => $waliKelas,
            'reportData' => $reportData,
            'generatedDate' => Carbon::now()->format('d F Y H:i:s'),
        ];

        return view('admin.reports.pdf', $data);
    }
}
