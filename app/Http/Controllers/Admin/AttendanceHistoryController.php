<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\SchoolClass;
use App\Models\Teacher;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isOwner = $user->hasRole('owner');

        // Scope by branch for admin, all branches for owner
        $branchId = $isOwner ? null : ($user->branch_id ?? null);

        $query = DB::table('absensi_siswas as ab')
            ->join('schedules as s', 's.id', '=', 'ab.jadwal_id')
            ->join('school_classes as sc', 'sc.id', '=', 's.kelas_id')
            ->join('students as st', 'st.id', '=', 'ab.siswa_id')
            ->join('courses as c', 'c.id', '=', 'sc.mata_pelajaran_id')
            ->leftJoin('teachers as t', 't.id', '=', 's.guru_id')
            ->when($branchId, fn($q) => $q->where('sc.cabang_id', $branchId))
            ->when($request->course_id, fn($q) => $q->where('sc.mata_pelajaran_id', $request->course_id))
            ->when($request->teacher_id, fn($q) => $q->where('s.guru_id', $request->teacher_id))
            ->when($request->status, fn($q) => $q->where('ab.status', $request->status))
            ->when($request->tanggal_from, fn($q) => $q->whereDate('s.tanggal', '>=', $request->tanggal_from))
            ->when($request->tanggal_to, fn($q) => $q->whereDate('s.tanggal', '<=', $request->tanggal_to))
            ->select([
                'ab.id',
                'ab.siswa_id',
                'st.name as siswa_name',
                'st.nis',
                'c.nama as mata_pelajaran',
                'sc.nama_kelas',
                't.name as guru_name',
                's.tanggal',
                's.jam_mulai',
                's.jam_selesai',
                's.pertemuan_ke',
                'ab.guru_hadir',
                'ab.siswa_konfirmasi_at',
                'ab.status',
                'ab.catatan',
            ])
            ->orderByDesc('s.tanggal')
            ->orderBy('st.name')
            ->paginate(50)
            ->withQueryString();

        // Filter options
        $courses = Course::when($branchId, fn($q) => $q->whereIn('id',
            SchoolClass::where('cabang_id', $branchId)->pluck('mata_pelajaran_id')
        ))->orderBy('nama')->get(['id', 'nama']);

        $teachers = Teacher::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->orderBy('name')->get(['id', 'name']);

        // Summary stats
        $summary = DB::table('absensi_siswas as ab')
            ->join('schedules as s', 's.id', '=', 'ab.jadwal_id')
            ->join('school_classes as sc', 'sc.id', '=', 's.kelas_id')
            ->when($branchId, fn($q) => $q->where('sc.cabang_id', $branchId))
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN ab.status='hadir' THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN ab.status='tidak_hadir' THEN 1 ELSE 0 END) as tidak_hadir,
                SUM(CASE WHEN ab.status='izin' THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN ab.status='sakit' THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN ab.status='alpa' THEN 1 ELSE 0 END) as alpa,
                SUM(CASE WHEN ab.status='menunggu_konfirmasi' THEN 1 ELSE 0 END) as menunggu
            ")
            ->first();

        return view('admin.attendance-history.index', compact(
            'query', 'courses', 'teachers', 'summary', 'branchId'
        ));
    }
}
