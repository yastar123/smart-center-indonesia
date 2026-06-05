@extends('layouts.app')
@section('title', 'Jadwal Kelas')
@section('page-title', 'Jadwal Kelas')

@section('content')

{{-- HEADER BANNER --}}
<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#0f172a,#1e3a5f,#0369a1);color:white;border:none">
    <div class="row align-items-center g-3">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px">
                    <i class="bi bi-calendar-week-fill"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1" style="color:white">Manajemen Jadwal Kelas</h5>
                    <span style="background:rgba(255,255,255,.15);color:rgba(255,255,255,.9);padding:3px 12px;border-radius:20px;font-size:11px;font-weight:600">
                        🚧 Segera Hadir
                    </span>
                </div>
            </div>
            <p style="opacity:.75;font-size:14px;line-height:1.7;margin:0">
                Fitur manajemen jadwal kelas sedang dalam proses pengembangan. Modul ini akan memungkinkan pengelolaan jadwal mengajar guru, ruang kelas, dan kalender akademik secara terintegrasi.
            </p>
        </div>
        <div class="col-md-4 text-center d-none d-md-block">
            <i class="bi bi-calendar-week" style="font-size:80px;opacity:.15"></i>
        </div>
    </div>
</div>

{{-- PREVIEW FEATURES --}}
<div class="row g-3 mb-4">
    @php
    $features = [
        ['icon'=>'bi-calendar2-check','color'=>'#3b82f6','bg'=>'#eff6ff','title'=>'Jadwal Mengajar','desc'=>'Atur jadwal guru per cabang, mata pelajaran, dan ruang kelas dengan mudah'],
        ['icon'=>'bi-clock-history','color'=>'#10b981','bg'=>'#f0fdf4','title'=>'Kehadiran Otomatis','desc'=>'Rekap kehadiran siswa dan guru secara otomatis berdasarkan jadwal aktif'],
        ['icon'=>'bi-calendar-event','color'=>'#f59e0b','bg'=>'#fffbeb','title'=>'Kalender Akademik','desc'=>'Kelola libur nasional, UTS, UAS, dan acara khusus per cabang'],
        ['icon'=>'bi-bell-fill','color'=>'#8b5cf6','bg'=>'#f5f3ff','title'=>'Notifikasi Jadwal','desc'=>'Kirim pengingat jadwal otomatis ke guru dan siswa via aplikasi'],
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
    <h6 class="fw-bold mb-4"><i class="bi bi-kanban text-primary me-2"></i>Status Pengembangan</h6>
    <div class="row g-3">
        @php
        $tasks = [
            ['label'=>'Desain UI/UX','pct'=>100,'color'=>'#10b981'],
            ['label'=>'Struktur Database','pct'=>80,'color'=>'#3b82f6'],
            ['label'=>'Backend API','pct'=>40,'color'=>'#f59e0b'],
            ['label'=>'Frontend Interaktif','pct'=>15,'color'=>'#8b5cf6'],
            ['label'=>'Testing & QA','pct'=>0,'color'=>'#94a3b8'],
        ];
        @endphp
        @foreach($tasks as $t)
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span style="font-size:13px;font-weight:500">{{ $t['label'] }}</span>
                <span style="font-size:12px;font-weight:700;color:{{ $t['color'] }}">{{ $t['pct'] }}%</span>
            </div>
            <div style="height:8px;background:var(--card-border);border-radius:10px;overflow:hidden">
                <div style="width:{{ $t['pct'] }}%;height:100%;background:{{ $t['color'] }};border-radius:10px;transition:width 1s ease"></div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-4 pt-3 d-flex gap-2 flex-wrap" style="border-top:1px solid var(--card-border)">
        <span class="badge" style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;padding:6px 14px;border-radius:8px;font-size:12px">
            <i class="bi bi-check-circle-fill me-1"></i>Estimasi selesai: Q3 2026
        </span>
        <span class="badge" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;padding:6px 14px;border-radius:8px;font-size:12px">
            <i class="bi bi-info-circle me-1"></i>Sprint 4 dari 8
        </span>
    </div>
</div>

@endsection
