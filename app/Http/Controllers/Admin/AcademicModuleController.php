<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'jenis'             => 'nullable|in:materi,video',
            'status'            => 'required|in:aktif,nonaktif',
            'module_file'       => 'nullable|file|mimes:pdf,doc,docx|max:51200',
            'video_url'         => 'nullable|url',
        ]);

        if (!$request->hasFile('module_file') && !$request->filled('video_url')) {
            return redirect()->back()
                ->withErrors([
                    'module_file' => 'Upload file modul (PDF/DOC/DOCX) atau isi link video wajib diisi.',
                ])
                ->withInput();
        }

        $data['jenis'] = $data['jenis'] ?? ($request->filled('video_url') ? 'video' : 'materi');
        $data['diupload_oleh'] = auth()->id();

        if ($request->hasFile('module_file')) {
            $uploaded = $request->file('module_file')->store('modules', 'public');
            $data['file_path'] = $uploaded;
            $data['file_url'] = null;
            $data['ukuran_file'] = $request->file('module_file')->getSize();
            if ($request->file('module_file')->getClientOriginalExtension() === 'pdf') {
                $data['jenis'] = 'pdf';
            }
        } elseif ($request->filled('video_url')) {
            $data['file_url'] = $request->video_url;
            $data['file_path'] = null;
            $data['ukuran_file'] = null;
        }

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
            'jenis'             => 'nullable|in:materi,video',
            'status'            => 'required|in:aktif,nonaktif',
            'module_file'       => 'nullable|file|mimes:pdf,doc,docx|max:51200',
            'video_url'         => 'nullable|url',
        ]);

        if (!$request->hasFile('module_file') && !$request->filled('video_url') && !$module->file_path && !$module->file_url) {
            return redirect()->back()
                ->withErrors([
                    'module_file' => 'Upload file modul (PDF/DOC/DOCX) atau isi link video wajib diisi.',
                ])
                ->withInput();
        }

        if ($request->hasFile('module_file')) {
            if ($module->file_path) {
                Storage::disk('public')->delete($module->file_path);
            }
            $uploaded = $request->file('module_file')->store('modules', 'public');
            $data['file_path'] = $uploaded;
            $data['file_url'] = null;
            $data['ukuran_file'] = $request->file('module_file')->getSize();
            if ($request->file('module_file')->getClientOriginalExtension() === 'pdf') {
                $data['jenis'] = 'pdf';
            }
        } elseif ($request->filled('video_url')) {
            $data['file_url'] = $request->video_url;
            $data['file_path'] = null;
            $data['ukuran_file'] = null;
            $data['jenis'] = $data['jenis'] ?? 'video';
        }

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
