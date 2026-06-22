<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\Branch;
use Illuminate\Http\Request;

class RiwayatSesiController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::with(['branch'])
            ->where('status', 'aktif')
            ->withCount([
                'schedules as sesi_selesai' => fn($q) => $q->where('status', 'selesai'),
                'schedules as sesi_total',
            ]);

        if (auth()->user()->hasRole('admin')) {
            $query->where('branch_id', auth()->user()->admin?->branch_id);
        }

        if ($s = $request->search) {
            $query->where('name', 'like', "%$s%");
        }
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }

        $teachers = $query->orderBy('name')->paginate(15)->appends($request->all());
        $branches = Branch::orderBy('name')->get();

        return view('admin.riwayat-sesi.index', compact('teachers', 'branches'));
    }

    public function show(Teacher $teacher)
    {
        $teacher->load('branch');

        $classes = SchoolClass::with(['mataPelajaran', 'siswa', 'cabang'])
            ->where('guru_id', $teacher->id)
            ->get();

        $classSchedules = Schedule::where('guru_id', $teacher->id)
            ->with('paket')
            ->get()
            ->groupBy('paket_id');

        $classData = $classes->map(function($kelas) use ($classSchedules) {
            $paketIds = \App\Models\Package::where('guru_id', $kelas->guru_id)
                ->pluck('id');

            $schedulesForClass = Schedule::whereIn('paket_id', $paketIds)
                ->get();

            $selesai = $schedulesForClass->where('status', 'selesai')->count();
            $total   = $schedulesForClass->count();
            $totalFromPackage = optional($kelas->siswa->first()?->package)->jumlah_pertemuan ?? $total;

            return [
                'kelas'     => $kelas,
                'selesai'   => $selesai,
                'total'     => $total,
                'target'    => $totalFromPackage,
                'progress'  => $totalFromPackage > 0 ? min(100, round($selesai / $totalFromPackage * 100)) : 0,
            ];
        });

        $recentSchedules = Schedule::where('guru_id', $teacher->id)
            ->with('paket')
            ->orderByDesc('tanggal')
            ->limit(20)
            ->get();

        $stats = [
            'total_kelas'  => $classes->count(),
            'sesi_selesai' => Schedule::where('guru_id', $teacher->id)->where('status', 'selesai')->count(),
            'sesi_total'   => Schedule::where('guru_id', $teacher->id)->count(),
            'total_siswa'  => $classes->sum(fn($k) => $k->siswa->count()),
        ];

        return view('admin.riwayat-sesi.show', compact('teacher', 'classData', 'recentSchedules', 'stats'));
    }
}
