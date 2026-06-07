<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $q = Module::with('mataPelajaran', 'uploader')
                ->when($request->search, fn($q) => $q->where('judul', 'like', "%{$request->search}%"))
                ->when($request->jenis,  fn($q) => $q->where('jenis', $request->jenis))
                ->when($request->mata_pelajaran_id, fn($q) => $q->where('mata_pelajaran_id', $request->mata_pelajaran_id))
                ->latest();

            $modules = $q->paginate(12);
            $stats = [
                'total'  => Module::count(),
                'pdf'    => Module::where('jenis', 'pdf')->count(),
                'video'  => Module::where('jenis', 'video')->count(),
                'gratis' => Module::where('is_gratis', true)->count(),
            ];
            return response()->json(array_merge($modules->toArray(), ['stats' => $stats]));
        }

        $courses = Course::orderBy('nama')->get();
        return view('admin.modules.index', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mata_pelajaran_id' => 'required|exists:courses,id',
            'judul'             => 'required|string|max:200',
            'deskripsi'         => 'nullable|string',
            'jenis'             => 'required|in:pdf,video,materi,link',
            'urutan'            => 'nullable|integer',
            'is_gratis'         => 'nullable|boolean',
            'file'              => 'nullable|file|max:51200',
            'file_url'          => 'nullable|url',
            'status'            => 'required|in:aktif,draft',
        ]);

        $data['diupload_oleh'] = auth()->id();
        $data['is_gratis']     = $request->boolean('is_gratis');

        if ($request->hasFile('file')) {
            $uploaded = $request->file('file')->store('modules', 'public');
            $data['file_path']    = $uploaded;
            $data['ukuran_file']  = $request->file('file')->getSize();
        }

        $module = Module::create($data);
        return response()->json(['success' => true, 'message' => 'Modul berhasil ditambahkan!', 'data' => $module]);
    }

    public function show(Module $module)
    {
        return response()->json(['success' => true, 'data' => $module->load('mataPelajaran')]);
    }

    public function update(Request $request, Module $module)
    {
        $data = $request->validate([
            'mata_pelajaran_id' => 'required|exists:courses,id',
            'judul'             => 'required|string|max:200',
            'deskripsi'         => 'nullable|string',
            'jenis'             => 'required|in:pdf,video,materi,link',
            'urutan'            => 'nullable|integer',
            'is_gratis'         => 'nullable|boolean',
            'file_url'          => 'nullable|url',
            'status'            => 'required|in:aktif,draft',
        ]);

        $data['is_gratis'] = $request->boolean('is_gratis');

        if ($request->hasFile('file')) {
            if ($module->file_path) Storage::disk('public')->delete($module->file_path);
            $data['file_path']   = $request->file('file')->store('modules', 'public');
            $data['ukuran_file'] = $request->file('file')->getSize();
        }

        $module->update($data);
        return response()->json(['success' => true, 'message' => 'Modul berhasil diperbarui!']);
    }

    public function destroy(Module $module)
    {
        if ($module->file_path) Storage::disk('public')->delete($module->file_path);
        $module->delete();
        return response()->json(['success' => true, 'message' => 'Modul berhasil dihapus!']);
    }
}
