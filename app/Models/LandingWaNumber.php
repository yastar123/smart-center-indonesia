<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingWaNumber extends Model
{
    protected $fillable = ['label','number','description','is_primary','is_active','sort_order'];

    protected $casts = ['is_primary'=>'boolean','is_active'=>'boolean'];

    public function scopeActive($q) { return $q->where('is_active', true); }

    public static function primaryNumber(string $default = '628001234567'): string
    {
        return static::where('is_primary', true)->where('is_active', true)->value('number')
            ?? static::where('is_active', true)->orderBy('sort_order')->value('number')
            ?? $default;
    }
}
