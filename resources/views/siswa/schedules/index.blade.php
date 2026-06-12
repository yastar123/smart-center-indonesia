@extends('layouts.app')
@section('title','Jadwal Pertemuan')
@section('page-title','Jadwal Pertemuan')

@section('content')

{{-- HEADER --}}
<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-3">
            <div style="width:52px;height:52px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div>
                <div style="font-size:11px;opacity:.6;margin-bottom:3px;text-transform:uppercase;letter-spacing:.08em">Jadwal Belajar</div>
                <h4 style="font-weight:800;margin-bottom:3px;color:white;letter-spacing:-.02em">Jadwal Pertemuan</h4>
                <p style="opacity:.65;margin:0;font-size:13px">Lihat jadwal kelas yang telah ditetapkan</p>
            </div>
        </div>
        <div style="font-size:64px;opacity:.08;line-height:1;flex-shrink:0">
            <i class="bi bi-calendar3-fill"></i>
        </div>
    </div>
</div>

{{-- INFO --}}
<div class="dashboard-card mb-4 fade-up" style="border-left:4px solid #c84ddf">
    <div class="d-flex align-items-center gap-2" style="font-size:12.5px;color:var(--text-muted)">
        <i class="bi bi-eye text-primary me-1"></i>
        <span>Jadwal ditentukan oleh admin dan guru. Kamu hanya dapat melihat jadwal, tidak dapat mengubahnya.</span>
    </div>
</div>

