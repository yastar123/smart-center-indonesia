@extends('layouts.app')
@section('title', 'Detail Invoice')
@section('page-title', 'Detail Invoice')

@section('content')
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.billing.index') }}">Billing</a></li>
        <li class="breadcrumb-item active">{{ $billing->nomor_invoice }}</li>
    </ol>
</nav>

@php
    $statusMap = [
        'belum_bayar' => ['bg'=>'var(--soft-danger)','fg'=>'#dc2626','icon'=>'bi-x-circle-fill','label'=>'Belum Bayar'],
        'sebagian'    => ['bg'=>'rgba(59,130,246,.12)','fg'=>'#2563eb','icon'=>'bi-pie-chart-fill','label'=>'Dibayar Sebagian'],
        'lunas'       => ['bg'=>'var(--soft-success)','fg'=>'#059669','icon'=>'bi-check-circle-fill','label'=>'Lunas'],
    ];
    $s = $statusMap[$billing->status] ?? ['bg'=>'var(--soft-muted-bg)','fg'=>'#6b7280','icon'=>'bi-dash-circle','label'=>ucfirst($billing->status)];
@endphp

<div class="row g-4">
    <div class="col-lg-8">
        <div class="dashboard-card">
            {{-- Invoice Header --}}
            <div class="d-flex align-items-start justify-content-between mb-4 pb-3 border-bottom">
                <div>
                    <div style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted)" class="mb-1">No. Invoice</div>
                    <h5 class="fw-bold mb-1" style="color:#461256">{{ $billing->nomor_invoice }}</h5>
                    <div class="text-muted" style="font-size:13px">Diterbitkan: {{ $billing->created_at->format('d F Y') }}</div>
                </div>
                <span style="background:{{ $s['bg'] }};color:{{ $s['fg'] }};padding:8px 16px;border-radius:20px;font-size:14px;font-weight:600">
                    <i class="bi {{ $s['icon'] }} me-1"></i>{{ $s['label'] }}
                </span>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted)" class="mb-1">Siswa</div>
                    <div class="fw-semibold">{{ $billing->siswa?->name ?? '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted)" class="mb-1">Cabang</div>
                    <div class="fw-semibold">{{ $billing->cabang?->name ?? '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted)" class="mb-1">Jatuh Tempo</div>
                    <div class="fw-semibold">{{ $billing->jatuh_tempo ? $billing->jatuh_tempo->format('d F Y') : '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted)" class="mb-1">Periode</div>
                    <div class="fw-semibold">{{ $billing->periode ?? '—' }}</div>
                </div>
                <div class="col-12">
                    <div style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted)" class="mb-1">Program / Deskripsi</div>
                    <div class="fw-semibold">{{ $billing->deskripsi ?? '—' }}</div>
                </div>
                @if($billing->catatan)
                <div class="col-12">
                    <div style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted)" class="mb-1">Catatan</div>
                    <div class="p-3 rounded" style="background:var(--input-bg)">{{ $billing->catatan }}</div>
                </div>
                @endif
            </div>

            {{-- Amount breakdown --}}
            <div class="mt-4 pt-3 border-top">
                <div class="d-flex justify-content-between py-2">
                    <span class="text-muted">Subtotal</span>
                    <span class="fw-semibold">Rp {{ number_format($billing->subtotal,0,',','.') }}</span>
                </div>
                @if($billing->diskon > 0)
                <div class="d-flex justify-content-between py-2">
                    <span class="text-muted">Diskon</span>
                    <span class="fw-semibold text-success">- Rp {{ number_format($billing->diskon,0,',','.') }}</span>
                </div>
                @endif
                @if($billing->pajak > 0)
                <div class="d-flex justify-content-between py-2">
                    <span class="text-muted">Pajak</span>
                    <span class="fw-semibold">Rp {{ number_format($billing->pajak,0,',','.') }}</span>
                </div>
                @endif
                <div class="d-flex justify-content-between py-2 border-top mt-1">
                    <span class="fw-bold" style="font-size:16px">Total Tagihan</span>
                    <span class="fw-bold" style="font-size:18px;color:#461256">Rp {{ number_format($billing->total,0,',','.') }}</span>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('admin.billing.index') }}" class="btn btn-outline-secondary fw-semibold">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="dashboard-card">
            <h6 class="fw-bold mb-3">Riwayat Pembayaran</h6>
            @forelse($billing->pembayaran as $pay)
                <div class="d-flex justify-content-between align-items-start py-2 border-bottom">
                    <div>
                        <div class="fw-semibold" style="font-size:13px">Rp {{ number_format($pay->jumlah,0,',','.') }}</div>
                        <div class="text-muted" style="font-size:12px">{{ \Carbon\Carbon::parse($pay->tanggal_pembayaran)->format('d M Y') }}</div>
                    </div>
                    <span style="background:var(--soft-success);color:#059669;padding:3px 8px;border-radius:10px;font-size:11px;font-weight:600">
                        {{ ucfirst($pay->status) }}
                    </span>
                </div>
            @empty
                <div class="text-muted text-center py-3" style="font-size:13px">
                    <i class="bi bi-inbox" style="font-size:24px;opacity:.3;display:block;margin-bottom:4px"></i>
                    Belum ada pembayaran
                </div>
            @endforelse
        </div>
    </div>
</div>

</div>
@endsection
