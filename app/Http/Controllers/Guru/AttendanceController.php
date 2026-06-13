<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiSiswa;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\User;
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
        try {
            // Primary: load from class_students pivot
            $studentIds = DB::table('class_students')
                ->where('class_id', $schedule->kelas_id)
                ->pluck('student_id');

            $students = collect();

            if ($studentIds->isNotEmpty()) {
                $students = DB::table('students')
                    ->join('users', 'users.id', '=', 'students.user_id')
                    ->whereIn('students.id', $studentIds)
                    ->where('students.status', 'aktif')
                    ->orderBy('students.name')
                    ->get([
                        'students.id',
                        'students.name',
                        'students.nis',
                        'students.photo',
                        'users.email',
                    ]);
            }

            // Fallback 1: find students by branch if class_students is empty
            if ($students->isEmpty() && $schedule->kelas_id) {
                $class = SchoolClass::find($schedule->kelas_id);
                if ($class && $class->cabang_id) {
                    $students = DB::table('students')
                        ->join('users', 'users.id', '=', 'students.user_id')
                        ->where('students.branch_id', $class->cabang_id)
                        ->where('students.status', 'aktif')
                        ->orderBy('students.name')
                        ->get([
                            'students.id',
                            'students.name',
                            'students.nis',
                            'students.photo',
                            'users.email',
                        ]);
                }
            }

            // Fallback 2: show empty — no student profiles exist for this branch.
            // We deliberately do NOT use User.id as a proxy for siswa_id because
            // absensi_siswas.siswa_id must reference students.id, not users.id.

            // Existing attendance records for this schedule
            $existing = DB::table('absensi_siswas')
                ->where('jadwal_id', $schedule->id)
                ->get(['siswa_id', 'guru_hadir', 'siswa_konfirmasi_at', 'status'])
                ->keyBy('siswa_id');

            return response()->json([
                'success'  => true,
                'students' => $students->values(),
                'existing' => $existing,
            ]);

        } catch (\Throwable $e) {
            \Log::error('getStudents error', ['schedule' => $schedule->id, 'msg' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $schedule = Schedule::findOrFail($request->input('jadwal_id'));

        $data = $request->validate([
            'jadwal_id'       => 'required|exists:schedules,id',
            'hadir_ids'       => 'nullable|array',
            'hadir_ids.*'     => 'integer',
        ]);

        $hadirIds = array_map('intval', $data['hadir_ids'] ?? []);

        // Collect all student IDs currently shown (from class_students + fallbacks)
        $studentIds = DB::table('class_students')
            ->where('class_id', $schedule->kelas_id)
            ->pluck('student_id')
            ->toArray();

        // If class_students is empty, use all students in the branch
        if (empty($studentIds) && $schedule->kelas_id) {
            $class = SchoolClass::find($schedule->kelas_id);
            if ($class && $class->cabang_id) {
                $studentIds = DB::table('students')
                    ->where('branch_id', $class->cabang_id)
                    ->where('status', 'aktif')
                    ->pluck('id')
                    ->toArray();
            }
        }

        // Merge hadir_ids into studentIds to include user proxies
        $allIds = array_unique(array_merge($studentIds, $hadirIds));

        if (empty($allIds)) {
            return response()->json(['success' => false, 'message' => 'Belum ada siswa di kelas ini.'], 422);
        }

        $now = now();

        foreach ($allIds as $siswaId) {
            $guruHadir = in_array($siswaId, $hadirIds);

            $existing = DB::table('absensi_siswas')
                ->where('jadwal_id', $schedule->id)
                ->where('siswa_id', $siswaId)
                ->first();

            if ($existing) {
                // Preserve siswa_konfirmasi_at, only update guru_hadir
                DB::table('absensi_siswas')
                    ->where('jadwal_id', $schedule->id)
                    ->where('siswa_id', $siswaId)
                    ->update([
                        'guru_hadir'  => $guruHadir,
                        'status'      => $this->computeStatus($guruHadir, $existing->siswa_konfirmasi_at),
                        'updated_at'  => $now,
                    ]);
            } else {
                DB::table('absensi_siswas')->insert([
                    'jadwal_id'           => $schedule->id,
                    'siswa_id'            => $siswaId,
                    'guru_hadir'          => $guruHadir,
                    'siswa_konfirmasi_at' => null,
                    'status'              => $guruHadir ? 'menunggu_konfirmasi' : 'tidak_hadir',
                    'catatan'             => null,
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Absensi berhasil disimpan!']);
    }

    private function computeStatus(bool $guruHadir, $siswaKonfirmasiAt): string
    {
        return \App\Models\AbsensiSiswa::computeStatus($guruHadir, $siswaKonfirmasiAt);
    }

    public function report(Request $request)
    {
        $user    = auth()->user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        $schedules = Schedule::with(['kelas', 'absensi'])
            ->when($teacher, fn($q) => $q->where('guru_id', $teacher->id))
            ->orderByDesc('tanggal')
            ->paginate(20);

        return view('guru.attendance.report', compact('schedules', 'teacher'));
    }

    // --- History methods (delegated to AttendanceHistoryController) ---
}
