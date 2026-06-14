<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceHistoryController extends Controller
{
    public function index()
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();

        $courses = Course::whereIn('id', function ($q) use ($teacher) {
            $q->select('mata_pelajaran_id')->from('school_classes')
                ->when($teacher, fn ($q2) => $q2->where('guru_id', $teacher->id))
                ->whereNull('deleted_at');
        })->get();

        $meetingCounts = DB::table('schedules as s')
            ->join('school_classes as sc', 'sc.id', '=', 's.kelas_id')
            ->when($teacher, fn ($q) => $q->where('s.guru_id', $teacher->id))
            ->select('sc.mata_pelajaran_id', DB::raw('COUNT(*) as total'))
            ->groupBy('sc.mata_pelajaran_id')
            ->pluck('total', 'mata_pelajaran_id');

        return view('guru.attendance-history.index', compact('courses', 'meetingCounts', 'teacher'));
    }

    public function show(Course $course)
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();

        $classes = SchoolClass::with(['jadwal' => fn ($q) => $q->orderBy('pertemuan_ke')])
            ->where('mata_pelajaran_id', $course->id)
            ->when($teacher, fn ($q) => $q->where('guru_id', $teacher->id))
            ->get();

        $scheduleIds = $classes->flatMap(fn ($c) => $c->jadwal->pluck('id'));

        // Per-student per-schedule attendance records (for grid view)
        $attendanceRecords = DB::table('absensi_siswas')
            ->whereIn('jadwal_id', $scheduleIds)
            ->get(['jadwal_id', 'siswa_id', 'guru_hadir', 'siswa_konfirmasi_at', 'status'])
            ->groupBy('jadwal_id')
            ->map(fn ($rows) => $rows->keyBy('siswa_id'));

        // Students per class (from class_students pivot)
        $classStudents = [];
        foreach ($classes as $class) {
            $studentIds = DB::table('class_students')->where('class_id', $class->id)->pluck('student_id');
            $students = DB::table('students')
                ->whereIn('id', $studentIds)
                ->where('status', 'aktif')
                ->orderBy('name')
                ->get(['id', 'name', 'nis']);
            $classStudents[$class->id] = $students;
        }

        return view('guru.attendance-history.show', compact('course', 'classes', 'attendanceRecords', 'classStudents', 'teacher'));
    }
}
