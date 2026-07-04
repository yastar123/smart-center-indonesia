<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingTicker extends Model
{
    protected $fillable = ['emoji', 'text', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($q) { return $q->where('is_active', true); }
}
