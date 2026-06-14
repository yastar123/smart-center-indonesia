<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\AbsensiSiswa;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\Student;
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

        // Pending confirmations count (guru marked hadir, student hasn't confirmed)
        $pendingConfirmations = DB::table('absensi_siswas')
            ->where('siswa_id', $student->id)
            ->where('guru_hadir', true)
            ->whereNull('siswa_konfirmasi_at')
            ->count();

        return view('siswa.attendance.index', compact(
            'courses', 'meetingCounts', 'attendanceSummary', 'student', 'pendingConfirmations'
        ));
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

        // Load absensi records with dual-confirmation fields
        $myAttendance = DB::table('absensi_siswas')
            ->where('siswa_id', $student->id)
            ->whereIn('jadwal_id', $scheduleIds)
            ->get(['jadwal_id', 'guru_hadir', 'siswa_konfirmasi_at', 'status'])
            ->keyBy('jadwal_id');

        return view('siswa.attendance.show', compact(
            'course', 'classes', 'myAttendance', 'student'
        ));
    }

    public function confirmAttendance(Schedule $schedule)
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (! $student) {
            return response()->json(['success' => false, 'message' => 'Profil siswa tidak ditemukan.'], 403);
        }

        $record = DB::table('absensi_siswas')
            ->where('jadwal_id', $schedule->id)
            ->where('siswa_id', $student->id)
            ->first();

        if (! $record) {
            return response()->json(['success' => false, 'message' => 'Data absensi belum tersedia. Guru belum mengisi absensi.'], 404);
        }

        if ($record->siswa_konfirmasi_at) {
            return response()->json(['success' => false, 'message' => 'Kehadiran sudah dikonfirmasi sebelumnya.'], 422);
        }

        // Confirmation only allowed during class hours
        $now = now();
        $classDate = \Carbon\Carbon::parse($schedule->tanggal)->format('Y-m-d');
        $startTime = \Carbon\Carbon::parse($classDate . ' ' . $schedule->jam_mulai);
        $endTime   = \Carbon\Carbon::parse($classDate . ' ' . $schedule->jam_selesai);

        if ($now->lt($startTime)) {
            return response()->json(['success' => false, 'message' => 'Konfirmasi kehadiran hanya bisa dilakukan saat kelas sudah dimulai (' . $startTime->format('H:i') . ' WIB).'], 422);
        }
        if ($now->gt($endTime)) {
            return response()->json(['success' => false, 'message' => 'Konfirmasi kehadiran sudah ditutup. Kelas telah selesai pada ' . $endTime->format('H:i') . ' WIB.'], 422);
        }
        $status = AbsensiSiswa::computeStatus((bool) $record->guru_hadir, $now);

        DB::table('absensi_siswas')
            ->where('jadwal_id', $schedule->id)
            ->where('siswa_id', $student->id)
            ->update([
                'siswa_konfirmasi_at' => $now,
                'status'              => $status,
                'updated_at'          => $now,
            ]);

        return response()->json(['success' => true, 'message' => 'Kehadiran berhasil dikonfirmasi!', 'status' => $status]);
    }
}
