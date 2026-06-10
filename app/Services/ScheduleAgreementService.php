<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\ScheduleStudentAgreement;
use App\Models\SchoolClass;

class ScheduleAgreementService
{
    public function syncForSchedule(Schedule $schedule): void
    {
        $kelas = $schedule->kelas ?? SchoolClass::find($schedule->kelas_id);
        if (! $kelas) {
            return;
        }

        $studentIds = $kelas->siswa()->pluck('students.id');

        foreach ($studentIds as $studentId) {
            ScheduleStudentAgreement::firstOrCreate(
                ['schedule_id' => $schedule->id, 'student_id' => $studentId],
                ['status' => 'pending']
            );
        }
    }

    public function guruConfirm(Schedule $schedule, int $studentId): array
    {
        $agreement = ScheduleStudentAgreement::where('schedule_id', $schedule->id)
            ->where('student_id', $studentId)
            ->firstOrFail();

        if (app(ScheduleLockService::class)->isScheduleLocked($schedule)) {
            return ['success' => false, 'message' => 'Jadwal sudah terkunci (H-1 jam sebelum pertemuan).'];
        }

        $agreement->update(['guru_confirmed_at' => now()]);
        $agreement->refreshStatus();

        return ['success' => true, 'message' => 'Konfirmasi jadwal berhasil.', 'agreement' => $agreement->fresh()];
    }

    public function siswaConfirm(Schedule $schedule, int $studentId): array
    {
        $agreement = ScheduleStudentAgreement::where('schedule_id', $schedule->id)
            ->where('student_id', $studentId)
            ->firstOrFail();

        if (app(ScheduleLockService::class)->isScheduleLocked($schedule)) {
            return ['success' => false, 'message' => 'Jadwal sudah terkunci (H-1 jam sebelum pertemuan).'];
        }

        $agreement->update(['siswa_confirmed_at' => now()]);
        $agreement->refreshStatus();

        return ['success' => true, 'message' => 'Konfirmasi jadwal berhasil.', 'agreement' => $agreement->fresh()];
    }

    public function isAgreedForStudent(Schedule $schedule, int $studentId): bool
    {
        $agreement = ScheduleStudentAgreement::where('schedule_id', $schedule->id)
            ->where('student_id', $studentId)
            ->first();

        return $agreement ? $agreement->isAgreed() : false;
    }

    public function allStudentsAgreed(Schedule $schedule): bool
    {
        $agreements = ScheduleStudentAgreement::where('schedule_id', $schedule->id)->get();
        if ($agreements->isEmpty()) {
            return false;
        }

        return $agreements->every(fn ($a) => $a->isAgreed());
    }
}
