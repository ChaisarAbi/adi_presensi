<?php

namespace App\Http\Controllers\Ortu;

use App\Http\Controllers\Controller;
use App\Models\Flag;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FlagController extends Controller
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

        $flags = Flag::where('student_id', $student->id)
            ->latest()
            ->paginate(10);

        return view('ortu.flags.index', compact('flags', 'student'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $student = Student::where('ortu_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('ortu.dashboard')
                ->with('error', 'Anda belum terhubung dengan siswa manapun.');
        }

        return view('ortu.flags.create', compact('student'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $student = Student::where('ortu_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('ortu.dashboard')
                ->with('error', 'Anda belum terhubung dengan siswa manapun.');
        }

        $validated = $request->validate([
            'keterangan' => 'required|string|max:500',
        ]);

        $validated['student_id'] = $student->id;
        $validated['tanggal'] = now();
        $validated['status'] = 'Aktif';
        $validated['flagged_by'] = $user->id;
        $validated['waktu_scan_pulang'] = null; // Not used for ortu flags
        $validated['waktu_flag'] = now();

        Flag::create($validated);

        return redirect()->route('ortu.flags.index')
            ->with('success', 'Flag berhasil dibuat! Guru akan segera menindaklanjuti.');
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

        $flag = Flag::where('id', $id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        return view('ortu.flags.show', compact('flag'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Ortu tidak bisa edit flag yang sudah dibuat
        return redirect()->route('ortu.flags.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Ortu tidak bisa update flag yang sudah dibuat
        return redirect()->route('ortu.flags.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $student = Student::where('ortu_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('ortu.dashboard')
                ->with('error', 'Anda belum terhubung dengan siswa manapun.');
        }

        $flag = Flag::where('id', $id)
            ->where('student_id', $student->id)
            ->where('status', 'Aktif')
            ->firstOrFail();

        $flag->delete();

        return redirect()->route('ortu.flags.index')
            ->with('success', 'Flag berhasil dibatalkan!');
    }

    /**
     * Create flag for specific student.
     */
    public function createFlag($student_id)
    {
        $user = Auth::user();
        $student = Student::where('id', $student_id)
            ->where('ortu_id', $user->id)
            ->firstOrFail();

        return view('ortu.flags.create', compact('student'));
    }
}
