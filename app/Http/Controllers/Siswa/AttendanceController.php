<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\SchoolClass;
use App\Models\Schedule;
use App\Models\ScheduleStudentAgreement;
use App\Models\Student;
use App\Services\ScheduleLockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
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
        })->get();

        $meetingCounts = DB::table('schedules as s')
            ->join('school_classes as sc', 'sc.id', '=', 's.kelas_id')
            ->join('class_students as cs', 'cs.class_id', '=', 'sc.id')
            ->where('cs.student_id', $student->id)
            ->select('sc.mata_pelajaran_id', DB::raw('COUNT(DISTINCT s.id) as total'))
            ->groupBy('sc.mata_pelajaran_id')
            ->pluck('total', 'mata_pelajaran_id');

        $attendanceSummary = DB::table('absensi_siswas as ab')
            ->join('schedules as s', 's.id', '=', 'ab.jadwal_id')
            ->join('school_classes as sc', 'sc.id', '=', 's.kelas_id')
            ->where('ab.siswa_id', $student->id)
            ->select('sc.mata_pelajaran_id', DB::raw("
                SUM(CASE WHEN ab.status='hadir' THEN 1 ELSE 0 END) as hadir,
                COUNT(*) as total
            "))
            ->groupBy('sc.mata_pelajaran_id')
            ->get()
            ->keyBy('mata_pelajaran_id');

        return view('siswa.attendance.index', compact('courses', 'meetingCounts', 'attendanceSummary', 'student'));
    }

    public function show(Course $course)
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (! $student) {
            return redirect()->route('siswa.attendance');
        }

        $classes = SchoolClass::with(['jadwal' => fn ($q) => $q->orderBy('pertemuan_ke')])
            ->where('mata_pelajaran_id', $course->id)
            ->whereIn('id', function ($q) use ($student) {
                $q->select('class_id')->from('class_students')->where('student_id', $student->id);
            })->get();

        $scheduleIds = $classes->flatMap(fn ($c) => $c->jadwal->pluck('id'));

        $myAttendance = DB::table('absensi_siswas')
            ->where('siswa_id', $student->id)
            ->whereIn('jadwal_id', $scheduleIds)
            ->pluck('status', 'jadwal_id');

        $agreements = ScheduleStudentAgreement::where('student_id', $student->id)
            ->whereIn('schedule_id', $scheduleIds)
            ->get()
            ->keyBy('schedule_id');

        $lockService = app(ScheduleLockService::class);

        return view('siswa.attendance.show', compact(
            'course', 'classes', 'myAttendance', 'agreements', 'student', 'lockService'
        ));
    }

    public function confirmSchedule(Schedule $schedule)
    {
        return app(ScheduleAgreementController::class)->confirm(request(), $schedule);
    }
}
