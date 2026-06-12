<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiSiswa extends Model
{
    protected $table = 'absensi_siswas';

    protected $fillable = [
        'jadwal_id',
        'siswa_id',
        'guru_hadir',
        'siswa_konfirmasi_at',
        'status',
        'catatan',
    ];

    protected $casts = [
        'guru_hadir'          => 'boolean',
        'siswa_konfirmasi_at' => 'datetime',
    ];

    public static function computeStatus(bool $guruHadir, $siswaKonfirmasiAt): string
    {
        if ($guruHadir && $siswaKonfirmasiAt)  return 'hadir';
        if ($guruHadir && !$siswaKonfirmasiAt) return 'menunggu_konfirmasi';
        if (!$guruHadir && $siswaKonfirmasiAt) return 'tidak_valid';
        return 'tidak_hadir';
    }

    public function jadwal()
    {
        return $this->belongsTo(Schedule::class, 'jadwal_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Student::class, 'siswa_id');
    }
}
