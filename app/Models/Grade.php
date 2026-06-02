<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $table = 'grades';

    protected $fillable = [
        'siswa_id',
        'mata_pelajaran_id',
        'guru_id',
        'semester_id',
        'jenis_penilaian',
        'nama_penilaian',
        'nilai',
        'nilai_maksimal',
        'bobot',
        'tanggal',
        'catatan',
    ];

    protected $casts = [
        'nilai' => 'decimal:2',
        'nilai_maksimal' => 'decimal:2',
        'bobot' => 'decimal:2',
        'tanggal' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(Student::class, 'siswa_id');
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

    // Relasi ke semester
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }
}