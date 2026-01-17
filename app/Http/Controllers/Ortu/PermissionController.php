<?php

namespace App\Http\Controllers\Ortu;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PermissionController extends Controller
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

        $permissions = Permission::where('student_id', $student->id)
            ->latest()
            ->paginate(10);

        return view('ortu.permissions.index', compact('permissions', 'student'));
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

        return view('ortu.permissions.create', compact('student'));
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
            'tanggal' => 'required|date',
            'alasan' => 'required|string|max:500',
            'foto_bukti' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload foto bukti
        if ($request->hasFile('foto_bukti')) {
            $path = $request->file('foto_bukti')->store('permissions', 'public');
            $validated['foto_bukti'] = $path;
        }

        $validated['student_id'] = $student->id;
        $validated['status'] = 'Pending';

        Permission::create($validated);

        return redirect()->route('ortu.permissions.index')
            ->with('success', 'Izin berhasil diajukan! Menunggu verifikasi guru.');
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

        $permission = Permission::where('id', $id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        return view('ortu.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Ortu tidak bisa edit izin yang sudah diajukan
        return redirect()->route('ortu.permissions.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Ortu tidak bisa update izin yang sudah diajukan
        return redirect()->route('ortu.permissions.index');
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

        $permission = Permission::where('id', $id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        // Hapus foto jika ada
        if ($permission->foto_bukti) {
            Storage::disk('public')->delete($permission->foto_bukti);
        }

        $permission->delete();

        return redirect()->route('ortu.permissions.index')
            ->with('success', 'Izin berhasil dibatalkan!');
    }

    /**
     * Create permission with student_id parameter.
     */
    public function createWithStudent($student_id = null)
    {
        $user = Auth::user();
        
        if ($student_id) {
            $student = Student::where('id', $student_id)
                ->where('ortu_id', $user->id)
                ->firstOrFail();
        } else {
            $student = Student::where('ortu_id', $user->id)->first();
        }
        
        if (!$student) {
            return redirect()->route('ortu.dashboard')
                ->with('error', 'Anda belum terhubung dengan siswa manapun.');
        }

        return view('ortu.permissions.create', compact('student'));
    }
}
