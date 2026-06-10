<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Branch;
use Illuminate\Http\Request;


class CourseController extends Controller
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
        if ($request->cabang_id) {
            $query->where('cabang_id', $request->cabang_id);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $courses  = $query->latest()->paginate(15)->appends($request->all());
        $branches = Branch::orderBy('name')->get();

        $stats = [
            'total'    => Course::count(),
            'aktif'    => Course::where('status', 'aktif')->count(),
            'nonaktif' => Course::where('status', 'nonaktif')->count(),
        ];

        return view('admin.courses.index', compact('courses', 'branches', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode'      => 'required|string|max:20',
            'nama'      => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'cabang_id' => 'nullable|exists:branches,id',
            'status'    => 'required|in:aktif,nonaktif',
        ]);

        Course::create($data);

        return response()->json(['success' => true, 'message' => 'Mata pelajaran berhasil ditambahkan.']);
    }

    public function show(Course $course)
    {
        return response()->json($course->load('cabang'));
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'kode'      => 'required|string|max:20',
            'nama'      => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'cabang_id' => 'nullable|exists:branches,id',
            'status'    => 'required|in:aktif,nonaktif',
        ]);

        $course->update($data);

        return response()->json(['success' => true, 'message' => 'Mata pelajaran berhasil diperbarui.']);
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return response()->json(['success' => true, 'message' => 'Mata pelajaran berhasil dihapus.']);
    }
}
