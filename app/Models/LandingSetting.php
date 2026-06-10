<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingSetting extends Model
{
    protected $fillable = ['section','key','value','type','label','sort_order'];

    public static function get(string $key, string $default = ''): string
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    public static function setVal(string $key, string $value): void
    {
        static::where('key', $key)->update(['value' => $value]);
    }
}
