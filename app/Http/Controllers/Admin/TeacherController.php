<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $teachers = Teacher::with('branch')
                ->when($request->search, fn($q) =>
                    $q->where('name', 'like', "%{$request->search}%")
                      ->orWhere('nig', 'like', "%{$request->search}%"))
                ->when($request->status, fn($q) =>
                    $q->where('status', $request->status))
                ->latest()->paginate(10);

            return response()->json($teachers);
        }

       $branches = Branch::all();
        return view('admin.teachers.index', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'nig'        => 'required|string|unique:teachers,nig',
            'gender'     => 'required|in:L,P',
            'birth_date' => 'required|date',
            'phone'      => 'nullable|string|max:20',
            'email'      => 'nullable|email|unique:teachers,email',
            'branch_id'  => 'required|exists:branches,id',
            'education'  => 'nullable|string|max:50',
            'subjects'   => 'nullable|string',
            'photo'      => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        $data['join_date'] = now()->toDateString();
        $data['status'] = 'aktif';

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        $teacher = Teacher::create($data);

        return response()->json(['success' => true, 'message' => 'Guru berhasil ditambahkan!', 'data' => $teacher]);
    }

    public function show(Teacher $teacher)
    {
        return response()->json(['success' => true, 'data' => $teacher->load('branch')]);
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'nig'       => 'required|string|unique:teachers,nig,' . $teacher->id,
            'gender'    => 'required|in:L,P',
            'branch_id' => 'required|exists:branches,id',
            'photo'     => 'nullable|image|max:2048',
        ]);

        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            if ($teacher->photo) Storage::disk('public')->delete($teacher->photo);
            $data['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        $teacher->update($data);

        return response()->json(['success' => true, 'message' => 'Data guru berhasil diupdate!']);
    }

    public function destroy(Teacher $teacher)
    {
        if ($teacher->photo) Storage::disk('public')->delete($teacher->photo);
        $teacher->delete();
        return response()->json(['success' => true, 'message' => 'Guru berhasil dihapus!']);
    }
}