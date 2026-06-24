<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'courses';

    protected $fillable = [
        'cabang_id',
        'kode',
        'nama',
        'kategori',
        'deskripsi',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    // Relasi ke cabang
    public function cabang()
    {
        return $this->belongsTo(Branch::class, 'cabang_id');
    }

    public function fee()
    {
        return $this->hasOne(CourseFee::class);
    }

    // Relasi ke paket belajar
    public function paket()
    {
        return $this->belongsToMany(
            Package::class,
            'course_package',
            'course_id',
            'package_id'
        );
    }

    // Relasi ke guru
    public function guru()
    {
        return $this->belongsToMany(
            Teacher::class,
            'teacher_courses',
            'course_id',
            'teacher_id'
        );
    }

    // Relasi ke modul
    public function modul()
    {
        return $this->hasMany(Module::class, 'mata_pelajaran_id');
    }

    // Relasi ke kelas
    public function kelas()
    {
        return $this->hasMany(SchoolClass::class, 'mata_pelajaran_id');
    }

    // Alias snake_case for withCount('school_classes')
    public function school_classes()
    {
        return $this->hasMany(SchoolClass::class, 'mata_pelajaran_id');
    }

    // Alias for withCount('packages')
    public function packages()
    {
        return $this->belongsToMany(
            Package::class,
            'course_package',
            'course_id',
            'package_id'
        );
    }

    // Relasi ke nilai
    public function nilai()
    {
        return $this->hasMany(Grade::class, 'mata_pelajaran_id');
    }
}
