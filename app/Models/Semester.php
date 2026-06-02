<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $table = 'semesters';

    protected $fillable = [
        'tahun_akademik_id',
        'nama_semester',
        'nomor_semester',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    // Relasi ke tahun akademik
    public function tahunAkademik()
    {
        return $this->belongsTo(AcademicYear::class, 'tahun_akademik_id');
    }

    // Relasi ke nilai
    public function nilai()
    {
        return $this->hasMany(Grade::class, 'semester_id');
    }
}