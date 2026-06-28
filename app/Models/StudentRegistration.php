<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_reg', 'name', 'phone', 'gender', 'education_level',
        'birth_place', 'birth_date', 'address', 'parent_name', 'parent_phone',
        'job', 'program', 'system', 'learning_place', 'pickup_mode', 'branch',
        'interests', 'interest_sessions', 'day_preferences', 'schedule_time', 'start_date', 'notes',
        'status', 'payment_status', 'academic_status',
        'assigned_teacher_id', 'biaya_per_sesi', 'total_sessions',
        'total_biaya', 'invoice_id', 'student_id',
    ];

    protected $casts = [
        'birth_date'        => 'date',
        'start_date'        => 'date',
        'interests'         => 'array',
        'interest_sessions' => 'array',
        'day_preferences'   => 'array',
        'biaya_per_sesi'    => 'decimal:2',
        'total_biaya'       => 'decimal:2',
    ];

    public function assignedTeacher()
    {
        return $this->belongsTo(Teacher::class, 'assigned_teacher_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
