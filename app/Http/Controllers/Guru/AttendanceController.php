<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Services\ScheduleAgreementService;
use App\Services\ScheduleLockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user    = auth()->user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        $schedules = Schedule::with('kelas', 'cabang')
            ->when($teacher, fn($q) => $q->where('guru_id', $teacher->id))
            ->when($request->tanggal, fn($q) => $q->whereDate('tanggal', $request->tanggal))
            ->when(!$request->tanggal, fn($q) => $q->where('tanggal', '>=', now()->subDays(30)))
            ->orderByDesc('tanggal')
            ->get();

        $classes = SchoolClass::when($teacher, function($q) use ($teacher) {
            $q->where('cabang_id', $teacher->branch_id);
        })->get();

        return view('guru.attendance', compact('schedules', 'classes', 'teacher'));
    }

    public function getStudents(Schedule $schedule)
    {
        $lockService = app(ScheduleLockService::class);
        $agreementService = app(ScheduleAgreementService::class);

        if ($schedule->agreements()->count() === 0) {
            $agreementService->syncForSchedule($schedule);
        }

        // Filter by branch if the schedule has a branch assigned
        $students = Student::where('status', 'aktif')
            ->when($schedule->cabang_id, fn($q) => $q->where('branch_id', $schedule->cabang_id))
            ->orderBy('name')
            ->get();

        // Get existing attendance for this schedule
        $existing = DB::table('absensi_siswas')
            ->where('jadwal_id', $schedule->id)
            ->pluck('status', 'siswa_id');

        $agreements = $schedule->agreements()->get()->keyBy('student_id');

        return response()->json([
            'success'            => true,
            'students'           => $students,
            'existing'           => $existing,
            'attendance_locked'  => $lockService->isAttendanceLocked($schedule),
            'schedule_locked'    => $lockService->isScheduleLocked($schedule),
            'all_agreed'         => $agreementService->allStudentsAgreed($schedule),
            'agreements'         => $agreements,
        ]);
    }

    public function store(Request $request)
    {
        $schedule = Schedule::findOrFail($request->input('jadwal_id'));
        $lockService = app(ScheduleLockService::class);
        $agreementService = app(ScheduleAgreementService::class);

        if ($lockService->isAttendanceLocked($schedule)) {
            return response()->json([
                'success' => false,
                'message' => 'Absensi tidak dapat diubah karena jadwal pertemuan sudah selesai.',
            ], 422);
        }

        if (! $agreementService->allStudentsAgreed($schedule)) {
            return response()->json([
                'success' => false,
                'message' => 'Absensi hanya dapat diisi setelah guru dan siswa sepakat pada jadwal.',
            ], 422);
        }

        $data = $request->validate([
            'jadwal_id'   => 'required|exists:schedules,id',
            'absensi'     => 'required|array',
            'absensi.*.siswa_id' => 'required|exists:students,id',
            'absensi.*.status'   => 'required|in:hadir,izin,sakit,alpa',
            'catatan'     => 'nullable|string',
        ]);

        foreach ($data['absensi'] as $item) {
            DB::table('absensi_siswas')->updateOrInsert(
                ['jadwal_id' => $data['jadwal_id'], 'siswa_id' => $item['siswa_id']],
                ['status' => $item['status'], 'catatan' => $data['catatan'] ?? null, 'updated_at' => now(), 'created_at' => now()]
            );
        }

        return response()->json(['success' => true, 'message' => 'Absensi berhasil disimpan!']);
    }

    public function report(Request $request)
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();

        $report = DB::table('absensi_siswas as ab')
            ->join('students as s', 's.id', '=', 'ab.siswa_id')
            ->join('schedules as sch', 'sch.id', '=', 'ab.jadwal_id')
            ->when($teacher, fn($q) => $q->where('sch.guru_id', $teacher->id))
            ->when($request->bulan, fn($q) => $q->whereMonth('sch.tanggal', $request->bulan))
            ->when($request->tahun, fn($q) => $q->whereYear('sch.tanggal', $request->tahun))
            ->select('s.name', DB::raw("
                SUM(CASE WHEN ab.status='hadir' THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN ab.status='izin' THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN ab.status='sakit' THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN ab.status='alpa' THEN 1 ELSE 0 END) as alpa,
                COUNT(*) as total
            "))
            ->groupBy('s.name', 's.id')
            ->get();

        return response()->json(['success' => true, 'data' => $report]);
    }
}
