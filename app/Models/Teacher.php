<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'branch_id', 'nig', 'name', 'gender',
        'birth_date', 'birth_place', 'address', 'phone',
        'email', 'photo', 'cv_path', 'subjects', 'status',
        'join_date', 'education', 'salary_base',
    ];

    protected $casts = [
        'birth_date'   => 'date',
        'join_date'    => 'date',
        'salary_base'  => 'decimal:2',
        'subjects'     => 'array',
    ];

    public function user()   { return $this->belongsTo(User::class); }
    public function branch() { return $this->belongsTo(Branch::class); }
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'teacher_courses', 'teacher_id', 'course_id')->withTimestamps();
    }
    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_teachers', 'teacher_id', 'student_id')->withTimestamps();
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=28a745&color=fff';
    }
}
