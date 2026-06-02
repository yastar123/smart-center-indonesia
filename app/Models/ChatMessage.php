<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $table = 'chat_messages';

    protected $fillable = [
        'room_id',
        'pengirim_id',
        'jenis',
        'pesan',
        'file_path',
        'dibaca_oleh',
        'is_deleted',
    ];

    protected $casts = [
        'dibaca_oleh' => 'array',
        'is_deleted' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    // Relasi ke room chat
    public function room()
    {
        return $this->belongsTo(ChatRoom::class, 'room_id');
    }

    // Relasi ke pengirim
    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }
}