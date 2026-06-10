@extends('layouts.app')
@section('title','List Mata Pelajaran')
@section('page-title','List Mata Pelajaran')

@section('content')

<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;">
    <div class="d-flex align-items-center gap-3">
        <div style="width:52px;height:52px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px">
            <i class="bi bi-journal-bookmark-fill"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-1" style="color:white">List Mata Pelajaran</h4>
            <p class="mb-0" style="opacity:.75;font-size:13px">Mata pelajaran terdaftar beserta biaya yang ditetapkan admin</p>
        </div>
    </div>
</div>

<div class="dashboard-card fade-up">
    @if($courses->isEmpty())
    <div class="text-center py-5 text-muted">Belum terdaftar di mata pelajaran apapun.</div>
    @else
    <div class="row g-3">
        @foreach($courses as $course)
        @php
            $fee = $fees[$course->id] ?? 0;
            $payment = $payments[$course->id] ?? null;
        @endphp
        <div class="col-md-6 col-lg-4">
            <div class="p-4 rounded-3 h-100" style="background:var(--input-bg);border:1.5px solid var(--card-border)">
                <div class="fw-bold mb-1" style="font-size:15px;color:var(--text-primary)">{{ $course->nama }}</div>
                <div class="text-muted mb-2" style="font-size:12px">{{ $course->cabang->name ?? 'Pusat' }}</div>
                @if($course->deskripsi)
                <p class="text-muted mb-3" style="font-size:12px">{{ Str::limit($course->deskripsi, 80) }}</p>
                @endif
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="text-muted" style="font-size:11px">Biaya</div>
                        <div class="fw-bold" style="font-size:18px;color:var(--primary)">
                            @if($fee > 0) Rp {{ number_format($fee, 0, ',', '.') }}
                            @else <span class="text-success">Gratis</span>
                            @endif
                        </div>
                    </div>
                    @if($payment)
                        @if($payment->status === 'verified')
                        <span class="badge bg-success">Lunas</span>
                        @elseif($payment->status === 'pending')
                        <span class="badge bg-warning text-dark">Menunggu</span>
                        @else
                        <span class="badge bg-danger">Ditolak</span>
                        @endif
                    @endif
                </div>
                @if(!$payment || $payment->status === 'rejected')
                <a href="{{ route('siswa.billing.index', ['course' => $course->id]) }}"
                   class="btn btn-primary btn-sm w-100" style="border-radius:10px">
                    <i class="bi bi-wallet2 me-1"></i>Bayar / Tagihan
                </a>
                @elseif($payment->status === 'pending')
                <a href="{{ route('siswa.billing.index', ['course' => $course->id]) }}"
                   class="btn btn-outline-warning btn-sm w-100" style="border-radius:10px">
                    Lihat Tagihan
                </a>
                @else
                <button class="btn btn-outline-success btn-sm w-100" disabled style="border-radius:10px">
                    <i class="bi bi-check2 me-1"></i>Sudah Lunas
                </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@endsection
