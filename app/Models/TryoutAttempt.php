<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TryoutAttempt extends Model
{
    use HasFactory;

    protected $table = 'tryout_attempts';

    protected $fillable = [
        'tryout_id',
        'siswa_id',
        'waktu_mulai',
        'waktu_selesai',
        'nilai',
        'jawaban_benar',
        'jawaban_salah',
        'tidak_dijawab',
        'percobaan_ke',
        'status',
        'jawaban',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'nilai' => 'decimal:2',
        'jawaban' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    // Relasi ke tryout
    public function tryout()
    {
        return $this->belongsTo(Tryout::class, 'tryout_id');
    }

    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(Student::class, 'siswa_id');
    }
}