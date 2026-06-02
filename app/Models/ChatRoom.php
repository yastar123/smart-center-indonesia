<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    protected $table = 'chat_rooms';

    protected $fillable = [
        'nama_room',
        'jenis_room',
        'cabang_id',
        'peserta_id',
        'waktu_pesan_terakhir',
    ];

    protected $casts = [
        'peserta_id' => 'array',
        'waktu_pesan_terakhir' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    // Relasi ke cabang
    public function cabang()
    {
        return $this->belongsTo(Branch::class, 'cabang_id');
    }

    // Relasi ke pesan chat
    public function pesan()
    {
        return $this->hasMany(ChatMessage::class, 'room_id');
    }
}