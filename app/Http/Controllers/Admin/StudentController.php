<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentController extends Controller
{
public function index(Request $request)
{
    $branches = Branch::all();

        $students = Student::with('branch')
        ->when($request->search, fn($q) =>
            $q->where('name', 'like', "%{$request->search}%")
              ->orWhere('nis', 'like', "%{$request->search}%"))
        ->when($request->status,    fn($q) => $q->where('status',    $request->status))
        ->when($request->gender,    fn($q) => $q->where('gender',    $request->gender))
            ->when($request->branch_id, function($q) use ($request) {
                if ($request->branch_id === 'pusat') return $q->whereNull('branch_id');
                return $q->where('branch_id', $request->branch_id);
            })
        ->latest()
        ->paginate(10);

$stats = [
    'total'  => Student::count(),
    'aktif'  => Student::where('status', 'aktif')->count(),
    'male'   => Student::where('gender', 'L')->count(),
    'female' => Student::where('gender', 'P')->count(),
];
    return view('admin.students.index', compact('branches', 'students', 'stats'));
}

public function store(Request $request)
{
        // normalize 'pusat' selection to null so validation accepts it
        if ($request->input('branch_id') === 'pusat' || $request->input('branch_id') === '0') {
            $request->merge(['branch_id' => null]);
        }

        $data = $request->validate([
        'name'         => 'required|string|max:100',
        'nis'          => 'required|string|unique:students,nis',
        'gender'       => 'required|in:L,P',
        'birth_date'   => 'nullable|date',
        'birth_place'  => 'nullable|string|max:100',
            'branch_id'    => 'nullable|exists:branches,id',
        'phone'        => 'nullable|string|max:20',
        'address'      => 'nullable|string',
        'parent_name'  => 'nullable|string|max:100',
        'parent_phone' => 'nullable|string|max:20',
        'school_name'  => 'nullable|string|max:100',
        'grade'        => 'nullable|string|max:50',
        'photo'        => 'nullable|image|max:2048',
    ]);

    $data['join_date'] = now()->toDateString();

        // allow choosing 'pusat' from the UI (maps to null branch)
        if ($request->input('branch_id') === 'pusat' || $request->input('branch_id') === '0') {
            $data['branch_id'] = null;
        }

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('students', 'public');
    }

    $student = Student::create($data);

    return response()->json([
        'success' => true,
        'message' => 'Siswa berhasil ditambahkan',
        'data' => $student
    ]);
} 

    public function show(Student $student)
    {
        $student->load('branch');
        return response()->json(['success' => true, 'data' => $student]);
    }

    public function update(Request $request, Student $student)
    {
        // normalize 'pusat' selection to null so validation accepts it
        if ($request->input('branch_id') === 'pusat' || $request->input('branch_id') === '0') {
            $request->merge(['branch_id' => null]);
        }

        $request->validate([
            'name'         => 'required|string|max:100',
            'nis'          => 'required|string|unique:students,nis,' . $student->id,
            'gender'       => 'required|in:L,P',
            'birth_date'   => 'nullable|date',
            'phone'        => 'nullable|string|max:20',
            'address'      => 'nullable|string',
            'parent_name'  => 'nullable|string|max:100',
            'parent_phone' => 'nullable|string|max:20',
            'branch_id'    => 'nullable|exists:branches,id',
            'photo'        => 'nullable|image|max:2048',
        ]);

        $data = $request->except('photo');

        if ($request->input('branch_id') === 'pusat' || $request->input('branch_id') === '0') {
            $data['branch_id'] = null;
        }

        if ($request->hasFile('photo')) {
            if ($student->photo) Storage::disk('public')->delete($student->photo);
            $data['photo'] = $request->file('photo')->store('students', 'public');
        }

        $student->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil diupdate!',
        ]);
    }

    public function destroy(Student $student)
    {
        if ($student->photo) Storage::disk('public')->delete($student->photo);
        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil dihapus!',
        ]);
    }
}