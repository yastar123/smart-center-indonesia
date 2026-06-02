<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tryout extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tryouts';

    protected $fillable = [
        'cabang_id',
        'dibuat_oleh',
        'judul',
        'deskripsi',
        'kategori',
        'durasi_menit',
        'total_soal',
        'nilai_kelulusan',
        'waktu_mulai',
        'waktu_selesai',
        'is_random',
        'tampilkan_hasil_langsung',
        'tampilkan_kunci_jawaban',
        'maksimal_percobaan',
        'status',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'is_random' => 'boolean',
        'tampilkan_hasil_langsung' => 'boolean',
        'tampilkan_kunci_jawaban' => 'boolean',
        'nilai_kelulusan' => 'decimal:2',
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

    // Relasi ke pembuat
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    // Relasi ke soal
    public function soal()
    {
        return $this->hasMany(Question::class, 'tryout_id');
    }

    // Relasi ke percobaan tryout
    public function percobaan()
    {
        return $this->hasMany(TryoutAttempt::class, 'tryout_id');
    }
}