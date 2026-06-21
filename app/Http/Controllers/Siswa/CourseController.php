<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Course;
use App\Models\Package;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Teacher;
use App\Services\ScheduleAgreementService;
use App\Services\ScheduleLockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function index()
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (! $student) {
            return redirect()->route('dashboard')->with('error', 'Profil siswa belum lengkap.');
        }

        $packages = collect();

        if ($student->package_id) {
            $packages = $student->package()
                ->with(['cabang', 'mataPelajaran'])
                ->get();
        }

        return view('siswa.courses.index', compact('packages', 'student'));
    }

    public function fees(Request $request)
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (! $student) {
            return redirect()->route('dashboard')->with('error', 'Profil siswa belum lengkap.');
        }

        $packagesQuery = Package::with(['cabang', 'guru', 'mataPelajaran'])
            ->where('status', 'aktif')
            ->where(function ($q) use ($student) {
                $q->whereNull('cabang_id')
                  ->orWhere('cabang_id', $student->branch_id);
            });

        if ($request->filled('course_id')) {
            $packagesQuery->whereHas('mataPelajaran', function ($q) use ($request) {
                $q->where('courses.id', $request->course_id);
            });
        }
        if ($request->filled('jenis')) {
            $packagesQuery->where('jenis', $request->jenis);
        }
        if ($request->filled('guru_id')) {
            $packagesQuery->where('guru_id', $request->guru_id);
        }
        if ($request->filled('cabang_id')) {
            $packagesQuery->where('cabang_id', $request->cabang_id);
        }
        if ($request->filled('harga_min')) {
            $packagesQuery->where('harga', '>=', $request->harga_min);
        }
        if ($request->filled('harga_max')) {
            $packagesQuery->where('harga', '<=', $request->harga_max);
        }

        $packages = $packagesQuery->orderBy('nama')->get();

        $courses = Course::where('status', 'aktif')
            ->where(function ($q) use ($student) {
                $q->whereNull('cabang_id')->orWhere('cabang_id', $student->branch_id);
            })
            ->orderBy('nama')
            ->get();

        $teachers = Teacher::where('status', 'aktif')
            ->where(function ($q) use ($student) {
                $q->whereNull('branch_id')->orWhere('branch_id', $student->branch_id);
            })
            ->orderBy('name')
            ->get();

        $branches = Branch::where('status', 'active')->orderBy('name')->get();

        return view('siswa.courses.fees', compact('packages', 'student', 'courses', 'teachers', 'branches'));
    }
}
