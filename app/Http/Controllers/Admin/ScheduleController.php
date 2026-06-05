<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Branch;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $teachers = Teacher::where('status', 'aktif')->orderBy('name')->get();
        $classes  = SchoolClass::orderBy('id')->get();
        $branches = Branch::all();

        $schedules = Schedule::with(['guru', 'kelas', 'cabang'])
            ->when($request->search, fn($q) =>
                $q->where('topik', 'like', "%{$request->search}%")
                  ->orWhere('ruangan', 'like', "%{$request->search}%")
                  ->orWhereHas('guru', fn($gq) =>
                      $gq->where('name', 'like', "%{$request->search}%")))
            ->when($request->status,    fn($q) => $q->where('status',    $request->status))
            ->when($request->branch_id, fn($q) => $q->where('cabang_id', $request->branch_id))
            ->when($request->jenis,     fn($q) => $q->where('jenis',     $request->jenis))
            ->when($request->tanggal,   fn($q) => $q->whereDate('tanggal', $request->tanggal))
            ->orderByDesc('tanggal')
            ->orderBy('jam_mulai')
            ->paginate(12)->withQueryString();

        $stats = [
            'total'       => Schedule::count(),
            'hari_ini'    => Schedule::whereDate('tanggal', today())->count(),
            'dijadwalkan' => Schedule::where('status', 'dijadwalkan')->count(),
            'selesai'     => Schedule::where('status', 'selesai')->count(),
        ];

        return view('admin.schedules.index', compact('schedules', 'teachers', 'classes', 'branches', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'guru_id'     => 'required|exists:teachers,id',
            'cabang_id'   => 'required|exists:branches,id',
            'kelas_id'    => 'nullable|exists:school_classes,id',
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required',
            'topik'       => 'nullable|string|max:200',
            'jenis'       => 'required|in:offline,online',
            'ruangan'     => 'nullable|string|max:100',
            'link_meeting'=> 'nullable|string|max:500',
            'catatan'     => 'nullable|string',
        ]);

        $data['status'] = 'dijadwalkan';
        $schedule = Schedule::create($data);
        $schedule->load(['guru', 'kelas', 'cabang']);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil ditambahkan',
            'data'    => $schedule,
        ]);
    }

    public function show(Schedule $schedule)
    {
        $schedule->load(['guru', 'kelas', 'cabang']);
        return response()->json(['success' => true, 'data' => $schedule]);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $data = $request->validate([
            'guru_id'     => 'required|exists:teachers,id',
            'cabang_id'   => 'required|exists:branches,id',
            'kelas_id'    => 'nullable|exists:school_classes,id',
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required',
            'topik'       => 'nullable|string|max:200',
            'jenis'       => 'required|in:offline,online',
            'ruangan'     => 'nullable|string|max:100',
            'link_meeting'=> 'nullable|string|max:500',
            'status'      => 'required|in:dijadwalkan,berlangsung,selesai,dibatalkan',
            'catatan'     => 'nullable|string',
        ]);

        $schedule->update($data);

        return response()->json(['success' => true, 'message' => 'Jadwal berhasil diperbarui']);
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return response()->json(['success' => true, 'message' => 'Jadwal berhasil dihapus']);
    }
}
