<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Flag;
use Illuminate\Http\Request;

class FlagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flags = Flag::with(['student', 'flaggedBy'])
            ->where('status', 'Aktif')
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => Flag::count(),
            'active' => Flag::where('status', 'Aktif')->count(),
            'resolved' => Flag::where('status', 'Selesai')->count(),
        ];

        return view('guru.flags.index', compact('flags', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Guru tidak bisa membuat flag baru, hanya bisa resolve
        return redirect()->route('guru.flags.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Guru tidak bisa membuat flag baru, hanya bisa resolve
        return redirect()->route('guru.flags.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $flag = Flag::with(['student', 'flaggedBy'])->findOrFail($id);
        return view('guru.flags.show', compact('flag'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Guru tidak bisa edit flag, hanya bisa resolve
        return redirect()->route('guru.flags.show', $id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $flag = Flag::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:Aktif,Selesai',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $flag->update($validated);

        return redirect()->route('guru.flags.index')
            ->with('success', 'Status flag berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Guru tidak bisa menghapus flag
        return redirect()->route('guru.flags.index');
    }

    /**
     * Resolve flag.
     */
    public function resolve(string $id)
    {
        $flag = Flag::findOrFail($id);
        $flag->update(['status' => 'Selesai']);

        return redirect()->route('guru.flags.index')
            ->with('success', 'Flag berhasil diselesaikan!');
    }
}
