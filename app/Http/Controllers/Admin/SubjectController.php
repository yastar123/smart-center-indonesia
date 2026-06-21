<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Branch;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with('cabang');

        if ($s = $request->search) {
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%$s%")
                  ->orWhere('kode', 'like', "%$s%");
            });
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->cabang_id) {
            $query->where('cabang_id', $request->cabang_id);
        }

        $subjects  = $query->latest()->paginate(15)->appends($request->all());
        $branches  = Branch::orderBy('name')->get();

        $stats = [
            'total'    => Course::count(),
            'aktif'    => Course::where('status', 'aktif')->count(),
            'nonaktif' => Course::where('status', 'nonaktif')->count(),
        ];

        return view('admin.subject.index', compact('subjects', 'branches', 'stats'));
    }

    public function create()
    {
        $branches = Branch::orderBy('name')->get();
        return view('admin.subject.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode'      => 'required|string|max:20',
            'nama'      => 'required|string|max:100',
            'kategori'  => 'nullable|in:academic,skill',
            'deskripsi' => 'nullable|string',
            'cabang_id' => 'nullable|exists:branches,id',
            'status'    => 'required|in:aktif,nonaktif',
        ]);

        Course::create($data);

        return redirect()->route('admin.subject.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function show(Course $subject)
    {
        $subject->load(['cabang', 'guru', 'modul', 'kelas', 'paket']);
        return view('admin.subject.detail', compact('subject'));
    }

    public function edit(Course $subject)
    {
        $branches = Branch::orderBy('name')->get();
        return view('admin.subject.edit', compact('subject', 'branches'));
    }

    public function update(Request $request, Course $subject)
    {
        $data = $request->validate([
            'kode'      => 'required|string|max:20',
            'nama'      => 'required|string|max:100',
            'kategori'  => 'nullable|in:academic,skill',
            'deskripsi' => 'nullable|string',
            'cabang_id' => 'nullable|exists:branches,id',
            'status'    => 'required|in:aktif,nonaktif',
        ]);

        $subject->update($data);

        return redirect()->route('admin.subject.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Course $subject)
    {
        $subject->delete();
        return redirect()->route('admin.subject.index')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
