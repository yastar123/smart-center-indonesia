<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'payments';

    protected $fillable = [
        'invoice_id',
        'siswa_id',
        'cabang_id',
        'nomor_pembayaran',
        'jumlah',
        'metode',
        'nama_bank',
        'nomor_rekening',
        'bukti_pembayaran',
        'tanggal_pembayaran',
        'status',
        'alasan_penolakan',
        'catatan',
        'disetujui_oleh',
        'tanggal_disetujui',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_pembayaran' => 'date',
        'tanggal_disetujui' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    // Relasi ke invoice
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

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

    // Relasi ke admin approval
    public function approver()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}