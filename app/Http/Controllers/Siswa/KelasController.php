<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ScheduleProposal;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (!$student) {
            return redirect()->route('siswa.dashboard')->with('error', 'Profil siswa belum lengkap.');
        }

        $classes = $student->schoolClasses()
            ->with(['guru', 'jadwal', 'mataPelajaran'])
            ->get();

        $now      = Carbon::now();
        $dayNames = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $classIds = $classes->pluck('id');

        // Pending proposals proposed by guru — siswa needs to approve/reject
        $pendingProposalsAll = ScheduleProposal::whereIn('class_id', $classIds)
            ->where('status', 'pending')
            ->where('proposed_by_type', 'guru')
            ->with('approvals')
            ->get()
            ->groupBy('class_id');

        $transformed = $classes->map(function ($kelas) use ($now, $dayNames, $pendingProposalsAll) {
            $jadwal  = $kelas->jadwal;
            $done    = $jadwal->where('status', 'selesai')->count();
            $total   = $kelas->jumlah_pertemuan ?: $jadwal->count();

            $proposals = $pendingProposalsAll->get($kelas->id, collect());

            // Determine ongoing session
            $isOngoing = $jadwal->first(function ($j) use ($now) {
                if (!$j->tanggal) return false;
                $start = Carbon::parse($j->tanggal->format('Y-m-d') . ' ' . $j->jam_mulai);
                $end   = Carbon::parse($j->tanggal->format('Y-m-d') . ' ' . $j->jam_selesai);
                return $now->between($start, $end);
            });

            // Computed status
            if ($total > 0 && $done >= $total) {
                $status = 'selesai';
            } elseif ($proposals->isNotEmpty()) {
                $status = 'proposed';
            } elseif ($kelas->status === 'draft') {
                $status = 'menunggu_konfirmasi';
            } elseif ($isOngoing) {
                $status = 'berlangsung';
            } else {
                $status = 'menunggu';
            }

            // Next upcoming schedule
            $today      = $now->copy()->startOfDay();
            $nextJadwal = $jadwal
                ->where('status', '!=', 'selesai')
                ->filter(fn ($j) => $j->tanggal && Carbon::parse($j->tanggal->format('Y-m-d'))->gte($today))
                ->sortBy('tanggal')
                ->first();

            // Build next_info array for the view
            $nextInfo = null;
            if ($status === 'berlangsung') {
                $nextInfo = ['type' => 'berlangsung'];
            } elseif ($status === 'proposed' && $proposals->isNotEmpty()) {
                $slots = $proposals->map(function ($p) use ($dayNames) {
                    $dayIdx = $p->tanggal ? ($p->tanggal->dayOfWeekIso - 1) : 0;
                    return ($dayNames[$dayIdx] ?? '') . ', ' . substr($p->jam_mulai, 0, 5);
                })->values()->all();
                $nextInfo = ['type' => 'proposed', 'slots' => $slots];
            } elseif ($nextJadwal && $nextJadwal->tanggal) {
                $dayIdx  = $nextJadwal->tanggal->dayOfWeekIso - 1;
                $nextInfo = ['type' => 'jadwal', 'text' => ($dayNames[$dayIdx] ?? '') . ', ' . substr($nextJadwal->jam_mulai, 0, 5)];
            }

            // Subject name & initials
            $subjectName = $kelas->mataPelajaran?->nama ?? '—';
            $initials    = strtoupper(mb_substr(preg_replace('/[^A-Za-z]/', '', $subjectName), 0, 2));
            if (strlen($initials) < 2) {
                $initials = strtoupper(mb_substr($kelas->nama_kelas, 0, 2));
            }

            $tipeLabel = in_array(strtolower($kelas->jenis ?? ''), ['private', 'privat'])
                ? 'PRIVAT' : 'REGULER';

            return [
                'id'           => $kelas->id,
                'nama_kelas'   => $kelas->nama_kelas,
                'subject_name' => $subjectName,
                'initials'     => $initials,
                'tipe_label'   => $tipeLabel,
                'jenis'        => $kelas->jenis,
                'done'         => $done,
                'total'        => $total,
                'next_info'    => $nextInfo,
                'status'       => $status,
                'proposals'    => $proposals,
            ];
        });

        // Manual pagination
        $perPage  = 10;
        $page     = max(1, (int) $request->get('page', 1));
        $items    = $transformed->forPage($page, $perPage)->values();
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $transformed->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('siswa.kelas.index', compact('paginator', 'student'));
    }
}
