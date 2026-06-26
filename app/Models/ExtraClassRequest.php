<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraClassRequest extends Model
{
    use HasFactory;

    protected $table = 'extra_class_requests';

    protected $fillable = [
        'siswa_id',
        'course_id',
        'tanggal_rencana',
        'jam_mulai',
        'jumlah_sesi',
        'harga',
        'status',
        'catatan',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal_rencana' => 'date',
        'harga'           => 'decimal:2',
        'jumlah_sesi'     => 'integer',
    ];

    public function siswa()
    {
        return $this->belongsTo(Student::class, 'siswa_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
