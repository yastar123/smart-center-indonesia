<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    use HasFactory;

    protected $table = 'academic_years';

    protected $fillable = [
        'nama',
        'tahun_mulai',
        'tahun_selesai',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    public function semesters()
    {
        return $this->hasMany(Semester::class, 'academic_year_id');
    }

    public function kelas()
    {
        return $this->hasMany(SchoolClass::class, 'academic_year_id');
    }
}