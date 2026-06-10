<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseFee extends Model
{
    protected $fillable = ['course_id', 'amount'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
