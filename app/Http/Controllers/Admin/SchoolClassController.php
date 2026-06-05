<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Branch;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index(Request $request)
    {
        $query = SchoolClass::with(['cabang', 'mataPelajaran', 'guru', 'tahunAkademik']);

        if ($s = $request->search) {
            $query->where('nama_kelas', 'like', "%$s%");
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->jenis) {
            $query->where('jenis', $request->jenis);
        }
        if ($request->cabang_id) {
            $query->where('cabang_id', $request->cabang_id);
        }

        $classes       = $query->latest()->paginate(15)->appends($request->all());
        $courses       = Course::where('status', 'aktif')->orderBy('nama')->get();
        $teachers      = Teacher::where('status', 'aktif')->orderBy('name')->get();
        $branches      = Branch::orderBy('name')->get();
        $tahunAkademik = TahunAkademik::orderByDesc('tahun_mulai')->get();

        $stats = [
            'total'   => SchoolClass::count(),
            'aktif'   => SchoolClass::where('status', 'aktif')->count(),
            'online'  => SchoolClass::where('jenis', 'online')->count(),
            'offline' => SchoolClass::where('jenis', 'offline')->count(),
        ];

        return view('admin.classes.index', compact(
            'classes', 'courses', 'teachers', 'branches', 'tahunAkademik', 'stats'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kelas'        => 'required|string|max:100',
            'cabang_id'         => 'required|exists:branches,id',
            'mata_pelajaran_id' => 'nullable|exists:courses,id',
            'guru_id'           => 'nullable|exists:teachers,id',
            'tahun_akademik_id' => 'nullable|exists:academic_years,id',
            'kapasitas'         => 'nullable|integer|min:1|max:200',
            'jenis'             => 'required|in:online,offline,hybrid',
            'ruangan'           => 'nullable|string|max:50',
            'link_zoom'         => 'nullable|url',
            'status'            => 'required|in:aktif,nonaktif,penuh',
        ]);

        SchoolClass::create($data);

        return response()->json(['success' => true, 'message' => 'Kelas berhasil ditambahkan.']);
    }

    public function show(SchoolClass $class)
    {
        return response()->json($class->load(['cabang', 'mataPelajaran', 'guru', 'tahunAkademik']));
    }

    public function update(Request $request, SchoolClass $class)
    {
        $data = $request->validate([
            'nama_kelas'        => 'required|string|max:100',
            'cabang_id'         => 'required|exists:branches,id',
            'mata_pelajaran_id' => 'nullable|exists:courses,id',
            'guru_id'           => 'nullable|exists:teachers,id',
            'tahun_akademik_id' => 'nullable|exists:academic_years,id',
            'kapasitas'         => 'nullable|integer|min:1|max:200',
            'jenis'             => 'required|in:online,offline,hybrid',
            'ruangan'           => 'nullable|string|max:50',
            'link_zoom'         => 'nullable|url',
            'status'            => 'required|in:aktif,nonaktif,penuh',
        ]);

        $class->update($data);

        return response()->json(['success' => true, 'message' => 'Kelas berhasil diperbarui.']);
    }

    public function destroy(SchoolClass $class)
    {
        $class->delete();

        return response()->json(['success' => true, 'message' => 'Kelas berhasil dihapus.']);
    }
}
