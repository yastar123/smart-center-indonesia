<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolClass extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'school_classes';

    protected $fillable = [
        'cabang_id',
        'mata_pelajaran_id',
        'guru_id',
        'tahun_akademik_id',
        'nama_kelas',
        'kapasitas',
        'jenis',
        'ruangan',
        'link_zoom',
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

    // Relasi ke mata pelajaran
    public function mataPelajaran()
    {
        return $this->belongsTo(Course::class, 'mata_pelajaran_id');
    }

    // Relasi ke guru
    public function guru()
    {
        return $this->belongsTo(Teacher::class, 'guru_id');
    }

    // Relasi ke tahun akademik
    public function tahunAkademik()
    {
        return $this->belongsTo(AcademicYear::class, 'tahun_akademik_id');
    }

    // Relasi ke jadwal
    public function jadwal()
    {
        return $this->hasMany(Schedule::class, 'kelas_id');
    }

    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsToMany(
            Student::class,
            'class_students',
            'class_id',
            'student_id'
        );
    }
}