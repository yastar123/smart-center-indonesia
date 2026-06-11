<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleProposalApproval extends Model
{
    protected $fillable = [
        'proposal_id',
        'approver_type',
        'approver_id',
        'status',
        'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    public function proposal()
    {
        return $this->belongsTo(ScheduleProposal::class, 'proposal_id');
    }

    public function approverName(): string
    {
        if ($this->approver_type === 'guru') {
            $t = Teacher::find($this->approver_id);
            return $t ? $t->name : 'Guru';
        }
        $s = Student::find($this->approver_id);
        return $s ? $s->name : 'Siswa';
    }
}
