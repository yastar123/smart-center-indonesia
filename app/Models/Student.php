<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'branch_id', 'package_id', 'nis', 'name', 'gender',
        'birth_date', 'birth_place', 'address', 'phone',
        'parent_name', 'parent_phone', 'photo', 'status',
        'join_date', 'school_name', 'grade', 'kategori_peserta_didik',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'join_date'  => 'date',
    ];

    public function user()    { return $this->belongsTo(User::class); }
    public function branch()  { return $this->belongsTo(Branch::class); }
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'student_teachers', 'student_id', 'teacher_id')->withTimestamps();
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'siswa_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'siswa_id');
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=4f8ef7&color=fff';
    }
}
