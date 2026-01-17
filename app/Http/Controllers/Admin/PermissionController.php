<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::with('student')
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => Permission::count(),
            'approved' => Permission::where('status', 'Disetujui')->count(),
            'pending' => Permission::where('status', 'Pending')->count(),
            'rejected' => Permission::where('status', 'Ditolak')->count(),
        ];

        return view('admin.permissions.index', compact('permissions', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::orderBy('nama')->get();
        return view('admin.permissions.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'alasan' => 'required|string|max:1000',
            'foto_bukti' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:Pending,Disetujui,Ditolak',
            'keterangan' => 'nullable|string|max:500',
            'tanggal' => 'required|date',
        ]);

        // Upload foto bukti
        if ($request->hasFile('foto_bukti')) {
            $path = $request->file('foto_bukti')->store('permissions', 'public');
            $validated['foto_bukti'] = $path;
        }

        Permission::create($validated);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Izin berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $permission = Permission::with('student')->findOrFail($id);
        return view('admin.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $permission = Permission::findOrFail($id);
        $students = Student::orderBy('nama')->get();
        return view('admin.permissions.edit', compact('permission', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $permission = Permission::findOrFail($id);

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'alasan' => 'required|string|max:1000',
            'foto_bukti' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:Pending,Disetujui,Ditolak',
            'keterangan' => 'nullable|string|max:500',
            'tanggal' => 'required|date',
        ]);

        // Update foto bukti jika ada
        if ($request->hasFile('foto_bukti')) {
            // Hapus foto lama
            if ($permission->foto_bukti) {
                Storage::disk('public')->delete($permission->foto_bukti);
            }
            
            $path = $request->file('foto_bukti')->store('permissions', 'public');
            $validated['foto_bukti'] = $path;
        } else {
            unset($validated['foto_bukti']);
        }

        $permission->update($validated);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Izin berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::findOrFail($id);

        // Hapus foto bukti
        if ($permission->foto_bukti) {
            Storage::disk('public')->delete($permission->foto_bukti);
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Izin berhasil dihapus!');
    }

    /**
     * Approve permission.
     */
    public function approve(string $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->update(['status' => 'Disetujui']);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Izin berhasil disetujui!');
    }

    /**
     * Reject permission.
     */
    public function reject(string $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->update(['status' => 'Ditolak']);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Izin berhasil ditolak!');
    }
}
