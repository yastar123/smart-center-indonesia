<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleStudentAgreement extends Model
{
    protected $fillable = [
        'schedule_id',
        'student_id',
        'guru_confirmed_at',
        'siswa_confirmed_at',
        'status',
    ];

    protected $casts = [
        'guru_confirmed_at'  => 'datetime',
        'siswa_confirmed_at' => 'datetime',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function refreshStatus(): void
    {
        if ($this->guru_confirmed_at && $this->siswa_confirmed_at) {
            $this->update(['status' => 'agreed']);
        }
    }

    public function isAgreed(): bool
    {
        return $this->status === 'agreed'
            || ($this->guru_confirmed_at && $this->siswa_confirmed_at);
    }
}
