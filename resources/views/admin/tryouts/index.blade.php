@extends('layouts.app')
@section('title', 'Tryout Online')
@section('page-title', 'Tryout Online')

@section('content')

{{-- HEADER BANNER --}}
<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#0f172a,#2e1065,#7c3aed);color:white;border:none">
    <div class="row align-items-center g-3">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px">
                    <i class="bi bi-ui-checks-grid"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1" style="color:white">Computer Based Test (CBT) Online</h5>
                    <span style="background:rgba(255,255,255,.15);color:rgba(255,255,255,.9);padding:3px 12px;border-radius:20px;font-size:11px;font-weight:600">
                        🚧 Segera Hadir
                    </span>
                </div>
            </div>
            <p style="opacity:.75;font-size:14px;line-height:1.7;margin:0">
                Platform tryout online berbasis CBT dengan fitur bank soal, anti-cheat, auto-koreksi, dan analisis hasil belajar siswa secara mendalam.
            </p>
        </div>
        <div class="col-md-4 text-center d-none d-md-block">
            <i class="bi bi-laptop" style="font-size:80px;opacity:.15"></i>
        </div>
    </div>
</div>

{{-- TRYOUT TYPES --}}
<div class="row g-3 mb-4">
    @php
    $types = [
        ['icon'=>'bi-mortarboard','color'=>'#8b5cf6','bg'=>'#f5f3ff','title'=>'SBMPTN / SNBT','desc'=>'Tryout persiapan masuk perguruan tinggi dengan soal sesuai kisi-kisi terbaru'],
        ['icon'=>'bi-building','color'=>'#3b82f6','bg'=>'#eff6ff','title'=>'UTBK Saintek/Soshum','desc'=>'Simulasi ujian UTBK dengan timer, pengacak soal, dan koreksi otomatis'],
        ['icon'=>'bi-journal-text','color'=>'#10b981','bg'=>'#f0fdf4','title'=>'Ujian Sekolah','desc'=>'UTS, UAS, dan ujian harian terintegrasi dengan kurikulum per cabang'],
        ['icon'=>'bi-trophy','color'=>'#f59e0b','bg'=>'#fffbeb','title'=>'Kompetisi Online','desc'=>'Olimpiade dan kompetisi antar cabang dengan papan peringkat real-time'],
    ];
    @endphp
    @foreach($types as $t)
    <div class="col-md-6 fade-up">
        <div class="dashboard-card d-flex align-items-start gap-3" style="border-left:4px solid {{ $t['color'] }}">
            <div style="width:44px;height:44px;border-radius:12px;background:{{ $t['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="bi {{ $t['icon'] }}" style="font-size:20px;color:{{ $t['color'] }}"></i>
            </div>
            <div>
                <div class="fw-bold mb-1" style="font-size:14px">{{ $t['title'] }}</div>
                <div class="text-muted" style="font-size:12.5px;line-height:1.6">{{ $t['desc'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- FEATURES GRID --}}
<div class="row g-3 mb-4">
    <div class="col-12 fade-up">
        <div class="dashboard-card">
            <h6 class="fw-bold mb-4"><i class="bi bi-stars text-warning me-2"></i>Fitur Unggulan yang Akan Hadir</h6>
            <div class="row g-3">
                @php
                $highlights = [
                    ['icon'=>'bi-shuffle','label'=>'Pengacak Soal','color'=>'#6366f1'],
                    ['icon'=>'bi-stopwatch','label'=>'Timer Otomatis','color'=>'#ef4444'],
                    ['icon'=>'bi-eye-slash','label'=>'Anti-Cheat System','color'=>'#0f172a'],
                    ['icon'=>'bi-bar-chart-line','label'=>'Analisis Hasil','color'=>'#10b981'],
                    ['icon'=>'bi-file-earmark-pdf','label'=>'Sertifikat PDF','color'=>'#f59e0b'],
                    ['icon'=>'bi-phone','label'=>'Mobile Friendly','color'=>'#3b82f6'],
                    ['icon'=>'bi-database','label'=>'Bank Soal','color'=>'#8b5cf6'],
                    ['icon'=>'bi-graph-up','label'=>'Progress Tracker','color'=>'#06b6d4'],
                ];
                @endphp
                @foreach($highlights as $h)
                <div class="col-6 col-md-3">
                    <div class="d-flex align-items-center gap-2 p-3 rounded-3" style="background:var(--input-bg)">
                        <i class="bi {{ $h['icon'] }}" style="font-size:18px;color:{{ $h['color'] }}"></i>
                        <span style="font-size:13px;font-weight:500">{{ $h['label'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- PROGRESS --}}
<div class="dashboard-card fade-up">
    <h6 class="fw-bold mb-4"><i class="bi bi-kanban" style="color:#8b5cf6"></i> Status Pengembangan</h6>
    <div class="row g-3">
        @php $tasks = [
            ['label'=>'Desain UI/UX CBT','pct'=>75,'color'=>'#8b5cf6'],
            ['label'=>'Bank Soal & Editor','pct'=>60,'color'=>'#3b82f6'],
            ['label'=>'Engine Ujian','pct'=>35,'color'=>'#f59e0b'],
            ['label'=>'Anti-Cheat & Security','pct'=>15,'color'=>'#ef4444'],
            ['label'=>'Analisis & Laporan','pct'=>5,'color'=>'#10b981'],
            ['label'=>'Integrasi & Testing','pct'=>0,'color'=>'#94a3b8'],
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
    <div class="mt-4 pt-3" style="border-top:1px solid var(--card-border)">
        <span class="badge" style="background:#f5f3ff;color:#6b21a8;border:1px solid #ddd6fe;padding:6px 14px;border-radius:8px;font-size:12px">
            <i class="bi bi-calendar me-1"></i>Estimasi selesai: Q4 2026
        </span>
    </div>
</div>

@endsection
