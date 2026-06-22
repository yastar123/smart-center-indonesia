<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Schedule;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::select('teachers.*')
            ->selectRaw('(SELECT COUNT(*) FROM schedules WHERE schedules.guru_id = teachers.id AND schedules.deleted_at IS NULL) as total_sesi')
            ->selectRaw('(SELECT COUNT(*) FROM schedules WHERE schedules.guru_id = teachers.id AND schedules.deleted_at IS NULL AND schedules.status = \'selesai\') as sesi_selesai')
            ->with(['branch']);

        if (auth()->user()->hasRole('admin')) {
            $query->where('branch_id', auth()->user()->admin?->branch_id);
        }

        if ($s = $request->search) {
            $query->where('name', 'like', "%$s%");
        }
        if ($request->cabang_id) {
            $query->where('branch_id', $request->cabang_id);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $teachers = $query->orderBy('name')->paginate(20)->appends($request->all());
        $branches = Branch::orderBy('name')->get();

        return view('admin.riwayat-guru.index', compact('teachers', 'branches'));
    }

    public function show(Request $request, Teacher $teacher)
    {
        $teacher->load(['branch']);

        $scheduleQuery = Schedule::with(['paket', 'kelas', 'cabang'])
            ->where('guru_id', $teacher->id);

        if ($request->status) {
            $scheduleQuery->where('status', $request->status);
        }
        if ($request->paket_id) {
            $scheduleQuery->where('paket_id', $request->paket_id);
        }

        $schedules = $scheduleQuery->orderByDesc('tanggal')->paginate(20)->appends($request->all());

        $stats = [
            'total'       => Schedule::where('guru_id', $teacher->id)->count(),
            'selesai'     => Schedule::where('guru_id', $teacher->id)->where('status', 'selesai')->count(),
            'dijadwalkan' => Schedule::where('guru_id', $teacher->id)->where('status', 'dijadwalkan')->count(),
            'paket_count' => Schedule::where('guru_id', $teacher->id)->distinct('paket_id')->count('paket_id'),
        ];

        $pakets = Schedule::where('guru_id', $teacher->id)
            ->with('paket')
            ->select('paket_id')
            ->distinct()
            ->get()
            ->pluck('paket')
            ->filter()
            ->values();

        return view('admin.riwayat-guru.show', compact('teacher', 'schedules', 'stats', 'pakets'));
    }
}
