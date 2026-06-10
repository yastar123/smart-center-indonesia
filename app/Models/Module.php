<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'modules';

    protected $fillable = [
        'mata_pelajaran_id',
        'diupload_oleh',
        'judul',
        'deskripsi',
        'jenis',
        'file_path',
        'file_url',
        'ukuran_file',
        'is_gratis',
        'status',
        'jumlah_download',
    ];

    protected $casts = [
        'is_gratis' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    // Relasi ke mata pelajaran
    public function mataPelajaran()
    {
        return $this->belongsTo(Course::class, 'mata_pelajaran_id');
    }

    // Relasi ke user uploader
    public function uploader()
    {
        return $this->belongsTo(User::class, 'diupload_oleh');
    }
}
