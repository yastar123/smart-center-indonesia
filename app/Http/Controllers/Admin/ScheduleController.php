<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\Branch;
use App\Services\ScheduleLockService;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $classes  = SchoolClass::with(['cabang', 'guru', 'mataPelajaran'])->where('status', 'aktif')->orderBy('nama_kelas')->get();
        $branches = Branch::all();

        $schedules = Schedule::with(['kelas.guru', 'kelas.cabang', 'kelas.mataPelajaran'])
            ->when($request->search, fn($q) =>
                $q->where('topik', 'like', "%{$request->search}%")
                  ->orWhere('ruangan', 'like', "%{$request->search}%")
                  ->orWhereHas('kelas', fn($gq) =>
                      $gq->where('nama_kelas', 'like', "%{$request->search}%")))
            ->when($request->status,    fn($q) => $q->where('status',    $request->status))
            ->when($request->kelas_id,  fn($q) => $q->where('kelas_id',  $request->kelas_id))
            ->when($request->jenis,     fn($q) => $q->where('jenis',     $request->jenis))
            ->when($request->tanggal,   fn($q) => $q->whereDate('tanggal', $request->tanggal))
            ->orderByDesc('tanggal')
            ->orderBy('pertemuan_ke')
            ->paginate(12)->withQueryString();

        $stats = [
            'total'       => Schedule::count(),
            'hari_ini'    => Schedule::whereDate('tanggal', today())->count(),
            'dijadwalkan' => Schedule::where('status', 'dijadwalkan')->count(),
            'selesai'     => Schedule::where('status', 'selesai')->count(),
        ];

        return view('admin.schedules.index', compact('schedules', 'classes', 'branches', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kelas_id'        => 'required|exists:school_classes,id',
            'pertemuan_ke'    => 'required|integer|min:1',
            'tanggal'         => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal',
            'jam_mulai'       => 'required',
            'jam_selesai'     => 'required',
            'topik'           => 'nullable|string|max:200',
            'jenis'           => 'required|in:offline,online,private',
            'ruangan'         => 'nullable|string|max:100',
            'link_meeting'    => 'nullable|string|max:500',
            'catatan'         => 'nullable|string',
        ]);

        $kelas = SchoolClass::findOrFail($data['kelas_id']);

        if ($data['pertemuan_ke'] > $kelas->jumlah_pertemuan) {
            return response()->json(['success' => false, 'message' => "Pertemuan ke-{$data['pertemuan_ke']} melebihi jumlah pertemuan kelas ({$kelas->jumlah_pertemuan})."], 422);
        }

        $data['guru_id']   = $kelas->guru_id;
        $data['cabang_id'] = $kelas->cabang_id;
        $data['status']    = 'dijadwalkan';

        $schedule = Schedule::create($data);
        $schedule->load(['kelas.guru', 'kelas.cabang', 'kelas.mataPelajaran']);

        return response()->json(['success' => true, 'message' => 'Jadwal berhasil ditambahkan. Guru telah mendapat notifikasi.', 'data' => $schedule]);
    }

    public function show(Schedule $schedule)
    {
        $schedule->load(['kelas.guru', 'kelas.cabang', 'kelas.mataPelajaran']);
        return response()->json(['success' => true, 'data' => $schedule]);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $lockService = app(ScheduleLockService::class);
        if (! $lockService->canEditSchedule($schedule)) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal tidak dapat diubah (H-1 jam sebelum pertemuan atau sudah selesai).',
            ], 422);
        }

        $data = $request->validate([
            'kelas_id'        => 'required|exists:school_classes,id',
            'pertemuan_ke'    => 'required|integer|min:1',
            'tanggal'         => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal',
            'jam_mulai'       => 'required',
            'jam_selesai'     => 'required',
            'topik'           => 'nullable|string|max:200',
            'jenis'           => 'required|in:offline,online,private',
            'ruangan'         => 'nullable|string|max:100',
            'link_meeting'    => 'nullable|string|max:500',
            'status'          => 'required|in:dijadwalkan,berlangsung,selesai,dibatalkan',
            'catatan'         => 'nullable|string',
        ]);

        $kelas = SchoolClass::findOrFail($data['kelas_id']);
        if ($data['pertemuan_ke'] > $kelas->jumlah_pertemuan) {
            return response()->json(['success' => false, 'message' => "Pertemuan ke-{$data['pertemuan_ke']} melebihi jumlah pertemuan kelas ({$kelas->jumlah_pertemuan})."], 422);
        }

        $data['guru_id']   = $kelas->guru_id;
        $data['cabang_id'] = $kelas->cabang_id;

        $schedule->update($data);

        return response()->json(['success' => true, 'message' => 'Jadwal berhasil diperbarui']);
    }

    public function destroy(Schedule $schedule)
    {
        $lockService = app(ScheduleLockService::class);
        if (! $lockService->canEditSchedule($schedule)) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal tidak dapat dihapus (H-1 jam sebelum pertemuan atau sudah selesai).',
            ], 422);
        }

        $schedule->delete();
        return response()->json(['success' => true, 'message' => 'Jadwal berhasil dihapus']);
    }
}
