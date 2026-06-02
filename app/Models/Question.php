<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    protected $fillable = [
        'tryout_id',
        'teks_pertanyaan',
        'gambar_pertanyaan',
        'jenis',
        'pilihan_jawaban',
        'penjelasan',
        'poin',
        'urutan',
        'tingkat_kesulitan',
    ];

    protected $casts = [
        'pilihan_jawaban' => 'array',
        'poin' => 'decimal:2',
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
}