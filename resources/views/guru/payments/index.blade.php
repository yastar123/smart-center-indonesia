@extends('layouts.app')
@section('title','Pembayaran Saya')
@section('page-title','Pembayaran Guru')

@section('content')

@php
    $teacher = $teacher ?? null;
@endphp

<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h5 class="fw-bold mb-0">Pembayaran Saya</h5>
            <small style="opacity:.8">Lihat riwayat pembayaran yang dibuat oleh admin</small>
        </div>
        <div class="text-end">
            <div class="fw-semibold">{{ $teacher?->name }}</div>
            <div class="text-muted small">{{ $teacher?->branch?->name ?? 'Pusat' }}</div>
        </div>
    </div>
</div>

<div class="dashboard-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="thead-modern"><tr><th>Periode</th><th>Total Gaji</th><th>Tanggal Pembayaran</th><th>Metode</th><th>Status</th><th>Bukti</th></tr></thead>
            <tbody>
                @forelse($salaries as $s)
                <tr>
                    <td>{{ $s->periode }}</td>
                    <td class="fw-bold">Rp {{ number_format($s->total_gaji,0,',','.') }}</td>
                    <td>{{ $s->tanggal_pembayaran?->format('d M Y') ?? '-' }}</td>
                    <td>{{ $s->metode_pembayaran ?? '-' }}</td>
                    <td>
                        @if($s->status === 'dibayar')<span class="badge bg-success">Dibayar</span>
                        @elseif($s->status === 'pending')<span class="badge bg-warning text-dark">Pending</span>
                        @else <span class="badge bg-danger">Batal</span>@endif
                    </td>
                    <td>
                        @if($s->bukti_pembayaran)
                            <a href="{{ asset('storage/'.$s->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat Bukti</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada pembayaran</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $salaries->links() }}</div>
</div>

@endsection
