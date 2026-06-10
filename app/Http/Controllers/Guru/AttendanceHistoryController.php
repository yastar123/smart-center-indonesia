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

        $attendanceStats = DB::table('absensi_siswas')
            ->whereIn('jadwal_id', $scheduleIds)
            ->select('jadwal_id', DB::raw("
                SUM(CASE WHEN status='hadir' THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status='izin' THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status='sakit' THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status='alpa' THEN 1 ELSE 0 END) as alpa,
                COUNT(*) as total
            "))
            ->groupBy('jadwal_id')
            ->get()
            ->keyBy('jadwal_id');

        return view('guru.attendance-history.show', compact('course', 'classes', 'attendanceStats', 'teacher'));
    }
}
