@extends('layouts.app')
@section('title','List Mata Pelajaran')
@section('page-title','List Mata Pelajaran')

@section('content')

<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-3">
            <div style="width:52px;height:52px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
                <i class="bi bi-journal-bookmark-fill"></i>
            </div>
            <div>
                <div style="font-size:11px;opacity:.6;margin-bottom:3px;text-transform:uppercase;letter-spacing:.08em">Program Belajar</div>
                <h4 class="fw-bold mb-1" style="color:white;letter-spacing:-.02em">Mata Pelajaran Saya</h4>
                <p class="mb-0" style="opacity:.75;font-size:13px">Mata pelajaran terdaftar beserta status pembayaran</p>
            </div>
        </div>
        <div style="font-size:64px;opacity:.08;line-height:1;flex-shrink:0">
            <i class="bi bi-journals"></i>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    @php
        $totalMapel = $courses->count();
        $lunas = 0; $pending = 0; $belum = 0;
        foreach ($courses as $c) {
            $p = $payments[$c->id] ?? null;
            if (!$p) $belum++;
            elseif ($p->status === 'verified') $lunas++;
            else $pending++;
        }
    @endphp
    <div class="col-6 col-md-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Total Mapel</div><div class="stat-value text-primary">{{ $totalMapel }}</div></div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-journal-bookmark-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Sudah Lunas</div><div class="stat-value text-success">{{ $lunas }}</div></div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-check-circle-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Menunggu</div><div class="stat-value text-warning">{{ $pending }}</div></div>
                <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-hourglass-split"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #ef4444">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Belum Bayar</div><div class="stat-value text-danger">{{ $belum }}</div></div>
                <div class="stat-icon bg-danger-soft" style="color:white"><i class="bi bi-exclamation-circle-fill"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-card fade-up">
    @if($courses->isEmpty())
    <div class="empty-state">
        <div class="empty-state-icon"><i class="bi bi-journal-x"></i></div>
        <div class="empty-state-title">Belum Ada Mata Pelajaran</div>
        <div class="empty-state-desc">Kamu belum terdaftar di mata pelajaran apapun. Hubungi admin cabang untuk pendaftaran.</div>
    </div>
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
