<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class OrtuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ortus = User::where('role', 'ortu')->with('students')->paginate(10);
        return view('admin.ortus.index', compact('ortus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::whereNull('ortu_id')->get();
        return view('admin.ortus.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:15',
            'student_id' => 'required|exists:students,id',
        ]);

        // Create parent user
        $ortu = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'ortu',
            'phone' => $request->phone,
        ]);

        // Link student to parent
        $student = Student::findOrFail($request->student_id);
        $student->update(['ortu_id' => $ortu->id]);

        return redirect()->route('admin.ortus.index')
            ->with('success', 'Data orang tua berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ortu = User::where('role', 'ortu')->with('students')->findOrFail($id);
        return view('admin.ortus.show', compact('ortu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ortu = User::where('role', 'ortu')->with('students')->findOrFail($id);
        $students = Student::whereNull('ortu_id')->orWhere('ortu_id', $id)->get();
        return view('admin.ortus.edit', compact('ortu', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ortu = User::where('role', 'ortu')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($ortu->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'required|string|max:15',
            'student_id' => 'required|exists:students,id',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $ortu->update($data);

        // Update student parent relationship
        $student = Student::findOrFail($request->student_id);
        
        // Remove previous parent link if changed
        if ($student->ortu_id != $ortu->id) {
            Student::where('ortu_id', $ortu->id)->update(['ortu_id' => null]);
            $student->update(['ortu_id' => $ortu->id]);
        }

        return redirect()->route('admin.ortus.index')
            ->with('success', 'Data orang tua berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ortu = User::where('role', 'ortu')->findOrFail($id);
        
        // Remove parent link from student
        Student::where('ortu_id', $ortu->id)->update(['ortu_id' => null]);
        
        $ortu->delete();

        return redirect()->route('admin.ortus.index')
            ->with('success', 'Data orang tua berhasil dihapus.');
    }
}
