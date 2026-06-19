<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Course;
use Illuminate\Http\Request;

class AcademicModuleController extends Controller
{
    public function index(Request $request)
    {
        $query = Module::with('mataPelajaran');

        if ($s = $request->search) {
            $query->where(function ($q) use ($s) {
                $q->where('judul', 'like', "%$s%")
                  ->orWhere('kode_modul', 'like', "%$s%");
            });
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->mata_pelajaran_id) {
            $query->where('mata_pelajaran_id', $request->mata_pelajaran_id);
        }

        $modules  = $query->latest()->paginate(15)->appends($request->all());
        $courses  = Course::where('status', 'aktif')->orderBy('nama')->get();

        $stats = [
            'total'  => Module::count(),
            'aktif'  => Module::where('status', 'aktif')->count(),
            'review' => Module::where('status', 'review')->count(),
        ];

        return view('admin.academic-module.index', compact('modules', 'courses', 'stats'));
    }

    public function create()
    {
        $courses = Course::where('status', 'aktif')->orderBy('nama')->get();
        return view('admin.academic-module.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_modul'        => 'nullable|string|max:30|unique:modules,kode_modul',
            'judul'             => 'required|string|max:200',
            'deskripsi'         => 'nullable|string',
            'mata_pelajaran_id' => 'required|exists:courses,id',
            'jenis'             => 'nullable|in:pdf,video,link,materi',
            'status'            => 'required|in:aktif,review',
        ]);

        $data['jenis'] = $data['jenis'] ?? 'materi';

        Module::create($data);

        return redirect()->route('admin.module.index')
            ->with('success', 'Modul akademik berhasil ditambahkan.');
    }

    public function show(Module $module)
    {
        $module->load('mataPelajaran');
        return view('admin.academic-module.detail', compact('module'));
    }

    public function edit(Module $module)
    {
        $courses = Course::where('status', 'aktif')->orderBy('nama')->get();
        return view('admin.academic-module.edit', compact('module', 'courses'));
    }

    public function update(Request $request, Module $module)
    {
        $data = $request->validate([
            'kode_modul'        => 'nullable|string|max:30|unique:modules,kode_modul,' . $module->id,
            'judul'             => 'required|string|max:200',
            'deskripsi'         => 'nullable|string',
            'mata_pelajaran_id' => 'required|exists:courses,id',
            'jenis'             => 'nullable|in:pdf,video,link,materi',
            'status'            => 'required|in:aktif,review',
        ]);

        $module->update($data);

        return redirect()->route('admin.module.index')
            ->with('success', 'Modul akademik berhasil diperbarui.');
    }

    public function destroy(Module $module)
    {
        $module->delete();
        return redirect()->route('admin.module.index')
            ->with('success', 'Modul akademik berhasil dihapus.');
    }
}
