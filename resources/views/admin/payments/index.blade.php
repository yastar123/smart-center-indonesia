@extends('layouts.app')
@section('title', 'Pembayaran')
@section('page-title', 'Pembayaran')

@section('content')

{{-- HEADER BANNER --}}
<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#0f172a,#064e3b,#059669);color:white;border:none">
    <div class="row align-items-center g-3">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1" style="color:white">Manajemen Keuangan & Pembayaran</h5>
                    <span style="background:rgba(255,255,255,.15);color:rgba(255,255,255,.9);padding:3px 12px;border-radius:20px;font-size:11px;font-weight:600">
                        🚧 Segera Hadir
                    </span>
                </div>
            </div>
            <p style="opacity:.75;font-size:14px;line-height:1.7;margin:0">
                Sistem keuangan terintegrasi untuk manajemen tagihan, pembayaran SPP, laporan keuangan cabang, dan rekonsiliasi otomatis sedang dalam pengembangan.
            </p>
        </div>
        <div class="col-md-4 text-center d-none d-md-block">
            <i class="bi bi-cash-stack" style="font-size:80px;opacity:.15"></i>
        </div>
    </div>
</div>

{{-- PREVIEW STATS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border:2px dashed var(--card-border)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Tagihan</div>
                    <div class="stat-value text-success">Rp –</div>
                    <div class="stat-growth text-muted"><i class="bi bi-hourglass me-1"></i>Bulan ini</div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white;opacity:.5">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border:2px dashed var(--card-border)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Sudah Lunas</div>
                    <div class="stat-value text-primary">– Siswa</div>
                    <div class="stat-growth text-muted"><i class="bi bi-check me-1"></i>Terbayar</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white;opacity:.5">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border:2px dashed var(--card-border)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Belum Bayar</div>
                    <div class="stat-value text-warning">– Siswa</div>
                    <div class="stat-growth text-warning"><i class="bi bi-exclamation me-1"></i>Menunggak</div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white;opacity:.5">
                    <i class="bi bi-clock"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border:2px dashed var(--card-border)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Pendapatan</div>
                    <div class="stat-value text-danger">Rp –</div>
                    <div class="stat-growth text-muted"><i class="bi bi-graph-up me-1"></i>Bulan ini</div>
                </div>
                <div class="stat-icon bg-danger-soft" style="color:white;opacity:.5">
                    <i class="bi bi-cash-coin"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FEATURE PREVIEW --}}
<div class="row g-3 mb-4">
    @php
    $features = [
        ['icon'=>'bi-receipt-cutoff','color'=>'#10b981','bg'=>'#f0fdf4','title'=>'Tagihan Otomatis','desc'=>'Generate tagihan SPP bulanan otomatis per siswa aktif dengan sistem cicilan fleksibel'],
        ['icon'=>'bi-credit-card-2-front','color'=>'#3b82f6','bg'=>'#eff6ff','title'=>'Multi Payment Gateway','desc'=>'Terima pembayaran via transfer bank, QRIS, Virtual Account, dan dompet digital'],
        ['icon'=>'bi-file-earmark-spreadsheet','color'=>'#f59e0b','bg'=>'#fffbeb','title'=>'Laporan Keuangan','desc'=>'Rekap otomatis pendapatan, piutang, dan arus kas per cabang dalam format Excel/PDF'],
        ['icon'=>'bi-bell-fill','color'=>'#ef4444','bg'=>'#fef2f2','title'=>'Reminder Tunggakan','desc'=>'Kirim notifikasi WA/email otomatis ke siswa yang belum melunasi pembayaran'],
    ];
    @endphp
    @foreach($features as $f)
    <div class="col-md-6 fade-up">
        <div class="dashboard-card d-flex align-items-start gap-3" style="border-left:4px solid {{ $f['color'] }}">
            <div style="width:44px;height:44px;border-radius:12px;background:{{ $f['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="bi {{ $f['icon'] }}" style="font-size:20px;color:{{ $f['color'] }}"></i>
            </div>
            <div>
                <div class="fw-bold mb-1" style="font-size:14px">{{ $f['title'] }}</div>
                <div class="text-muted" style="font-size:12.5px;line-height:1.6">{{ $f['desc'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- PROGRESS --}}
<div class="dashboard-card fade-up">
    <h6 class="fw-bold mb-4"><i class="bi bi-kanban text-success me-2"></i>Status Pengembangan</h6>
    <div class="row g-3">
        @php $tasks = [
            ['label'=>'Desain UI/UX','pct'=>90,'color'=>'#10b981'],
            ['label'=>'Model & Migrasi Database','pct'=>100,'color'=>'#3b82f6'],
            ['label'=>'API Pembayaran','pct'=>30,'color'=>'#f59e0b'],
            ['label'=>'Integrasi Payment Gateway','pct'=>5,'color'=>'#8b5cf6'],
            ['label'=>'Laporan & Rekonsiliasi','pct'=>0,'color'=>'#94a3b8'],
        ]; @endphp
        @foreach($tasks as $t)
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span style="font-size:13px;font-weight:500">{{ $t['label'] }}</span>
                <span style="font-size:12px;font-weight:700;color:{{ $t['color'] }}">{{ $t['pct'] }}%</span>
            </div>
            <div style="height:8px;background:var(--card-border);border-radius:10px;overflow:hidden">
                <div style="width:{{ $t['pct'] }}%;height:100%;background:{{ $t['color'] }};border-radius:10px"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection
