<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingProgram extends Model
{
    protected $fillable = ['title','description','badge_label','badge_bg','badge_color','icon_emoji','image','is_active','is_popular','is_new','sort_order'];

    protected $casts = ['is_active'=>'boolean','is_popular'=>'boolean','is_new'=>'boolean'];

    public function scopeActive($q) { return $q->where('is_active', true); }
}
