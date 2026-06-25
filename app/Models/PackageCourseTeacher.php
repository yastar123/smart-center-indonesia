<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageCourseTeacher extends Model
{
    protected $table = 'package_course_teachers';

    protected $fillable = ['package_id', 'course_id', 'teacher_id'];

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
