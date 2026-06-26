<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurriculumChapter extends Model
{
    protected $fillable = ['curriculum_id', 'judul', 'jumlah_sesi', 'urutan', 'pdf_path'];

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }
}
