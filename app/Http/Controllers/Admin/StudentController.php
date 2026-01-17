<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\ClassSchedule;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with(['classSchedule', 'ortu'])->latest()->paginate(10);
        return view('admin.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = ClassSchedule::all();
        $ortu = User::where('role', 'ortu')->get();
        return view('admin.students.create', compact('classes', 'ortu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|unique:students,nis',
            'class_schedule_id' => 'required|exists:class_schedules,id',
            'ortu_id' => 'nullable|exists:users,id',
        ]);

        $student = Student::create([
            'nama' => $request->nama,
            'nis' => $request->nis,
            'class_schedule_id' => $request->class_schedule_id,
            'ortu_id' => $request->ortu_id,
            'barcode' => Str::random(10),
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = Student::with(['classSchedule', 'ortu', 'attendances'])->findOrFail($id);
        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = Student::findOrFail($id);
        $classes = ClassSchedule::all();
        $ortu = User::where('role', 'ortu')->get();
        return view('admin.students.edit', compact('student', 'classes', 'ortu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => ['required', 'string', Rule::unique('students')->ignore($student->id)],
            'class_schedule_id' => 'required|exists:class_schedules,id',
            'ortu_id' => 'nullable|exists:users,id',
        ]);

        $student->update([
            'nama' => $request->nama,
            'nis' => $request->nis,
            'class_schedule_id' => $request->class_schedule_id,
            'ortu_id' => $request->ortu_id,
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    /**
     * Generate new barcode for student
     */
    public function generateBarcode(string $id)
    {
        $student = Student::findOrFail($id);
        $student->update([
            'barcode' => Str::random(10),
        ]);

        return redirect()->route('admin.students.show', $student->id)
            ->with('success', 'Barcode berhasil digenerate ulang.');
    }
}
