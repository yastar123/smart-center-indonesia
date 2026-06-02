<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'invoices';

    protected $fillable = [
        'siswa_id',
        'cabang_id',
        'nomor_invoice',
        'subtotal',
        'diskon',
        'pajak',
        'total',
        'deskripsi',
        'periode',
        'jatuh_tempo',
        'status',
        'catatan',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'diskon' => 'decimal:2',
        'pajak' => 'decimal:2',
        'total' => 'decimal:2',
        'jatuh_tempo' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(Student::class, 'siswa_id');
    }

    // Relasi ke cabang
    public function cabang()
    {
        return $this->belongsTo(Branch::class, 'cabang_id');
    }

    // Relasi ke pembayaran
    public function pembayaran()
    {
        return $this->hasMany(Payment::class, 'invoice_id');
    }
}