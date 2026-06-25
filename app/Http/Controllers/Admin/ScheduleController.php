<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Package;
use App\Models\Branch;
use App\Models\Module;
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
        $modules  = Module::with('mataPelajaran')
            ->where('status', 'aktif')
            ->orderBy('judul')
            ->get();
        $teachers = \App\Models\Teacher::with(['branch', 'courses'])
            ->where('status', 'aktif')
            ->orderBy('name')
            ->get();
        $classes = \App\Models\SchoolClass::with(['mataPelajaran', 'cabang', 'guru'])
            ->where('status', 'aktif')
            ->orderBy('nama_kelas')
            ->get();
        return view('admin.schedules.create', compact('pakets', 'branches', 'modules', 'teachers', 'classes'));
    }

    public function index(Request $request)
    {
        $user      = auth()->user();
        $branchId  = $user->hasRole('admin') ? optional($user->admin)->branch_id : null;

        $pakets = Package::with(['guru', 'mataPelajaran', 'cabang'])
            ->where('status', 'aktif')
            ->when($branchId, fn($q) => $q->where('cabang_id', $branchId))
            ->orderBy('nama')
            ->get();

        $branches = $branchId
            ? Branch::where('id', $branchId)->get()
            : Branch::all();

        $teachers = \App\Models\Teacher::with('branch')
            ->where('status', 'aktif')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->orderBy('name')->get();

        $classes = \App\Models\SchoolClass::with(['mataPelajaran', 'cabang'])
            ->where('status', 'aktif')
            ->when($branchId, fn($q) => $q->where('cabang_id', $branchId))
            ->orderBy('nama_kelas')->get();

        $modules = Module::with('mataPelajaran')
            ->where('status', 'aktif')
            ->orderBy('judul')->get();

        $schedules = Schedule::with(['mataPelajaran', 'paket.guru', 'paket.mataPelajaran', 'paket.cabang', 'guru', 'cabang', 'kelas'])
            ->when($branchId, fn($q) => $q->where('cabang_id', $branchId))
            ->when($request->search, fn($q) =>
                $q->where('topik', 'like', "%{$request->search}%")
                  ->orWhere('ruangan', 'like', "%{$request->search}%")
                  ->orWhereHas('paket', fn($gq) =>
                      $gq->where('nama', 'like', "%{$request->search}%")))
            ->when($request->status,    fn($q) => $q->where('status',    $request->status))
            ->when($request->paket_id,  fn($q) => $q->where('paket_id',  $request->paket_id))
            ->when($request->cabang_id, fn($q) => $q->where('cabang_id', $request->cabang_id))
            ->when($request->tanggal,   fn($q) => $q->whereDate('tanggal', $request->tanggal))
            ->orderByDesc('tanggal')
            ->orderBy('pertemuan_ke')
            ->paginate(15)->withQueryString();

        $baseQ = fn() => Schedule::when($branchId, fn($q) => $q->where('cabang_id', $branchId));
        $stats = [
            'total'       => $baseQ()->count(),
            'hari_ini'    => $baseQ()->whereDate('tanggal', today())->count(),
            'dijadwalkan' => $baseQ()->where('status', 'dijadwalkan')->count(),
            'selesai'     => $baseQ()->where('status', 'selesai')->count(),
        ];

        return view('admin.schedule.index', compact('schedules', 'pakets', 'branches', 'teachers', 'classes', 'modules', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'paket_id'          => 'required|exists:packages,id',
            'mata_pelajaran_id' => 'required|exists:courses,id',
            'kelas_id'          => 'nullable|exists:school_classes,id',
            'module_id'         => 'nullable|exists:modules,id',
            'guru_id'           => 'required|exists:teachers,id',
            'jenis'             => 'required|in:online,offline,private',
            'pertemuan_ke'      => 'required|integer|min:1',
            'tanggal'           => 'required|date',
            'tanggal_selesai'   => 'nullable|date|after_or_equal:tanggal',
            'jam_mulai'         => 'required',
            'jam_selesai'       => 'required',
            'topik'             => 'nullable|string|max:200',
            'ruangan'           => 'nullable|string|max:100',
            'link_meeting'      => 'nullable|url|max:500',
            'catatan'           => 'nullable|string',
        ]);

        $paket = Package::with('cabang')->findOrFail($data['paket_id']);

        if ($data['pertemuan_ke'] > $paket->jumlah_pertemuan) {
            $msg = "Sesi ke-{$data['pertemuan_ke']} melebihi jumlah sesi paket ({$paket->jumlah_pertemuan}).";
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->withErrors(['pertemuan_ke' => $msg])->withInput();
        }

        // Cegah duplikat sesi pada paket yang sama
        $sudahAda = Schedule::where('paket_id', $data['paket_id'])
            ->where('pertemuan_ke', $data['pertemuan_ke'])
            ->whereNull('deleted_at')
            ->exists();
        if ($sudahAda) {
            $msg = "Sesi ke-{$data['pertemuan_ke']} untuk paket ini sudah pernah dijadwalkan.";
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->withErrors(['pertemuan_ke' => $msg])->withInput();
        }

        $data['cabang_id'] = $paket->cabang_id;
        // $data['jenis'] comes from the form (online/offline/private)
        $data['status']    = 'dijadwalkan';

        $schedule = Schedule::create($data);
        $schedule->load(['paket.guru', 'paket.mataPelajaran']);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Jadwal sesi berhasil ditambahkan.', 'data' => $schedule]);
        }
        return redirect()->route('admin.schedules.create')->with('success', 'Jadwal sesi berhasil ditambahkan!');
    }

    public function show(Request $request, Schedule $schedule)
    {
        $schedule->load(['mataPelajaran', 'paket.guru', 'paket.mataPelajaran', 'paket.cabang', 'guru', 'cabang', 'module']);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'data' => $schedule]);
        }

        $absensi = $schedule->absensi()->with('siswa')->orderBy('id')->get();

        return view('admin.schedules.show', compact('schedule', 'absensi'));
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
            'paket_id'          => 'required|exists:packages,id',
            'mata_pelajaran_id' => 'required|exists:courses,id',
            'guru_id'           => 'required|exists:teachers,id',
            'kelas_id'          => 'nullable|exists:school_classes,id',
            'jenis'             => 'required|in:online,offline,private',
            'pertemuan_ke'      => 'required|integer|min:1',
            'tanggal'           => 'required|date',
            'tanggal_selesai'   => 'nullable|date|after_or_equal:tanggal',
            'jam_mulai'         => 'required',
            'jam_selesai'       => 'required',
            'topik'             => 'nullable|string|max:200',
            'ruangan'           => 'nullable|string|max:100',
            'link_meeting'      => 'nullable|string|max:500',
            'status'            => 'required|in:dijadwalkan,berlangsung,selesai,dibatalkan',
            'catatan'           => 'nullable|string',
        ]);

        $paket = Package::with('cabang')->findOrFail($data['paket_id']);
        if ($data['pertemuan_ke'] > $paket->jumlah_pertemuan) {
            return response()->json(['success' => false, 'message' => "Sesi ke-{$data['pertemuan_ke']} melebihi jumlah sesi paket ({$paket->jumlah_pertemuan})."], 422);
        }

        $data['cabang_id'] = $paket->cabang_id;

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
