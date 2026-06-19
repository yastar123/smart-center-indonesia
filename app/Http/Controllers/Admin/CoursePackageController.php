<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Branch;
use App\Models\Course;
use Illuminate\Http\Request;

class CoursePackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::with(['cabang', 'mataPelajaran']);

        if ($s = $request->search) {
            $query->where('nama', 'like', "%$s%");
        }
        if ($request->jenis) {
            $query->where('jenis', $request->jenis);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->cabang_id) {
            $query->where('cabang_id', $request->cabang_id);
        }

        $packages  = $query->latest()->paginate(15)->appends($request->all());
        $branches  = Branch::orderBy('name')->get();

        $stats = [
            'total'   => Package::count(),
            'aktif'   => Package::where('status', 'aktif')->count(),
            'draft'   => Package::where('status', 'nonaktif')->count(),
            'privat'  => Package::where('jenis', 'privat')->count(),
        ];

        return view('admin.course-package.index', compact('packages', 'branches', 'stats'));
    }

    public function create()
    {
        $branches = Branch::orderBy('name')->get();
        $courses  = Course::where('status', 'aktif')->orderBy('nama')->get();
        return view('admin.course-package.create', compact('branches', 'courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'             => 'required|string|max:150',
            'deskripsi'        => 'nullable|string',
            'harga'            => 'required|numeric|min:0',
            'durasi_bulan'     => 'required|integer|min:1',
            'jumlah_pertemuan' => 'required|integer|min:1',
            'jenis'            => 'required|in:reguler,intensif,privat,online',
            'cabang_id'        => 'nullable|exists:branches,id',
            'is_unggulan'      => 'nullable|boolean',
            'status'           => 'required|in:aktif,nonaktif',
        ]);

        $data['is_unggulan'] = $request->boolean('is_unggulan');

        $package = Package::create($data);

        if ($request->course_ids) {
            $package->mataPelajaran()->sync($request->course_ids);
        }

        return redirect()->route('admin.course-package.index')
            ->with('success', 'Paket belajar berhasil ditambahkan.');
    }

    public function show(Package $coursePackage)
    {
        $coursePackage->load(['cabang', 'mataPelajaran']);
        return view('admin.course-package.detail', compact('coursePackage'));
    }

    public function edit(Package $coursePackage)
    {
        $branches = Branch::orderBy('name')->get();
        $courses  = Course::where('status', 'aktif')->orderBy('nama')->get();
        return view('admin.course-package.edit', compact('coursePackage', 'branches', 'courses'));
    }

    public function update(Request $request, Package $coursePackage)
    {
        $data = $request->validate([
            'nama'             => 'required|string|max:150',
            'deskripsi'        => 'nullable|string',
            'harga'            => 'required|numeric|min:0',
            'durasi_bulan'     => 'required|integer|min:1',
            'jumlah_pertemuan' => 'required|integer|min:1',
            'jenis'            => 'required|in:reguler,intensif,privat,online',
            'cabang_id'        => 'nullable|exists:branches,id',
            'is_unggulan'      => 'nullable|boolean',
            'status'           => 'required|in:aktif,nonaktif',
        ]);

        $data['is_unggulan'] = $request->boolean('is_unggulan');
        $coursePackage->update($data);

        if ($request->has('course_ids')) {
            $coursePackage->mataPelajaran()->sync($request->course_ids ?? []);
        }

        return redirect()->route('admin.course-package.index')
            ->with('success', 'Paket belajar berhasil diperbarui.');
    }

    public function destroy(Package $coursePackage)
    {
        $coursePackage->delete();
        return redirect()->route('admin.course-package.index')
            ->with('success', 'Paket belajar berhasil dihapus.');
    }
}
