<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\AbsensiSiswa;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Branch;
use Illuminate\Http\Request;

class AdminAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user     = auth()->user();
        $isOwner  = $user->hasRole('owner');
        $branchId = $isOwner ? null : ($user->branch_id ?? null);

        $query = Schedule::with([
            'paket.mataPelajaran',
            'paket.guru',
            'guru',
            'cabang',
            'absensi',
        ])
        ->when($branchId, fn($q) => $q->where('cabang_id', $branchId))
        ->when($request->status,    fn($q) => $q->where('status', $request->status))
        ->when($request->guru_id,   fn($q) => $q->where('guru_id', $request->guru_id))
        ->when($request->tanggal,   fn($q) => $q->whereDate('tanggal', $request->tanggal))
        ->when($request->paket_id,  fn($q) => $q->where('paket_id', $request->paket_id))
        ->orderByDesc('tanggal')
        ->orderBy('pertemuan_ke')
        ->paginate(20)->withQueryString();

        $teachers = Teacher::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->orderBy('name')->get(['id', 'name']);

        $pakets = \App\Models\Package::with('mataPelajaran')
            ->when($branchId, fn($q) => $q->where('cabang_id', $branchId))
            ->orderBy('nama')->get(['id', 'nama', 'jenis']);

        $stats = [
            'total'    => Schedule::when($branchId, fn($q) => $q->where('cabang_id', $branchId))->count(),
            'selesai'  => Schedule::when($branchId, fn($q) => $q->where('cabang_id', $branchId))->where('status', 'selesai')->count(),
            'hadir'    => AbsensiSiswa::whereHas('jadwal', fn($q) => $branchId ? $q->where('cabang_id', $branchId) : $q)->where('status', 'hadir')->count(),
            'alpa'     => AbsensiSiswa::whereHas('jadwal', fn($q) => $branchId ? $q->where('cabang_id', $branchId) : $q)->where('status', 'alpa')->count(),
        ];

        return view('admin.attendance.index', compact('query', 'teachers', 'pakets', 'stats', 'isOwner', 'branchId'));
    }

    public function show(Schedule $schedule)
    {
        $schedule->load([
            'paket.mataPelajaran',
            'paket.guru',
            'guru',
            'cabang',
            'absensi.siswa',
        ]);

        return response()->json([
            'success' => true,
            'data'    => [
                'schedule' => $schedule,
                'absensi'  => $schedule->absensi->map(fn($ab) => [
                    'id'         => $ab->id,
                    'siswa_id'   => $ab->siswa_id,
                    'siswa_name' => $ab->siswa?->name ?? '–',
                    'siswa_nis'  => $ab->siswa?->nis ?? '–',
                    'guru_hadir' => $ab->guru_hadir,
                    'status'     => $ab->status,
                    'catatan'    => $ab->catatan,
                    'konfirmasi_at' => $ab->siswa_konfirmasi_at,
                ]),
            ],
        ]);
    }

    public function update(Request $request, AbsensiSiswa $absensi)
    {
        $data = $request->validate([
            'status'  => 'required|in:hadir,tidak_hadir,izin,sakit,alpa,menunggu_konfirmasi',
            'catatan' => 'nullable|string|max:500',
        ]);

        $absensi->update($data);

        return response()->json(['success' => true, 'message' => 'Absensi berhasil diperbarui.']);
    }

    public function bulkUpdate(Request $request, Schedule $schedule)
    {
        $request->validate([
            'absensi'          => 'required|array',
            'absensi.*.id'     => 'required|exists:absensi_siswas,id',
            'absensi.*.status' => 'required|in:hadir,tidak_hadir,izin,sakit,alpa,menunggu_konfirmasi',
            'absensi.*.catatan'=> 'nullable|string|max:500',
        ]);

        foreach ($request->absensi as $item) {
            AbsensiSiswa::where('id', $item['id'])
                ->where('jadwal_id', $schedule->id)
                ->update([
                    'status'  => $item['status'],
                    'catatan' => $item['catatan'] ?? null,
                ]);
        }

        return response()->json(['success' => true, 'message' => 'Absensi berhasil disimpan.']);
    }
}
