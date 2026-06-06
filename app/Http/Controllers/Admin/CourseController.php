<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with('cabang');

        if ($s = $request->search) {
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%$s%")
                  ->orWhere('kode', 'like', "%$s%")
                  ->orWhere('kategori', 'like', "%$s%");
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
            'kategori'  => 'nullable|string|max:50',
            'icon'      => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'warna'     => 'nullable|string|max:10',
            'cabang_id' => 'nullable|exists:branches,id',
            'status'    => 'required|in:aktif,nonaktif',
        ]);

        // Handle uploaded icon image
        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('courses', 'public');
            $data['icon'] = $path;
        }

        Course::create($data);

        return response()->json(['success' => true, 'message' => 'Mata pelajaran berhasil ditambahkan.']);
    }

    public function show(Course $course)
    {
        $course->load('cabang');
        $payload = $course->toArray();
        $payload['icon_url'] = $course->icon ? Storage::url($course->icon) : null;
        return response()->json($payload);
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'kode'      => 'required|string|max:20',
            'nama'      => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'kategori'  => 'nullable|string|max:50',
            'icon'      => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'warna'     => 'nullable|string|max:10',
            'cabang_id' => 'nullable|exists:branches,id',
            'status'    => 'required|in:aktif,nonaktif',
        ]);

        if ($request->hasFile('icon')) {
            // delete old if exists
            if ($course->icon) {
                Storage::disk('public')->delete($course->icon);
            }
            $path = $request->file('icon')->store('courses', 'public');
            $data['icon'] = $path;
        }

        $course->update($data);

        return response()->json(['success' => true, 'message' => 'Mata pelajaran berhasil diperbarui.']);
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return response()->json(['success' => true, 'message' => 'Mata pelajaran berhasil dihapus.']);
    }
}
