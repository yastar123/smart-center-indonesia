<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curriculum extends Model
{
    protected $fillable = ['course_id', 'scope', 'cabang_id'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'cabang_id');
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(CurriculumChapter::class)->orderBy('urutan');
    }
}
