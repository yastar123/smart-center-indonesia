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

            // Fallback 1: students enrolled in the same package as this schedule
            if ($students->isEmpty() && $schedule->paket_id) {
                $students = DB::table('students')
                    ->join('users', 'users.id', '=', 'students.user_id')
                    ->where('students.package_id', $schedule->paket_id)
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

            // Fallback 2: find students by branch if still empty and kelas_id is set
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

            // Fallback 3: show empty — no student profiles exist.
            // We deliberately do NOT use User.id as a proxy for siswa_id because
            // absensi_siswas.siswa_id must reference students.id, not users.id.

            // Existing attendance records for this schedule
            $existing = DB::table('absensi_siswas')
                ->where('jadwal_id', $schedule->id)
                ->get(['siswa_id', 'guru_hadir', 'siswa_konfirmasi_at', 'status'])
                ->keyBy('siswa_id');

            // Attach sisa_sesi info for each student
            $studentIds = $students->pluck('id')->toArray();
            $sesiTerpakai = DB::table('absensi_siswas')
                ->whereIn('siswa_id', $studentIds)
                ->where('status', 'hadir')
                ->select('siswa_id', DB::raw('COUNT(*) as terpakai'))
                ->groupBy('siswa_id')
                ->pluck('terpakai', 'siswa_id');

            $totalSesi = DB::table('students')
                ->whereIn('id', $studentIds)
                ->pluck('total_sesi', 'id');

            $students = $students->map(function ($s) use ($sesiTerpakai, $totalSesi) {
                $total  = $totalSesi[$s->id] ?? 0;
                $pakai  = $sesiTerpakai[$s->id] ?? 0;
                $s->total_sesi = $total;
                $s->sesi_terpakai = $pakai;
                $s->sisa_sesi = max(0, $total - $pakai);
                return $s;
            });

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

        // Attendance only allowed during class hours
        $now = now();
        $classDate = \Carbon\Carbon::parse($schedule->tanggal)->format('Y-m-d');
        $startTime = \Carbon\Carbon::parse($classDate . ' ' . $schedule->jam_mulai);
        $endTime   = \Carbon\Carbon::parse($classDate . ' ' . $schedule->jam_selesai);

        if ($now->lt($startTime)) {
            return response()->json(['success' => false, 'message' => 'Absensi hanya bisa dilakukan saat kelas sudah dimulai (' . $startTime->format('H:i') . ' WIB).'], 422);
        }
        if ($now->gt($endTime)) {
            return response()->json(['success' => false, 'message' => 'Absensi sudah ditutup. Kelas telah selesai pada ' . $endTime->format('H:i') . ' WIB.'], 422);
        }

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

        // Fallback 1: students enrolled in the same package
        if (empty($studentIds) && $schedule->paket_id) {
            $studentIds = DB::table('students')
                ->where('package_id', $schedule->paket_id)
                ->where('status', 'aktif')
                ->pluck('id')
                ->toArray();
        }

        // Fallback 2: all students in branch if class_students and package both empty
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

        // Pre-load sisa sesi for all students in one query
        $sesiTerpakai = DB::table('absensi_siswas')
            ->whereIn('siswa_id', $allIds)
            ->where('status', 'hadir')
            ->select('siswa_id', DB::raw('COUNT(*) as terpakai'))
            ->groupBy('siswa_id')
            ->pluck('terpakai', 'siswa_id');

        $totalSesiMap = DB::table('students')
            ->whereIn('id', $allIds)
            ->pluck('total_sesi', 'id');

        $blockedNames = []; // students blocked due to 0 remaining sessions

        foreach ($allIds as $siswaId) {
            $guruHadir = in_array($siswaId, $hadirIds);

            // If guru tries to mark hadir but student has no remaining sessions, block it
            if ($guruHadir) {
                $total = $totalSesiMap[$siswaId] ?? 0;
                $pakai = $sesiTerpakai[$siswaId] ?? 0;
                $sisa  = max(0, $total - $pakai);

                if ($sisa <= 0) {
                    $guruHadir = false; // force tidak_hadir
                    $siswaName = DB::table('students')->where('id', $siswaId)->value('name') ?? "ID $siswaId";
                    $blockedNames[] = $siswaName . " (sesi habis: $pakai/$total)";
                }
            }

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

        $message = 'Absensi berhasil disimpan!';
        if (!empty($blockedNames)) {
            $message .= ' Catatan: siswa berikut tidak dapat ditandai hadir karena sesi sudah habis: ' . implode(', ', $blockedNames) . '.';
        }

        return response()->json(['success' => true, 'message' => $message]);
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
