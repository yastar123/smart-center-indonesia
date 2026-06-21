<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Branch;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
public function index(Request $request)
{
    $branches = Branch::all();
    $teachers = Teacher::with('courses')->where('status', 'aktif')->orderBy('name')->get();

        $students = Student::with([
                'branch',
                'user',
                'teachers.courses',
                'package.cabang',
                'package.guru'
            ])
        ->when($request->search, fn($q) =>
            $q->where('name', 'like', "%{$request->search}%")
              ->orWhere('nis', 'like', "%{$request->search}%"))
        ->when($request->kategori_peserta_didik, fn($q) =>
            $q->where('kategori_peserta_didik', $request->kategori_peserta_didik))
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
    return view('admin.students.index', compact('branches', 'teachers', 'students', 'stats'));
}

public function create()
{
    $branches = Branch::all();
    return view('admin.students.create', compact('branches'));
}

public function edit(Student $student)
{
    $student->load(['branch', 'user', 'package.cabang', 'package.guru']);
    $branches = Branch::all();
    return view('admin.students.edit', compact('student', 'branches'));
}

public function store(Request $request)
{
        // normalize 'pusat' selection to null so validation accepts it
        if ($request->input('branch_id') === 'pusat' || $request->input('branch_id') === '0') {
            $request->merge(['branch_id' => null]);
        }

        $data = $request->validate([
        'name'                    => 'required|string|max:100',
        'gender'                  => 'required|in:L,P',
        'birth_date'              => 'nullable|date',
        'birth_place'             => 'nullable|string|max:100',
        'branch_id'               => 'nullable|exists:branches,id',
        'phone'                   => 'nullable|string|max:20',
        'address'                 => 'nullable|string',
        'parent_name'             => 'nullable|string|max:100',
        'parent_phone'            => 'nullable|string|max:20',
        'kategori_peserta_didik'  => 'nullable|in:Pra Sekolah (PAUD/TK),Sekolah Dasar (SD),Sekolah Menengah Pertama (SMP),Sekolah Menengah Atas/Kejuruan (SMA/SMK),Mahasiswa,Umum',
        'photo'                   => 'nullable|image|max:2048',
        'teacher_pairs'           => 'nullable|array',
        'teacher_pairs.*'         => ['nullable','regex:/^\d+:\d+$/'],
        'teacher_ids'             => 'nullable|array',
        'teacher_ids.*'           => 'exists:teachers,id',
        'email'                   => ['required', 'email', 'unique:users,email'],
        'password'                => 'required|string|min:8',
    ]);

    $password = $data['password'];
    $email = $data['email'];
    unset($data['password'], $data['email']);

    if (empty($data['nis'] ?? null)) {
        do {
            $data['nis'] = 'S' . now()->format('YmdHis') . Str::upper(Str::random(3));
        } while (Student::where('nis', $data['nis'])->exists());
    }

    $data['join_date'] = now()->toDateString();

        // allow choosing 'pusat' from the UI (maps to null branch)
        if ($request->input('branch_id') === 'pusat' || $request->input('branch_id') === '0') {
            $data['branch_id'] = null;
        }

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('students', 'public');
    }

    // prefer explicit pairs if provided, else teacher_ids
    $teacherIds = [];
    if ($request->filled('teacher_pairs')) {
        $pairs = $request->input('teacher_pairs', []);
        foreach ($pairs as $p) {
            [$tid, $cid] = explode(':', $p);
            $teacherIds[] = intval($tid);
        }
        $teacherIds = array_values(array_unique($teacherIds));
    } else {
        $teacherIds = $request->input('teacher_ids', []);
    }

    $student = DB::transaction(function () use ($data, $request, $email, $password, $teacherIds) {
        $user = User::create([
            'name' => $data['name'],
            'email' => $email,
            'password' => Hash::make($password),
            'phone' => $data['phone'] ?? null,
            'branch_id' => $data['branch_id'] ?? null,
            'is_active' => true,
        ]);

        if (method_exists($user, 'assignRole')) {
            $user->assignRole('siswa');
        }

        $data['user_id'] = $user->id;
        $student = Student::create($data);
        $student->teachers()->sync($teacherIds);
        return $student;
    });

    return redirect()
        ->route('admin.students.index')
        ->with('success', 'Siswa berhasil ditambahkan.');
}

    public function show(Student $student)
    {
        $student->load(['branch', 'user', 'teachers.courses', 'package.cabang', 'package.guru']);
        return response()->json(['success' => true, 'data' => $student]);
    }

    public function update(Request $request, Student $student)
    {
        // normalize 'pusat' selection to null so validation accepts it
        if ($request->input('branch_id') === 'pusat' || $request->input('branch_id') === '0') {
            $request->merge(['branch_id' => null]);
        }

        $request->validate([
            'name'                   => 'required|string|max:100',
            'gender'                 => 'required|in:L,P',
            'birth_date'             => 'nullable|date',
            'phone'                  => 'nullable|string|max:20',
            'address'                => 'nullable|string',
            'parent_name'            => 'nullable|string|max:100',
            'parent_phone'           => 'nullable|string|max:20',
            'branch_id'              => 'nullable|exists:branches,id',
            'kategori_peserta_didik' => 'nullable|in:Pra Sekolah (PAUD/TK),Sekolah Dasar (SD),Sekolah Menengah Pertama (SMP),Sekolah Menengah Atas/Kejuruan (SMA/SMK),Mahasiswa,Umum',
            'photo'                  => 'nullable|image|max:2048',
            'teacher_ids'            => 'nullable|array',
            'teacher_ids.*'          => 'exists:teachers,id',
            'email'                  => ['nullable', 'email', Rule::unique('users', 'email')->ignore($student->user_id)],
            'password'               => 'nullable|string|min:8',
        ]);

        // prefer explicit pairs if provided, else teacher_ids
        $teacherIds = [];
        if ($request->filled('teacher_pairs')) {
            $pairs = $request->input('teacher_pairs', []);
            foreach ($pairs as $p) {
                [$tid, $cid] = explode(':', $p);
                $teacherIds[] = intval($tid);
            }
            $teacherIds = array_values(array_unique($teacherIds));
        } else {
            $teacherIds = $request->input('teacher_ids', []);
        }
        $data = $request->except(['photo', 'email', 'password', 'teacher_ids']);

        if ($request->input('branch_id') === 'pusat' || $request->input('branch_id') === '0') {
            $data['branch_id'] = null;
        }

        if ($request->hasFile('photo')) {
            if ($student->photo) Storage::disk('public')->delete($student->photo);
            $data['photo'] = $request->file('photo')->store('students', 'public');
        }

        DB::transaction(function () use ($request, $student, $data, $teacherIds) {
            $student->update($data);
            $student->teachers()->sync($teacherIds);

            $userData = [
                'name' => $data['name'],
                'phone' => $data['phone'] ?? null,
                'branch_id' => $data['branch_id'] ?? null,
                'is_active' => ($data['status'] ?? $student->status) === 'aktif',
            ];
            if ($request->filled('email')) $userData['email'] = $request->email;
            if ($request->filled('password')) $userData['password'] = Hash::make($request->password);

            if ($student->user) {
                $student->user->update($userData);
            } elseif ($request->filled('email') && $request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
                $user = User::create($userData);
                if (method_exists($user, 'assignRole')) $user->assignRole('siswa');
                $student->update(['user_id' => $user->id]);
            }
        });

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        if ($student->photo) Storage::disk('public')->delete($student->photo);
        $student->teachers()->detach();
        if ($student->user) $student->user->delete();
        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil dihapus!',
        ]);
    }
}
