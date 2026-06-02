<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'city',
        'regency',
        'email',
        'password',
        'status',

        'can_students',
        'can_teachers',
        'can_schedules',
        'can_payments',
        'can_tryouts',
    ];

    protected $casts = [
        'can_students' => 'boolean',
        'can_teachers' => 'boolean',
        'can_schedules' => 'boolean',
        'can_payments' => 'boolean',
        'can_tryouts' => 'boolean',
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}