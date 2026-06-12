<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiSiswa;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\SchoolClass;
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
        // Load students from the class roster (class_students), not branch-wide
        $studentIds = DB::table('class_students')
            ->where('class_id', $schedule->kelas_id)
            ->pluck('student_id');

        $students = DB::table('students')
            ->whereIn('id', $studentIds)
            ->where('status', 'aktif')
            ->orderBy('name')
            ->get(['id', 'name', 'nis', 'photo']);

        // Existing attendance records for this schedule
        $existing = DB::table('absensi_siswas')
            ->where('jadwal_id', $schedule->id)
            ->get(['siswa_id', 'guru_hadir', 'siswa_konfirmasi_at', 'status'])
            ->keyBy('siswa_id');

        return response()->json([
            'success'  => true,
            'students' => $students,
            'existing' => $existing,
        ]);
    }

    public function store(Request $request)
    {
        $schedule = Schedule::findOrFail($request->input('jadwal_id'));

        $data = $request->validate([
            'jadwal_id'       => 'required|exists:schedules,id',
            'hadir_ids'       => 'nullable|array',
            'hadir_ids.*'     => 'integer|exists:students,id',
        ]);

        // All students in this class
        $allStudentIds = DB::table('class_students')
            ->where('class_id', $schedule->kelas_id)
            ->pluck('student_id')
            ->toArray();

        if (empty($allStudentIds)) {
            return response()->json(['success' => false, 'message' => 'Belum ada siswa di kelas ini.'], 422);
        }

        $hadirIds = array_map('intval', $data['hadir_ids'] ?? []);
        $now      = now();

        foreach ($allStudentIds as $studentId) {
            $guruHadir = in_array($studentId, $hadirIds);

            // Get existing record to preserve siswa_konfirmasi_at
            $existing = DB::table('absensi_siswas')
                ->where('jadwal_id', $schedule->id)
                ->where('siswa_id', $studentId)
                ->first();

            $siswaKonfirmasiAt = $existing?->siswa_konfirmasi_at;

            // If student already confirmed and guru is now un-marking them, preserve the record but mark invalid
            $status = AbsensiSiswa::computeStatus($guruHadir, $siswaKonfirmasiAt);

            if ($existing) {
                DB::table('absensi_siswas')
                    ->where('jadwal_id', $schedule->id)
                    ->where('siswa_id', $studentId)
                    ->update([
                        'guru_hadir'  => $guruHadir,
                        'status'      => $status,
                        'updated_at'  => $now,
                    ]);
            } else {
                DB::table('absensi_siswas')->insert([
                    'jadwal_id'  => $schedule->id,
                    'siswa_id'   => $studentId,
                    'guru_hadir' => $guruHadir,
                    'status'     => $status,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Absensi berhasil disimpan. Siswa yang ditandai hadir akan melihat tombol konfirmasi.']);
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
                SUM(CASE WHEN ab.status='menunggu_konfirmasi' THEN 1 ELSE 0 END) as menunggu,
                SUM(CASE WHEN ab.status='tidak_hadir' THEN 1 ELSE 0 END) as tidak_hadir,
                COUNT(*) as total
            "))
            ->groupBy('s.name', 's.id')
            ->get();

        return response()->json(['success' => true, 'data' => $report]);
    }
}
