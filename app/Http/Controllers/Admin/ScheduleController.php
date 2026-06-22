<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Package;
use App\Models\Branch;
use App\Services\ScheduleLockService;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function create()
    {
        $pakets = Package::with(['guru', 'mataPelajaran', 'cabang'])
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();
        $branches = Branch::all();
        return view('admin.schedules.create', compact('pakets', 'branches'));
    }

    public function index(Request $request)
    {
        $pakets = Package::with(['guru', 'mataPelajaran'])
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $branches = Branch::all();

        $schedules = Schedule::with(['paket.guru', 'paket.mataPelajaran', 'paket.cabang', 'guru', 'cabang'])
            ->when($request->search, fn($q) =>
                $q->where('topik', 'like', "%{$request->search}%")
                  ->orWhere('ruangan', 'like', "%{$request->search}%")
                  ->orWhereHas('paket', fn($gq) =>
                      $gq->where('nama', 'like', "%{$request->search}%")))
            ->when($request->status,   fn($q) => $q->where('status',   $request->status))
            ->when($request->paket_id, fn($q) => $q->where('paket_id', $request->paket_id))
            ->when($request->tanggal,  fn($q) => $q->whereDate('tanggal', $request->tanggal))
            ->orderByDesc('tanggal')
            ->orderBy('pertemuan_ke')
            ->paginate(12)->withQueryString();

        $stats = [
            'total'       => Schedule::count(),
            'hari_ini'    => Schedule::whereDate('tanggal', today())->count(),
            'dijadwalkan' => Schedule::where('status', 'dijadwalkan')->count(),
            'selesai'     => Schedule::where('status', 'selesai')->count(),
        ];

        return view('admin.schedules.index', compact('schedules', 'pakets', 'branches', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'paket_id'        => 'required|exists:packages,id',
            'pertemuan_ke'    => 'required|integer|min:1',
            'tanggal'         => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal',
            'jam_mulai'       => 'required',
            'jam_selesai'     => 'required',
            'topik'           => 'nullable|string|max:200',
            'ruangan'         => 'nullable|string|max:100',
            'link_meeting'    => 'nullable|string|max:500',
            'catatan'         => 'nullable|string',
        ]);

        $paket = Package::with('cabang')->findOrFail($data['paket_id']);

        if ($data['pertemuan_ke'] > $paket->jumlah_pertemuan) {
            return response()->json(['success' => false, 'message' => "Sesi ke-{$data['pertemuan_ke']} melebihi jumlah sesi paket ({$paket->jumlah_pertemuan})."], 422);
        }

        $data['guru_id']   = $paket->guru_id;
        $data['cabang_id'] = $paket->cabang_id;
        $data['jenis']     = $paket->jenis;
        $data['status']    = 'dijadwalkan';

        $schedule = Schedule::create($data);
        $schedule->load(['paket.guru', 'paket.mataPelajaran']);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Jadwal sesi berhasil ditambahkan.', 'data' => $schedule]);
        }
        return redirect()->route('admin.schedules.create')->with('success', 'Jadwal sesi berhasil ditambahkan!');
    }

    public function show(Schedule $schedule)
    {
        $schedule->load(['paket.guru', 'paket.mataPelajaran', 'paket.cabang', 'guru', 'cabang']);
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
            'paket_id'        => 'required|exists:packages,id',
            'pertemuan_ke'    => 'required|integer|min:1',
            'tanggal'         => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal',
            'jam_mulai'       => 'required',
            'jam_selesai'     => 'required',
            'topik'           => 'nullable|string|max:200',
            'ruangan'         => 'nullable|string|max:100',
            'link_meeting'    => 'nullable|string|max:500',
            'status'          => 'required|in:dijadwalkan,berlangsung,selesai,dibatalkan',
            'catatan'         => 'nullable|string',
        ]);

        $paket = Package::with('cabang')->findOrFail($data['paket_id']);
        if ($data['pertemuan_ke'] > $paket->jumlah_pertemuan) {
            return response()->json(['success' => false, 'message' => "Sesi ke-{$data['pertemuan_ke']} melebihi jumlah sesi paket ({$paket->jumlah_pertemuan})."], 422);
        }

        $data['guru_id']   = $paket->guru_id;
        $data['cabang_id'] = $paket->cabang_id;
        $data['jenis']     = $paket->jenis;

        $schedule->update($data);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Jadwal sesi berhasil diperbarui']);
        }
        return redirect()->route('admin.schedules.create')->with('success', 'Jadwal sesi berhasil diperbarui!');
    }

    public function edit(Schedule $schedule)
    {
        $schedule->load(['paket.guru', 'paket.mataPelajaran', 'paket.cabang', 'guru', 'cabang']);
        $pakets = Package::with(['guru', 'mataPelajaran', 'cabang'])
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();
        $branches = Branch::all();
        return view('admin.schedules.edit', compact('schedule', 'pakets', 'branches'));
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
