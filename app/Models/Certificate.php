<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Course;

class Certificate extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'certificates';

    protected $fillable = [
        'siswa_id',
        'cabang_id',
        'course_id',
        'diterbitkan_oleh',
        'nomor_sertifikat',
        'jenis',
        'judul',
        'deskripsi',
        'tanggal_terbit',
        'tanggal_expired',
        'file_sertifikat',
        'file_qrcode',
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
        'tanggal_expired' => 'date',
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

    // Relasi ke cabang
    public function cabang()
    {
        return $this->belongsTo(Branch::class, 'cabang_id');
    }

    // Relasi ke user penerbit sertifikat
    public function penerbit()
    {
        return $this->belongsTo(User::class, 'diterbitkan_oleh');
    }

    // Relasi ke mata pelajaran (opsional)
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}