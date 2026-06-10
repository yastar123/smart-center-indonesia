<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, string $default = ''): string
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value ?? '']);
    }

    public static function bulk(array $keys, array $defaults = []): array
    {
        $rows = static::whereIn('key', $keys)->get()->keyBy('key');
        $result = [];
        foreach ($keys as $k) {
            $result[$k] = $rows[$k]->value ?? ($defaults[$k] ?? '');
        }
        return $result;
    }
}
