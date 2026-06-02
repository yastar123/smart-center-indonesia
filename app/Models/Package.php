<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'packages';

    protected $fillable = [
        'cabang_id',
        'nama',
        'deskripsi',
        'harga',
        'durasi_bulan',
        'jumlah_pertemuan',
        'jenis',
        'fitur',
        'is_unggulan',
        'status',
    ];

    protected $casts = [
        'fitur' => 'array',
        'is_unggulan' => 'boolean',
        'harga' => 'decimal:2',
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
        return $this->belongsToMany(
            Course::class,
            'course_package',
            'package_id',
            'course_id'
        );
    }

    // Relasi ke siswa
    public function siswa()
    {
        return $this->hasMany(Student::class, 'package_id');
    }
}