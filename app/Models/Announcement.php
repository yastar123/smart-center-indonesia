<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $table = 'announcements';

    protected $fillable = [
        'cabang_id', 'dibuat_oleh', 'judul', 'konten',
        'jenis', 'target', 'target_teacher_ids', 'target_student_ids', 'file', 'tanggal_mulai',
        'tanggal_selesai', 'is_pinned', 'status',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'is_pinned'       => 'boolean',
        'target_teacher_ids' => 'array',
        'target_student_ids' => 'array',
    ];

    public function cabang()    { return $this->belongsTo(Branch::class, 'cabang_id'); }
    public function pembuat()   { return $this->belongsTo(User::class, 'dibuat_oleh'); }
}
