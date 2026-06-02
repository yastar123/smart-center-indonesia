<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'schedules';

    protected $fillable = [
        'kelas_id',
        'guru_id',
        'cabang_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'topik',
        'jenis',
        'ruangan',
        'link_meeting',
        'status',
        'catatan',
        'reminder_terkirim',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'reminder_terkirim' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    // Relasi ke kelas
    public function kelas()
    {
        return $this->belongsTo(SchoolClass::class, 'kelas_id');
    }

    // Relasi ke guru
    public function guru()
    {
        return $this->belongsTo(Teacher::class, 'guru_id');
    }

    // Relasi ke cabang
    public function cabang()
    {
        return $this->belongsTo(Branch::class, 'cabang_id');
    }

    // Relasi absensi siswa
    public function absensiSiswa()
    {
        return $this->hasMany(StudentAttendance::class, 'schedule_id');
    }

    // Relasi absensi guru
    public function absensiGuru()
    {
        return $this->hasMany(TeacherAttendance::class, 'schedule_id');
    }
}