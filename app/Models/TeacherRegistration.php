<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_reg',
        'name',
        'nig',
        'gender',
        'birth_date',
        'education',
        'phone',
        'email',
        'branch_id',
        'branch',
        'address',
        'jenis_guru',
        'salary_base',
        'course_ids',
        'cv_path',
        'notes',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'course_ids' => 'array',
        'salary_base' => 'decimal:2',
    ];
}