{{-- FILTER --}}
<div class="dashboard-card mb-4 fade-up">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-auto">
            <label class="form-label small text-muted mb-1">Bulan</label>
            <select name="bulan" class="form-select form-select-sm" style="border-radius:9px;min-width:110px">
                <option value="">Semua Bulan</option>
                @foreach(range(1,12) as $m)
                <option value="{{ $m }}" {{ request('bulan')==$m?'selected':'' }}>
                    {{ \Carbon\Carbon::create()->month($m)->locale('id')->isoFormat('MMMM') }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <label class="form-label small text-muted mb-1">Tahun</label>
            <select name="tahun" class="form-select form-select-sm" style="border-radius:9px;min-width:90px">
                @foreach([now()->year, now()->year-1] as $y)
                <option value="{{ $y }}" {{ request('tahun')==$y?'selected':'' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary btn-sm" style="border-radius:9px">
                <i class="bi bi-funnel me-1"></i>Filter
            </button>
            <a href="{{ route('siswa.schedules.index') }}" class="btn btn-sm ms-1" style="border-radius:9px;background:var(--input-bg);border:1px solid var(--card-border)">Reset</a>
        </div>
    </form>
</div>

{{-- LIST --}}
<div class="d-flex flex-column gap-3">
@if($schedules->isEmpty())
<div class="dashboard-card fade-up">
    <div class="text-center py-5">
        <div style="width:80px;height:80px;border-radius:50%;background:var(--input-bg);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
            <i class="bi bi-calendar-x text-muted" style="font-size:2rem;opacity:.5"></i>
        </div>
        <h6 class="fw-semibold mb-2" style="color:var(--text-primary)">Belum Ada Jadwal</h6>
        <p class="text-muted mb-0" style="font-size:13px">Belum ada jadwal yang ditetapkan untuk kelas kamu.</p>
    </div>
</div>
@else
@foreach($schedules as $s)
@php
    $tgl      = $s->tanggal instanceof \Carbon\Carbon ? $s->tanggal : \Carbon\Carbon::parse($s->tanggal);
    $isToday  = $tgl->isToday();
    $isPast   = $tgl->isPast() && !$isToday;
    $statusMap = [
        'dijadwalkan' => ['color'=>'#0284c7','bg'=>'var(--soft-info-bg)','label'=>'Dijadwalkan','icon'=>'bi-clock'],
        'berlangsung' => ['color'=>'#10b981','bg'=>'var(--soft-success-bg)','label'=>'Berlangsung','icon'=>'bi-broadcast'],
        'selesai'     => ['color'=>'var(--text-muted)','bg'=>'var(--soft-muted-bg)','label'=>'Selesai','icon'=>'bi-check2'],
        'dibatalkan'  => ['color'=>'#ef4444','bg'=>'var(--soft-danger-bg)','label'=>'Dibatalkan','icon'=>'bi-x-circle'],
    ];
    $sm = $statusMap[$s->status] ?? ['color'=>'#999','bg'=>'var(--input-bg)','label'=>ucfirst($s->status),'icon'=>'bi-circle'];
@endphp
<div class="dashboard-card fade-up"
     style="{{ $isToday ? 'border-left:4px solid #10b981' : ($s->status==='dibatalkan' ? 'opacity:.6' : '') }}">
    <div class="d-flex align-items-start gap-3 flex-wrap">
        {{-- DATE BADGE --}}
        <div class="flex-shrink-0 text-center" style="width:52px">
            <div style="background:{{ $isToday ? '#10b981' : 'rgba(200,77,223,.1)' }};border-radius:12px;padding:6px 8px">
                <div style="font-size:18px;font-weight:800;color:{{ $isToday ? 'white' : '#c84ddf' }};line-height:1">
                    {{ $tgl->format('d') }}
                </div>
                <div style="font-size:10px;font-weight:600;color:{{ $isToday ? 'rgba(255,255,255,.8)' : 'var(--text-muted)' }};text-transform:uppercase">
                    {{ $tgl->locale('id')->isoFormat('MMM') }}
                </div>
            </div>
        </div>
        {{-- DETAILS --}}
        <div style="flex:1;min-width:0">
            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                <span class="fw-bold" style="font-size:14px;color:var(--text-primary)">
                    {{ $s->kelas->mataPelajaran->nama ?? 'Sesi Belajar' }}
                </span>
                @if($s->pertemuan_ke)
                <span style="background:var(--soft-primary-bg);color:var(--soft-primary-text);padding:1px 8px;border-radius:5px;font-size:11px;font-weight:700">
                    Pertemuan ke-{{ $s->pertemuan_ke }}
                </span>
                @endif
                @if($isToday)
                <span style="background:var(--soft-success-bg);color:var(--soft-success-text);padding:1px 8px;border-radius:5px;font-size:11px;font-weight:700">
                    <i class="bi bi-circle-fill me-1" style="font-size:6px"></i>Hari Ini
                </span>
                @endif
            </div>
            <div class="d-flex flex-wrap gap-3" style="font-size:12px;color:var(--text-muted)">
                <span><i class="bi bi-building me-1"></i>{{ $s->kelas->nama_kelas ?? '—' }}</span>
                <span><i class="bi bi-person me-1"></i>{{ $s->kelas->guru->name ?? '—' }}</span>
                <span><i class="bi bi-clock me-1"></i>
                    {{ \Carbon\Carbon::parse($s->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($s->jam_selesai)->format('H:i') }}
                </span>
                @if($s->jenis === 'online')
                <span style="color:#0284c7"><i class="bi bi-camera-video me-1"></i>Online
                    @if($s->link_meeting)
                    · <a href="{{ $s->link_meeting }}" target="_blank" style="color:#0284c7">Join Meeting</a>
                    @endif
                </span>
                @elseif($s->ruangan)
                <span><i class="bi bi-geo-alt me-1"></i>{{ $s->ruangan }}</span>
                @endif
                @if($s->topik)
                <span><i class="bi bi-journal-text me-1"></i>{{ $s->topik }}</span>
                @endif
            </div>
        </div>
        {{-- STATUS --}}
        <div class="flex-shrink-0">
            <span style="background:{{ $sm['bg'] }};color:{{ $sm['color'] }};padding:4px 12px;border-radius:7px;font-size:12px;font-weight:600">
                <i class="bi {{ $sm['icon'] }} me-1"></i>{{ $sm['label'] }}
            </span>
        </div>
    </div>
</div>
@endforeach

@if($schedules->hasPages())
<div class="mt-2">{{ $schedules->links() }}</div>
@endif
@endif
</div>

@endsection
