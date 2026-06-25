<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Services\ScheduleLockService;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Teacher::where('user_id', auth()->id())->firstOrFail();

        $schedules = Schedule::with(['kelas.mataPelajaran', 'kelas.cabang', 'paket'])
            ->where('guru_id', $teacher->id)
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->bulan, fn($q) => $q->whereMonth('tanggal', $request->bulan))
            ->when($request->tahun, fn($q) => $q->whereYear('tanggal', $request->tahun))
            ->orderByDesc('tanggal')
            ->orderBy('jam_mulai')
            ->paginate(15)->withQueryString();

        return view('guru.schedules.index', compact('schedules', 'teacher'));
    }

    public function show(Schedule $schedule)
    {
        $teacher = Teacher::where('user_id', auth()->id())->firstOrFail();
        if ($schedule->guru_id !== $teacher->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }
        $schedule->load(['kelas.mataPelajaran', 'kelas.cabang', 'paket']);
        return response()->json(['success' => true, 'data' => $schedule]);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $teacher = Teacher::where('user_id', auth()->id())->firstOrFail();
        if ($schedule->guru_id !== $teacher->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $lockService = app(ScheduleLockService::class);
        if ($lockService->isScheduleLocked($schedule)) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal tidak dapat diubah (H-1 jam sebelum pertemuan).',
            ], 422);
        }

        $data = $request->validate([
            'tanggal'      => 'required|date',
            'jam_mulai'    => 'required',
            'jam_selesai'  => 'required',
            'jenis'        => 'required|in:offline,online,private',
            'ruangan'      => 'nullable|string|max:100',
            'link_meeting' => 'nullable|string|max:500',
            'topik'        => 'nullable|string|max:200',
            'catatan'      => 'nullable|string',
        ]);

        $schedule->update($data);

        return response()->json(['success' => true, 'message' => 'Jadwal berhasil diperbarui.']);
    }
}
