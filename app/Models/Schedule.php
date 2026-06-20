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
        'paket_id',
        'guru_id',
        'cabang_id',
        'pertemuan_ke',
        'tanggal',
        'tanggal_selesai',
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
        'tanggal_selesai' => 'date',
        'reminder_terkirim' => 'boolean',
    ];

    public function kelas()
    {
        return $this->belongsTo(SchoolClass::class, 'kelas_id');
    }

    public function paket()
    {
        return $this->belongsTo(Package::class, 'paket_id');
    }

    public function guru()
    {
        return $this->belongsTo(Teacher::class, 'guru_id');
    }

    public function cabang()
    {
        return $this->belongsTo(Branch::class, 'cabang_id');
    }

    public function agreements()
    {
        return $this->hasMany(ScheduleStudentAgreement::class);
    }

    public function absensi()
    {
        return $this->hasMany(AbsensiSiswa::class, 'jadwal_id');
    }
}
