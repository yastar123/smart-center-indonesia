<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Branch;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $teachers = Teacher::with(['branch', 'user', 'courses'])
                ->when($request->search, fn($q) =>
                    $q->where('name', 'like', "%{$request->search}%")
                      ->orWhere('nig',  'like', "%{$request->search}%"))
                ->when($request->status,    fn($q) => $q->where('status',    $request->status))
                ->when($request->branch_id, function($q) use ($request) {
                    if ($request->branch_id === 'pusat') return $q->whereNull('branch_id');
                    return $q->where('branch_id', $request->branch_id);
                })
                ->latest()
                ->paginate(10);

            // Global stats — unfiltered, always show totals across all branches
            $stats = [
                'total'  => Teacher::count(),
                'aktif'  => Teacher::where('status', 'aktif')->count(),
                'male'   => Teacher::where('gender', 'L')->count(),
                'female' => Teacher::where('gender', 'P')->count(),
            ];

            return response()->json(array_merge($teachers->toArray(), ['stats' => $stats]));
        }

        $branches = Branch::all();
        $courses = Course::where('status', 'aktif')->orderBy('nama')->get();
        return view('admin.teachers.index', compact('branches', 'courses'));
    }

    public function create()
    {
        $branches = Branch::all();
        $courses = Course::where('status', 'aktif')->orderBy('nama')->get();

        return view('admin.teachers.create', compact('branches', 'courses'));
    }

    public function edit(Teacher $teacher)
    {
        $teacher->load(['branch', 'user', 'courses']);
        $branches = Branch::all();
        $courses = Course::where('status', 'aktif')->orderBy('nama')->get();

        return view('admin.teachers.edit', compact('teacher', 'branches', 'courses'));
    }

    public function store(Request $request)
    {
        // normalize 'pusat' selection
        if ($request->input('branch_id') === 'pusat' || $request->input('branch_id') === '0') {
            $request->merge(['branch_id' => null]);
        }

        $request->validate([
            'name'       => 'required|string|max:100',
            'nig'        => 'required|string|unique:teachers,nig',
            'gender'     => 'required|in:L,P',
            'birth_date' => 'nullable|date',
            'phone'      => 'nullable|string|max:20',
            'email'      => ['required', 'email', 'unique:teachers,email', 'unique:users,email'],
            'password'   => 'required|string|min:8',
            'branch_id'  => 'nullable|exists:branches,id',
            'education'  => 'nullable|string|max:50',
            'subjects'   => 'nullable|string',
            'photo'      => 'nullable|image|max:2048',
            'cv'         => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $teacher = DB::transaction(function () use ($request) {
            $rawSubjects = is_array($request->input('subjects'))
                ? $request->input('subjects')
                : preg_split('/[\n,]+/', (string) $request->input('subjects', ''));
            $subjects = collect($rawSubjects)
                ->map(fn ($value) => trim((string) $value))
                ->filter()
                ->values()
                ->all();

            $data = $request->except(['photo', 'cv', 'password', 'subjects']);
            $data['join_date'] = now()->toDateString();
            $data['status'] = 'aktif';
            $data['subjects'] = $subjects;

            if ($request->hasFile('photo')) {
                $data['photo'] = $request->file('photo')->store('teachers', 'public');
            }

            if ($request->hasFile('cv')) {
                $data['cv_path'] = $request->file('cv')->store('teachers/cv', 'public');
            }

            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'phone'     => $request->phone,
                'branch_id' => $request->branch_id,
                'is_active' => true,
            ]);

            if (method_exists($user, 'assignRole')) {
                $user->assignRole('guru');
            }

            $data['user_id'] = $user->id;

            $teacher = Teacher::create($data);

            return $teacher;
        });

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Guru dan akun login berhasil ditambahkan!',
                'data' => $teacher,
            ]);
        }

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Guru dan akun login berhasil ditambahkan!');
    }

    public function show(Request $request, Teacher $teacher)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'data' => $teacher->load(['branch', 'user', 'courses'])]);
        }
        $teacher->load(['branch', 'user', 'courses']);
        $packages = \App\Models\Package::where('guru_id', $teacher->id)
            ->with('mataPelajaran')
            ->orderBy('nama')
            ->get();
        return view('admin.teachers.show', compact('teacher', 'packages'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        // normalize 'pusat' selection
        if ($request->input('branch_id') === 'pusat' || $request->input('branch_id') === '0') {
            $request->merge(['branch_id' => null]);
        }

        $request->validate([
            'name'       => 'required|string|max:100',
            'nig'        => 'required|string|unique:teachers,nig,' . $teacher->id,
            'gender'     => 'required|in:L,P',
            'birth_date' => 'nullable|date',
            'branch_id'  => 'nullable|exists:branches,id',
            'email'      => [
                'nullable',
                'email',
                Rule::unique('teachers', 'email')->ignore($teacher->id),
                Rule::unique('users', 'email')->ignore($teacher->user_id),
            ],
            'password'    => 'nullable|string|min:8',
            'course_ids'  => 'nullable|array',
            'course_ids.*'=> 'exists:courses,id',
            'photo'       => 'nullable|image|max:2048',
            'cv'          => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        DB::transaction(function () use ($request, $teacher) {
            // Sync mata pelajaran via pivot table
            $courseIds = array_filter(array_map('intval', $request->input('course_ids', [])));
            $teacher->courses()->sync($courseIds);

            // Keep subjects JSON in sync (store course names for backward compat)
            $courseNames = Course::whereIn('id', $courseIds)->pluck('nama')->toArray();

            $data = $request->except(['photo', 'cv', 'password', 'course_ids', 'subjects']);
            $data['subjects'] = $courseNames;

            if ($request->hasFile('photo')) {
                if ($teacher->photo) Storage::disk('public')->delete($teacher->photo);
                $data['photo'] = $request->file('photo')->store('teachers', 'public');
            }

            if ($request->hasFile('cv')) {
                if ($teacher->cv_path) Storage::disk('public')->delete($teacher->cv_path);
                $data['cv_path'] = $request->file('cv')->store('teachers/cv', 'public');
            }

            $teacher->update($data);

            $userData = [
                'name'      => $request->name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'branch_id' => $request->branch_id,
                'is_active' => $request->input('status', $teacher->status) === 'aktif',
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            if ($teacher->user) {
                $teacher->user->update($userData);
            } elseif ($request->filled('email') && $request->filled('password')) {
                $user = User::create($userData);
                if (method_exists($user, 'assignRole')) {
                    $user->assignRole('guru');
                }
                $teacher->update(['user_id' => $user->id]);
            }
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru dan akun login berhasil diupdate!');
    }

    public function destroy(Teacher $teacher)
    {
        if ($teacher->photo) Storage::disk('public')->delete($teacher->photo);
        if ($teacher->cv_path) Storage::disk('public')->delete($teacher->cv_path);
        $teacher->courses()->detach();
        if ($teacher->user) $teacher->user->delete();
        $teacher->delete();
        return response()->json(['success' => true, 'message' => 'Guru dan akun login berhasil dihapus!']);
    }
}
