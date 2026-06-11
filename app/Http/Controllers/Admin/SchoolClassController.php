<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Branch;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index(Request $request)
    {
        $query = SchoolClass::with(['cabang', 'mataPelajaran', 'guru']);

        if ($s = $request->search) $query->where('nama_kelas', 'like', "%$s%");
        if ($request->status) $query->where('status', $request->status);
        if ($request->jenis) $query->where('jenis', $request->jenis);
        if ($request->cabang_id) $query->where('cabang_id', $request->cabang_id);

        $classes  = $query->latest()->paginate(15)->appends($request->all());
        $courses  = Course::where('status', 'aktif')->orderBy('nama')->get();
        $teachers = Teacher::where('status', 'aktif')->orderBy('name')->get();
        $branches = Branch::orderBy('name')->get();

        $stats = [
            'total'   => SchoolClass::count(),
            'aktif'   => SchoolClass::where('status', 'aktif')->count(),
            'online'  => SchoolClass::where('jenis', 'online')->count(),
            'offline' => SchoolClass::where('jenis', 'offline')->count(),
        ];

        return view('admin.classes.index', compact('classes', 'courses', 'teachers', 'branches', 'stats'));
    }

    public function store(Request $request)
    {
        if ($request->input('cabang_id') === 'pusat' || $request->input('cabang_id') === '0') {
            $request->merge(['cabang_id' => null]);
        }

        $data = $request->validate([
            'cabang_id'         => 'nullable|exists:branches,id',
            'mata_pelajaran_id' => 'nullable|exists:courses,id',
            'guru_id'           => 'nullable|exists:teachers,id',
            'kapasitas'         => 'nullable|integer|min:1|max:200',
            'jumlah_pertemuan'  => 'required|integer|min:1|max:200',
            'jenis'             => 'required|in:online,offline,private',
            'link_zoom'         => 'nullable|url',
            'status'            => 'required|in:aktif,nonaktif,penuh',
        ]);

        if ($request->input('cabang_id') === 'pusat' || $request->input('cabang_id') === '0') {
            $data['cabang_id'] = null;
        }

        // Auto-generate nama_kelas if not provided
        if (!isset($data['nama_kelas'])) {
            $course = \App\Models\Course::find($request->mata_pelajaran_id);
            $teacher = \App\Models\Teacher::find($request->guru_id);
            $branch = \App\Models\Branch::find($request->cabang_id);
            
            $parts = [];
            if ($course) $parts[] = $course->nama;
            if ($teacher) $parts[] = $teacher->name;
            if ($branch) $parts[] = $branch->name;
            if ($request->jenis) $parts[] = ucfirst($request->jenis);
            
            $data['nama_kelas'] = implode(' - ', $parts) ?: 'Kelas Baru';
        }

        SchoolClass::create($data);

        return response()->json(['success' => true, 'message' => 'Kelas berhasil ditambahkan.']);
    }

    public function show(SchoolClass $class)
    {
        return response()->json($class->load(['cabang', 'mataPelajaran', 'guru']));
    }

    public function update(Request $request, SchoolClass $class)
    {
        if ($request->input('cabang_id') === 'pusat' || $request->input('cabang_id') === '0') {
            $request->merge(['cabang_id' => null]);
        }

        $data = $request->validate([
            'cabang_id'         => 'nullable|exists:branches,id',
            'mata_pelajaran_id' => 'nullable|exists:courses,id',
            'guru_id'           => 'nullable|exists:teachers,id',
            'kapasitas'         => 'nullable|integer|min:1|max:200',
            'jumlah_pertemuan'  => 'required|integer|min:1|max:200',
            'jenis'             => 'required|in:online,offline,private',
            'link_zoom'         => 'nullable|url',
            'status'            => 'required|in:aktif,nonaktif,penuh',
        ]);

        if ($request->input('cabang_id') === 'pusat' || $request->input('cabang_id') === '0') {
            $data['cabang_id'] = null;
        }

        // Auto-generate nama_kelas if not provided
        if (!isset($data['nama_kelas'])) {
            $course = \App\Models\Course::find($request->mata_pelajaran_id);
            $teacher = \App\Models\Teacher::find($request->guru_id);
            $branch = \App\Models\Branch::find($request->cabang_id);
            
            $parts = [];
            if ($course) $parts[] = $course->nama;
            if ($teacher) $parts[] = $teacher->name;
            if ($branch) $parts[] = $branch->name;
            if ($request->jenis) $parts[] = ucfirst($request->jenis);
            
            $data['nama_kelas'] = implode(' - ', $parts) ?: 'Kelas Baru';
        }

        $class->update($data);

        return response()->json(['success' => true, 'message' => 'Kelas berhasil diperbarui.']);
    }

    public function destroy(SchoolClass $class)
    {
        $class->delete();
        return response()->json(['success' => true, 'message' => 'Kelas berhasil dihapus.']);
    }

    public function getTeacherCourses($teacherId)
    {
        $teacher = Teacher::with('courses')->find($teacherId);
        if (!$teacher) {
            return response()->json(['courses' => []]);
        }
        return response()->json(['courses' => $teacher->courses]);
    }
}
