<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flag;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class FlagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flags = Flag::with(['student', 'flaggedBy'])
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => Flag::count(),
            'active' => Flag::where('status', 'Aktif')->count(),
            'resolved' => Flag::where('status', 'Selesai')->count(),
        ];

        return view('admin.flags.index', compact('flags', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::orderBy('nama')->get();
        return view('admin.flags.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'keterangan' => 'required|string|max:1000',
            'status' => 'required|in:Aktif,Selesai',
            'tanggal' => 'required|date',
        ]);

        $validated['flagged_by'] = auth()->id();

        Flag::create($validated);

        return redirect()->route('admin.flags.index')
            ->with('success', 'Flag berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $flag = Flag::with(['student', 'flaggedBy'])->findOrFail($id);
        return view('admin.flags.show', compact('flag'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $flag = Flag::findOrFail($id);
        $students = Student::orderBy('nama')->get();
        return view('admin.flags.edit', compact('flag', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $flag = Flag::findOrFail($id);

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'keterangan' => 'required|string|max:1000',
            'status' => 'required|in:Aktif,Selesai',
            'tanggal' => 'required|date',
        ]);

        $flag->update($validated);

        return redirect()->route('admin.flags.index')
            ->with('success', 'Flag berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $flag = Flag::findOrFail($id);
        $flag->delete();

        return redirect()->route('admin.flags.index')
            ->with('success', 'Flag berhasil dihapus!');
    }

    /**
     * Resolve flag.
     */
    public function resolve(string $id)
    {
        $flag = Flag::findOrFail($id);
        $flag->update(['status' => 'Selesai']);

        return redirect()->route('admin.flags.index')
            ->with('success', 'Flag berhasil diselesaikan!');
    }
}
