<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\ScheduleProposal;
use App\Models\ScheduleProposalApproval;
use App\Models\SchoolClass;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScheduleProposalService
{
    /**
     * Return available meeting slots for a class.
     * Returns array of [ no => int, status => 'available'|'scheduled'|'done' ]
     */
    public function availableMeetings(SchoolClass $class): array
    {
        $total = (int) ($class->jumlah_pertemuan ?? 0);
        if ($total <= 0) {
            return [];
        }

        // Schedules already created for this class
        $schedules = Schedule::where('kelas_id', $class->id)
            ->withTrashed(false)
            ->get(['id', 'pertemuan_ke']);

        $scheduledMap  = []; // pertemuan_ke => schedule_id
        foreach ($schedules as $s) {
            if ($s->pertemuan_ke) {
                $scheduledMap[$s->pertemuan_ke] = $s->id;
            }
        }

        // Which of those schedules already have attendance?
        $doneIds = [];
        if (! empty($scheduledMap)) {
            $doneIds = DB::table('absensi_siswas')
                ->whereIn('jadwal_id', array_values($scheduledMap))
                ->pluck('jadwal_id')
                ->unique()
                ->toArray();
        }

        $result = [];
        for ($i = 1; $i <= $total; $i++) {
            if (isset($scheduledMap[$i])) {
                $isDone = in_array($scheduledMap[$i], $doneIds);
                $result[] = [
                    'no'     => $i,
                    'status' => $isDone ? 'done' : 'scheduled',
                ];
            } else {
                $result[] = [
                    'no'     => $i,
                    'status' => 'available',
                ];
            }
        }

        return $result;
    }

    /**
     * Create a new proposal and generate approval records for all class members.
     */
    public function propose(SchoolClass $class, string $proposerType, int $proposerId, array $data): array
    {
        $pertemuanKe = isset($data['pertemuan_ke']) ? (int) $data['pertemuan_ke'] : null;

        // Validate: if pertemuan_ke is given, check it's not already done (has attendance)
        if ($pertemuanKe) {
            $existing = Schedule::where('kelas_id', $class->id)
                ->where('pertemuan_ke', $pertemuanKe)
                ->first();

            if ($existing) {
                $hasDone = DB::table('absensi_siswas')
                    ->where('jadwal_id', $existing->id)
                    ->exists();

                if ($hasDone) {
                    return [
                        'success' => false,
                        'message' => "Pertemuan ke-{$pertemuanKe} sudah memiliki absensi dan tidak dapat dijadwalkan ulang.",
                    ];
                }
            }
        }

        $proposal = ScheduleProposal::create([
            'class_id'         => $class->id,
            'pertemuan_ke'     => $pertemuanKe,
            'proposed_by_type' => $proposerType,
            'proposed_by_id'   => $proposerId,
            'tanggal'          => $data['tanggal'],
            'jam_mulai'        => $data['jam_mulai'],
            'jam_selesai'      => $data['jam_selesai'],
            'jenis'            => $data['jenis'] ?? 'offline',
            'ruangan'          => $data['ruangan'] ?? null,
            'link_meeting'     => $data['link_meeting'] ?? null,
            'status'           => 'pending',
        ]);

        // Create approval record for the teacher
        if ($class->guru_id) {
            ScheduleProposalApproval::firstOrCreate([
                'proposal_id'   => $proposal->id,
                'approver_type' => 'guru',
                'approver_id'   => $class->guru_id,
            ], [
                'status' => 'pending',
            ]);
        }

        // Create approval records for all enrolled students
        $studentIds = $class->siswa()->pluck('students.id');
        foreach ($studentIds as $studentId) {
            ScheduleProposalApproval::firstOrCreate([
                'proposal_id'   => $proposal->id,
                'approver_type' => 'siswa',
                'approver_id'   => $studentId,
            ], [
                'status' => 'pending',
            ]);
        }

        // Auto-approve the proposer's own record
        $this->autoApproveProposer($proposal);

        return [
            'success'  => true,
            'proposal' => $proposal->load('approvals'),
        ];
    }

    private function autoApproveProposer(ScheduleProposal $proposal): void
    {
        ScheduleProposalApproval::where('proposal_id', $proposal->id)
            ->where('approver_type', $proposal->proposed_by_type)
            ->where('approver_id', $proposal->proposed_by_id)
            ->update(['status' => 'approved', 'responded_at' => now()]);
    }

    /**
     * Record a response (approved/rejected) from a participant.
     * If all approved → create schedule. If any rejected → mark rejected.
     */
    public function respond(ScheduleProposal $proposal, string $approverType, int $approverId, string $status): array
    {
        $sessionStart = Carbon::parse($proposal->tanggal->format('Y-m-d') . ' ' . $proposal->jam_mulai);
        if (now()->gte($sessionStart->subHour())) {
            return ['success' => false, 'message' => 'Jadwal sudah terkunci (H-1 jam sebelum pertemuan).'];
        }

        if ($proposal->status !== 'pending') {
            return ['success' => false, 'message' => 'Proposal ini sudah ' . $proposal->status . '.'];
        }

        $approval = ScheduleProposalApproval::where('proposal_id', $proposal->id)
            ->where('approver_type', $approverType)
            ->where('approver_id', $approverId)
            ->first();

        if (! $approval) {
            return ['success' => false, 'message' => 'Anda tidak termasuk dalam daftar peserta proposal ini.'];
        }

        $approval->update(['status' => $status, 'responded_at' => now()]);

        $proposal->load('approvals');

        if ($status === 'rejected') {
            $proposal->update(['status' => 'rejected']);
            return ['success' => true, 'message' => 'Jadwal ditolak.', 'proposal_status' => 'rejected'];
        }

        if ($proposal->allApproved()) {
            $schedule = $this->autoCreateSchedule($proposal);
            $proposal->update(['status' => 'approved', 'schedule_id' => $schedule->id]);
            return ['success' => true, 'message' => 'Semua pihak setuju! Jadwal otomatis dibuat.', 'proposal_status' => 'approved'];
        }

        return ['success' => true, 'message' => 'Persetujuan Anda telah direkam.', 'proposal_status' => 'pending'];
    }

    /**
     * Auto-create a Schedule record when all approvals are given.
     */
    public function autoCreateSchedule(ScheduleProposal $proposal): Schedule
    {
        $class = $proposal->kelas;

        // Use the chosen pertemuan_ke, or fall back to next available
        $pertemuanKe = $proposal->pertemuan_ke;
        if (! $pertemuanKe) {
            $pertemuanKe = Schedule::where('kelas_id', $class->id)->max('pertemuan_ke') + 1;
        }

        $schedule = Schedule::create([
            'kelas_id'     => $class->id,
            'guru_id'      => $class->guru_id,
            'cabang_id'    => $class->cabang_id,
            'pertemuan_ke' => $pertemuanKe,
            'tanggal'      => $proposal->tanggal,
            'jam_mulai'    => $proposal->jam_mulai,
            'jam_selesai'  => $proposal->jam_selesai,
            'jenis'        => $proposal->jenis,
            'ruangan'      => $proposal->ruangan,
            'link_meeting' => $proposal->link_meeting,
            'status'       => 'dijadwalkan',
        ]);

        // Sync schedule agreements for attendance tracking
        app(ScheduleAgreementService::class)->syncForSchedule($schedule);

        return $schedule;
    }
}
