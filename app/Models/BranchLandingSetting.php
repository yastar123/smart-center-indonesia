<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchLandingSetting extends Model
{
    protected $fillable = ['branch_id', 'key', 'value'];

    /* ── Upsert helpers ── */

    public static function get(int $branchId, string $key, $default = null)
    {
        $row = static::where('branch_id', $branchId)->where('key', $key)->first();
        return $row ? $row->value : $default;
    }

    public static function getJson(int $branchId, string $key, $default = []): array
    {
        $raw = static::get($branchId, $key);
        if (!$raw) return $default;
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : $default;
    }

    public static function setVal(int $branchId, string $key, string|null $value): void
    {
        static::updateOrCreate(
            ['branch_id' => $branchId, 'key' => $key],
            ['value' => (string) ($value ?? '')]
        );
    }

    public static function forBranch(int $branchId): array
    {
        return static::where('branch_id', $branchId)
            ->pluck('value', 'key')
            ->toArray();
    }

    /* ── Branch relation ── */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
