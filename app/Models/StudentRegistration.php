<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_reg',
        'name',
        'phone',
        'gender',
        'education_level',
        'birth_place',
        'birth_date',
        'address',
        'parent_name',
        'parent_phone',
        'job',
        'program',
        'system',
        'learning_place',
        'pickup_mode',
        'branch',
        'interests',
        'day_preferences',
        'schedule_time',
        'start_date',
        'notes',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'start_date' => 'date',
        'interests' => 'array',
        'day_preferences' => 'array',
    ];
}
