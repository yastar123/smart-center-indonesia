<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleProposal extends Model
{
    protected $fillable = [
        'class_id',
        'pertemuan_ke',
        'proposed_by_type',
        'proposed_by_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'jenis',
        'ruangan',
        'link_meeting',
        'status',
        'schedule_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function kelas()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function approvals()
    {
        return $this->hasMany(ScheduleProposalApproval::class, 'proposal_id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function proposer()
    {
        if ($this->proposed_by_type === 'guru') {
            return Teacher::find($this->proposed_by_id);
        }
        return Student::find($this->proposed_by_id);
    }

    public function proposerName(): string
    {
        $p = $this->proposer();
        return $p ? $p->name : '—';
    }

    public function allApproved(): bool
    {
        $approvals = $this->approvals;
        if ($approvals->isEmpty()) return false;
        return $approvals->every(fn ($a) => $a->status === 'approved');
    }

    public function anyRejected(): bool
    {
        return $this->approvals->contains(fn ($a) => $a->status === 'rejected');
    }

    public function pendingCount(): int
    {
        return $this->approvals->where('status', 'pending')->count();
    }

    public function approvedCount(): int
    {
        return $this->approvals->where('status', 'approved')->count();
    }
}
