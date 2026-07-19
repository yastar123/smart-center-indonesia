<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $fillable = [
        'judul', 'slug', 'ringkasan', 'konten',
        'thumbnail', 'kategori', 'status',
        'penulis_id', 'views', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /* ── Relations ─────────────────────────────── */

    public function penulis(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penulis_id');
    }

    /* ── Helpers ────────────────────────────────── */

    public static function generateSlug(string $judul, ?int $exceptId = null): string
    {
        $slug = $base = Str::slug($judul);
        $i = 1;
        while (static::where('slug', $slug)->when($exceptId, fn($q) => $q->where('id', '!=', $exceptId))->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        return 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=800&q=60';
    }

    public function getKategoriLabelAttribute(): string
    {
        return match($this->kategori) {
            'tips'     => 'Tips & Trik',
            'berita'   => 'Berita',
            'akademik' => 'Akademik',
            'promo'    => 'Promo',
            default    => 'Lainnya',
        };
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
