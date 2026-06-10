<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salary extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'salaries';

    protected $fillable = [
        'guru_id',
        'cabang_id',
        'periode',
        'tipe_gaji',
        'gaji_pokok',
        'jam_mengajar',
        'tarif_per_jam',
        'total_gaji_mengajar',
        'bonus',
        'potongan',
        'total_gaji',
        'metode_pembayaran',
        'nama_bank',
        'nomor_rekening',
        'tanggal_pembayaran',
        'status',
        'catatan',
        'bukti_pembayaran',
        'dibayar_oleh',
    ];

    protected $casts = [
        'gaji_pokok' => 'decimal:2',
        'jam_mengajar' => 'decimal:1',
        'tarif_per_jam' => 'decimal:2',
        'total_gaji_mengajar' => 'decimal:2',
        'bonus' => 'decimal:2',
        'potongan' => 'decimal:2',
        'total_gaji' => 'decimal:2',
        'tanggal_pembayaran' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    // Relasi ke guru
    public function guru()
    {
        return $this->belongsTo(Teacher::class, 'guru_id');
    }

    // Relasi ke cabang
    public function cabang()
    {
        return $this->belongsTo(Branch::class, 'cabang_id');
    }

    // Relasi ke admin pembayaran
    public function pembayaranOleh()
    {
        return $this->belongsTo(User::class, 'dibayar_oleh');
    }
}