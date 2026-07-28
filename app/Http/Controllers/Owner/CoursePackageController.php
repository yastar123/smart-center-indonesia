<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageCourseTeacher;
use App\Models\Branch;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoursePackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::with(['cabang', 'mataPelajaran', 'guru', 'courseTeachers.teacher', 'courseTeachers.course']);

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

        return view('owner.course-package.index', compact('packages', 'branches', 'stats'));
    }

    public function create()
    {
        $branches       = Branch::orderBy('name')->get();
        $courses        = Course::where('status', 'aktif')->orderBy('jenis_kursus')->orderBy('nama')->get();
        $coursesGrouped = $courses->groupBy(fn($c) => $c->jenis_kursus ?: 'lainnya');
        $teachers       = Teacher::where('status', 'aktif')->orderBy('name')->get();
        return view('owner.course-package.create', compact('branches', 'courses', 'coursesGrouped', 'teachers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'             => 'required|string|max:150',
            'deskripsi'        => 'nullable|string',
            'harga'            => 'required|numeric|min:0',
            'durasi_bulan'     => 'nullable|integer|min:1',
            'jumlah_pertemuan' => 'required|integer|min:1',
            'jenis'            => 'required|in:reguler,intensif,privat,online',
            'metode_absensi'   => 'required|in:manual,otomatis',
            'tipe_kelas'       => 'required|in:offline,online,private',
            'cabang_id'        => 'nullable|exists:branches,id',
            'is_unggulan'      => 'nullable|boolean',
            'status'           => 'required|in:aktif,nonaktif',
        ]);

        $data['is_unggulan'] = $request->boolean('is_unggulan');

        DB::transaction(function () use ($data, $request) {
            $package = Package::create($data);

            $courseIds = array_filter((array) $request->course_ids);
            if (!empty($courseIds)) {
                $package->mataPelajaran()->sync($courseIds);
            }

            $courseTeachers = $request->input('course_teachers', []);
            foreach ($courseTeachers as $courseId => $teacherIds) {
                if (!is_array($teacherIds)) continue;
                foreach (array_filter($teacherIds) as $teacherId) {
                    PackageCourseTeacher::firstOrCreate([
                        'package_id' => $package->id,
                        'course_id'  => (int) $courseId,
                        'teacher_id' => (int) $teacherId,
                    ]);
                }
            }
        });

        return redirect()->route('owner.course-package.index')
            ->with('success', 'Paket belajar berhasil ditambahkan.');
    }

    public function show(Package $coursePackage)
    {
        $coursePackage->load(['cabang', 'mataPelajaran', 'guru', 'courseTeachers.teacher', 'courseTeachers.course']);
        return view('owner.course-package.detail', compact('coursePackage'));
    }

    public function edit(Package $coursePackage)
    {
        $branches       = Branch::orderBy('name')->get();
        $courses        = Course::where('status', 'aktif')->orderBy('jenis_kursus')->orderBy('nama')->get();
        $coursesGrouped = $courses->groupBy(fn($c) => $c->jenis_kursus ?: 'lainnya');
        $teachers       = Teacher::where('status', 'aktif')->orderBy('name')->get();
        $coursePackage->load(['mataPelajaran', 'courseTeachers']);
        return view('owner.course-package.edit', compact('coursePackage', 'branches', 'courses', 'coursesGrouped', 'teachers'));
    }

    public function update(Request $request, Package $coursePackage)
    {
        $data = $request->validate([
            'nama'             => 'required|string|max:150',
            'deskripsi'        => 'nullable|string',
            'harga'            => 'required|numeric|min:0',
            'durasi_bulan'     => 'nullable|integer|min:1',
            'jumlah_pertemuan' => 'required|integer|min:1',
            'jenis'            => 'required|in:reguler,intensif,privat,online',
            'metode_absensi'   => 'required|in:manual,otomatis',
            'tipe_kelas'       => 'required|in:offline,online,private',
            'cabang_id'        => 'nullable|exists:branches,id',
            'is_unggulan'      => 'nullable|boolean',
            'status'           => 'required|in:aktif,nonaktif',
        ]);

        $data['is_unggulan'] = $request->boolean('is_unggulan');

        DB::transaction(function () use ($data, $request, $coursePackage) {
            $coursePackage->update($data);

            $courseIds = array_filter((array) $request->course_ids);
            $coursePackage->mataPelajaran()->sync($courseIds);

            if ($request->has('course_teachers')) {
                PackageCourseTeacher::where('package_id', $coursePackage->id)->delete();
                $courseTeachers = $request->input('course_teachers', []);
                foreach ($courseTeachers as $courseId => $teacherIds) {
                    if (!is_array($teacherIds)) continue;
                    foreach (array_filter($teacherIds) as $teacherId) {
                        PackageCourseTeacher::firstOrCreate([
                            'package_id' => $coursePackage->id,
                            'course_id'  => (int) $courseId,
                            'teacher_id' => (int) $teacherId,
                        ]);
                    }
                }
            } else {
                PackageCourseTeacher::where('package_id', $coursePackage->id)
                    ->whereNotIn('course_id', $courseIds)
                    ->delete();
            }
        });

        return redirect()->route('owner.course-package.index')
            ->with('success', 'Paket belajar berhasil diperbarui.');
    }

    public function destroy(Package $coursePackage)
    {
        $coursePackage->delete();
        return redirect()->route('owner.course-package.index')
            ->with('success', 'Paket belajar berhasil dihapus.');
    }

    public function courseTeachersApi(Package $coursePackage)
    {
        $coursePackage->load(['mataPelajaran', 'courseTeachers.teacher']);

        $data = $coursePackage->mataPelajaran->map(function ($course) use ($coursePackage) {
            $teachers = $coursePackage->courseTeachers
                ->where('course_id', $course->id)
                ->map(fn($ct) => [
                    'id'   => optional($ct->teacher)->id,
                    'name' => optional($ct->teacher)->name,
                ])
                ->filter(fn($t) => $t['id']);

            return [
                'id'       => $course->id,
                'nama'     => $course->nama,
                'teachers' => $teachers->values(),
            ];
        });

        return response()->json([
            'success'          => true,
            'package_id'       => $coursePackage->id,
            'nama'             => $coursePackage->nama,
            'jumlah_pertemuan' => $coursePackage->jumlah_pertemuan,
            'harga'            => (float) $coursePackage->harga,
            'courses'          => $data,
        ]);
    }
}
