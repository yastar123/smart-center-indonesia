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
}
