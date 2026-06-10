<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentCoursePayment extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'amount',
        'proof',
        'catatan',
        'status',
        'rejected_reason',
        'verified_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
