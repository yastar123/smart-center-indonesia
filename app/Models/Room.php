<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'branch_id',
        'nama_ruangan',
        'kapasitas',
        'status',
        'keterangan',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
