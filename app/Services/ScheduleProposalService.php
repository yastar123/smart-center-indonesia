<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\ScheduleProposal;
use App\Models\ScheduleProposalApproval;
use App\Models\SchoolClass;
use Carbon\Carbon;

class ScheduleProposalService
{
    /**
     * Create a new proposal and generate approval records for all class members.
     */
    public function propose(SchoolClass $class, string $proposerType, int $proposerId, array $data): ScheduleProposal
    {
        $proposal = ScheduleProposal::create([
            'class_id'         => $class->id,
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

        return $proposal->load('approvals');
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
        // Check lock: cannot change if meeting starts in less than 1 hour
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

        // Reload approvals
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

        // Determine next meeting number
        $existingCount = Schedule::where('kelas_id', $class->id)->count();

        $schedule = Schedule::create([
            'kelas_id'     => $class->id,
            'guru_id'      => $class->guru_id,
            'cabang_id'    => $class->cabang_id,
            'pertemuan_ke' => $existingCount + 1,
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
