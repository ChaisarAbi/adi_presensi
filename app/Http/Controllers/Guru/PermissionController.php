<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::with(['student'])
            ->where('status', 'Pending')
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => Permission::count(),
            'pending' => Permission::where('status', 'Pending')->count(),
            'approved' => Permission::where('status', 'Disetujui')->count(),
            'rejected' => Permission::where('status', 'Ditolak')->count(),
        ];

        return view('guru.permissions.index', compact('permissions', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Guru tidak bisa membuat izin, hanya bisa verifikasi
        return redirect()->route('guru.permissions.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Guru tidak bisa membuat izin, hanya bisa verifikasi
        return redirect()->route('guru.permissions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $permission = Permission::with(['student'])->findOrFail($id);
        return view('guru.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Guru tidak bisa edit izin, hanya bisa approve/reject
        return redirect()->route('guru.permissions.show', $id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $permission = Permission::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $permission->update($validated);

        return redirect()->route('guru.permissions.index')
            ->with('success', 'Status izin berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Guru tidak bisa menghapus izin
        return redirect()->route('guru.permissions.index');
    }

    /**
     * Approve permission.
     */
    public function approve(string $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->update(['status' => 'Disetujui']);

        return redirect()->route('guru.permissions.index')
            ->with('success', 'Izin berhasil disetujui!');
    }

    /**
     * Reject permission.
     */
    public function reject(string $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->update(['status' => 'Ditolak']);

        return redirect()->route('guru.permissions.index')
            ->with('success', 'Izin berhasil ditolak!');
    }
}
