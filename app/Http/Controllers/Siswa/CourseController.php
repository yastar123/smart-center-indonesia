<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Student;
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

        $courses = Course::whereIn('id', function ($q) use ($student) {
            $q->select('mata_pelajaran_id')->from('school_classes')
                ->whereIn('id', function ($q2) use ($student) {
                    $q2->select('class_id')->from('class_students')->where('student_id', $student->id);
                })->whereNull('deleted_at');
        })->with('cabang')->get();

        $fees = DB::table('course_fees')->pluck('amount', 'course_id')->toArray();
        $payments = DB::table('student_course_payments')
            ->where('student_id', $student->id)
            ->get()
            ->keyBy('course_id');

        return view('siswa.courses.index', compact('courses', 'fees', 'payments', 'student'));
    }

    public function fees(Request $request)
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (! $student) {
            return redirect()->route('dashboard')->with('error', 'Profil siswa belum lengkap.');
        }

        // IDs of classes the student is already enrolled in
        $enrolledClassIds = \App\Models\SchoolClass::whereHas('siswa', function ($q) use ($student) {
            $q->where('student_id', $student->id);
        })->pluck('id')->toArray();

        // All active classes from student's branch (or global)
        $classesQuery = \App\Models\SchoolClass::with(['mataPelajaran', 'guru', 'cabang'])
            ->where('status', 'aktif')
            ->where(function ($q) use ($student) {
                $q->whereNull('cabang_id')
                  ->orWhere('cabang_id', $student->branch_id);
            });

        // Filters
        if ($request->filled('course_id')) {
            $classesQuery->where('mata_pelajaran_id', $request->course_id);
        }
        if ($request->filled('jenis')) {
            $classesQuery->where('jenis', $request->jenis);
        }
        if ($request->filled('guru_id')) {
            $classesQuery->where('guru_id', $request->guru_id);
        }
        if ($request->filled('cabang_id')) {
            $classesQuery->where('cabang_id', $request->cabang_id);
        }
        if ($request->filled('harga_min')) {
            $classesQuery->whereHas('mataPelajaran.fee', function ($q) use ($request) {
                $q->where('amount', '>=', $request->harga_min);
            });
        }
        if ($request->filled('harga_max')) {
            $classesQuery->whereHas('mataPelajaran.fee', function ($q) use ($request) {
                $q->where('amount', '<=', $request->harga_max);
            });
        }

        $classes = $classesQuery->orderBy('nama_kelas')->get();

        // Data for filter dropdowns
        $courses = \App\Models\Course::where('status', 'aktif')
            ->where(function ($q) use ($student) {
                $q->whereNull('cabang_id')->orWhere('cabang_id', $student->branch_id);
            })->orderBy('nama')->get();

        $teachers = \App\Models\Teacher::where('status', 'aktif')
            ->where(function ($q) use ($student) {
                $q->whereNull('branch_id')->orWhere('branch_id', $student->branch_id);
            })->orderBy('name')->get();

        $branches = \App\Models\Branch::where('status', 'active')->orderBy('name')->get();

        return view('siswa.courses.fees', compact('classes', 'student', 'enrolledClassIds', 'courses', 'teachers', 'branches'));
    }
}
