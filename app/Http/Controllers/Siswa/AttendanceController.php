<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (! $student) {
            return redirect()->route('dashboard')->with('error','Profil siswa belum lengkap.');
        }

        // Get courses the student is enrolled in via school_classes -> class_students pivot
        $courses = Course::whereIn('id', function($q) use ($student) {
            $q->select('mata_pelajaran_id')->from('school_classes')
                ->whereIn('id', function($q2) use ($student) {
                    $q2->select('class_id')->from('class_students')->where('student_id', $student->id);
                })->whereNull('deleted_at');
        })->get();

        return view('siswa.attendance.index', compact('courses'));
    }

    public function show(Course $course)
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (! $student) return redirect()->route('siswa.attendance');

        // Find classes of this course where the student is enrolled
        $classes = SchoolClass::with('jadwal')
            ->where('mata_pelajaran_id', $course->id)
            ->whereIn('id', function($q) use ($student) {
                $q->select('class_id')->from('class_students')->where('student_id', $student->id);
            })->get();

        return view('siswa.attendance.show', compact('course','classes'));
    }
}
