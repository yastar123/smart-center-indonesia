<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduleProposal;
use App\Models\ScheduleProposalApproval;
use App\Models\SchoolClass;
use App\Models\Teacher;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RescheduleController extends Controller
{
    public function index(Request $request)
    {
        $proposals = ScheduleProposal::with([
            'kelas.guru',
            'kelas.mataPelajaran',
            'approvals',
            'schedule',
        ])
        ->when($request->status, fn($q) => $q->where('status', $request->status))
        ->latest()
        ->paginate(15)
        ->appends($request->all());

        $teachers = Teacher::where('status', 'aktif')->orderBy('name')->get();

        $availability = $teachers->take(6)->map(function ($t) {
            $days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
            $dayNames = [1 => 'mon', 2 => 'tue', 3 => 'wed', 4 => 'thu', 5 => 'fri', 6 => 'sat'];
            $avail = ['teacher' => $t->name, 'subject' => implode(', ', (array)($t->subjects ?? []))];

            foreach ($days as $day) {
                $avail[$day] = 'OFF';
            }

            $schedules = Schedule::where('guru_id', $t->id)
                ->where('tanggal', '>=', Carbon::today())
                ->where('tanggal', '<=', Carbon::today()->addDays(7))
                ->get();

            foreach ($schedules as $s) {
                $dow = Carbon::parse($s->tanggal)->dayOfWeekIso;
                $key = $dayNames[$dow] ?? null;
                if ($key) {
                    $avail[$key] = $s->jam_mulai . '-' . $s->jam_selesai;
                }
            }

            return $avail;
        });

        $stats = [
            'total'    => ScheduleProposal::count(),
            'pending'  => ScheduleProposal::where('status', 'pending')->count(),
            'approved' => ScheduleProposal::where('status', 'approved')->count(),
            'rejected' => ScheduleProposal::where('status', 'rejected')->count(),
        ];

        return view('admin.reschedule.index', compact('proposals', 'availability', 'stats'));
    }

    public function approve(Request $request, ScheduleProposal $proposal)
    {
        if ($proposal->status !== 'pending') {
            return back()->with('error', 'Proposal ini sudah diproses.');
        }

        $proposal->update(['status' => 'approved']);

        if ($proposal->schedule_id) {
            $schedule = Schedule::find($proposal->schedule_id);
            if ($schedule) {
                $schedule->update([
                    'tanggal'    => $proposal->tanggal,
                    'jam_mulai'  => $proposal->jam_mulai,
                    'jam_selesai'=> $proposal->jam_selesai,
                    'ruangan'    => $proposal->ruangan,
                ]);
            }
        }

        return back()->with('success', 'Reschedule berhasil disetujui.');
    }

    public function reject(Request $request, ScheduleProposal $proposal)
    {
        if ($proposal->status !== 'pending') {
            return back()->with('error', 'Proposal ini sudah diproses.');
        }

        $proposal->update(['status' => 'rejected']);

        return back()->with('success', 'Reschedule telah ditolak.');
    }
}
