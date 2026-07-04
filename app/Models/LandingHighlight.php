<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingHighlight extends Model
{
    protected $fillable = ['image', 'title', 'description', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($q) { return $q->where('is_active', true); }
}
