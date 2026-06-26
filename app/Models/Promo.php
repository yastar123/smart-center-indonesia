<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promo extends Model
{
    protected $fillable = [
        'kode', 'judul', 'tipe', 'kode_promo', 'deskripsi',
        'banner_path', 'tanggal_mulai', 'tanggal_berakhir',
        'target', 'cabang_id', 'status', 'views', 'claims',
    ];

    protected $casts = [
        'tanggal_mulai'    => 'date',
        'tanggal_berakhir' => 'date',
    ];

    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'cabang_id');
    }

    public static function generateKode(): string
    {
        $last = static::orderByDesc('id')->first();
        $num  = $last ? ((int) substr($last->kode, 4)) + 1 : 1;
        return 'PRM-' . str_pad($num, 3, '0', STR_PAD_LEFT);
    }

    public function getTipeLabelAttribute(): string
    {
        return [
            'diskon'        => 'Diskon',
            'bundle_upgrade'=> 'Bundle Upgrade',
            'special_price' => 'Special Price',
            'lainnya'       => 'Lainnya',
        ][$this->tipe] ?? $this->tipe;
    }

    public function getStatusLabelAttribute(): string
    {
        return [
            'draft'   => 'Draft',
            'aktif'   => 'Aktif',
            'berakhir'=> 'Berakhir',
        ][$this->status] ?? $this->status;
    }
}
