<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $table = 'academic_years';

    protected $fillable = [
        // actual DB columns
        'name',
        'year_start',
        'year_end',
        'is_active',
        // legacy attribute names accepted by seeders/controllers
        'nama',
        'tahun_mulai',
        'tahun_selesai',
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

    // --- Legacy attribute accessors/mutators ---
    public function getNamaAttribute()
    {
        return $this->attributes['name'] ?? null;
    }

    public function setNamaAttribute($value)
    {
        $this->attributes['name'] = $value;
    }

    public function getTahunMulaiAttribute()
    {
        return $this->attributes['year_start'] ?? null;
    }

    public function setTahunMulaiAttribute($value)
    {
        $this->attributes['year_start'] = $value;
    }

    public function getTahunSelesaiAttribute()
    {
        return $this->attributes['year_end'] ?? null;
    }

    public function setTahunSelesaiAttribute($value)
    {
        $this->attributes['year_end'] = $value;
    }
}

// Backward compatibility: some controllers/models reference `TahunAkademik`
if (!class_exists(\App\Models\TahunAkademik::class)) {
    class TahunAkademik extends AcademicYear {}
}