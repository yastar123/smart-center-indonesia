<?php

namespace App\Services;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

    /**
     * Absensi terkunci PERMANEN setelah disimpan pertama kali,
     * atau setelah waktu pertemuan selesai.
     */
    public function isAttendanceLocked(Schedule $schedule): bool
    {
        // Locked by time
        if (now()->gt($this->sessionEnd($schedule))) {
            return true;
        }

        // Permanently locked if any attendance record already exists
        $hasAttendance = DB::table('absensi_siswas')
            ->where('jadwal_id', $schedule->id)
            ->exists();

        return $hasAttendance;
    }

    /** True if this schedule's meeting slot already has saved attendance */
    public function hasAttendance(Schedule $schedule): bool
    {
        return DB::table('absensi_siswas')
            ->where('jadwal_id', $schedule->id)
            ->exists();
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
