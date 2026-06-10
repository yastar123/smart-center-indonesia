<?php

namespace App\Services;

use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleLockService
{
    public function sessionStart(Schedule $schedule): Carbon
    {
        $date = $schedule->tanggal instanceof Carbon
            ? $schedule->tanggal->format('Y-m-d')
            : Carbon::parse($schedule->tanggal)->format('Y-m-d');

        return Carbon::parse($date . ' ' . $schedule->jam_mulai);
    }

    public function sessionEnd(Schedule $schedule): Carbon
    {
        $date = $schedule->tanggal instanceof Carbon
            ? $schedule->tanggal->format('Y-m-d')
            : Carbon::parse($schedule->tanggal)->format('Y-m-d');

        return Carbon::parse($date . ' ' . $schedule->jam_selesai);
    }

    /** Jadwal tidak bisa diubah H-1 jam sebelum mulai */
    public function isScheduleLocked(Schedule $schedule): bool
    {
        return now()->gte($this->sessionStart($schedule)->subHour());
    }

    /** Absensi tidak bisa diubah setelah pertemuan selesai */
    public function isAttendanceLocked(Schedule $schedule): bool
    {
        return now()->gt($this->sessionEnd($schedule));
    }

    public function canEditSchedule(Schedule $schedule): bool
    {
        return ! $this->isScheduleLocked($schedule) && $schedule->status !== 'selesai';
    }

    public function canEditAttendance(Schedule $schedule): bool
    {
        return ! $this->isAttendanceLocked($schedule);
    }
}
