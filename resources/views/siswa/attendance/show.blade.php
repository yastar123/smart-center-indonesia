@extends('layouts.app')
@section('title','Absensi — '.$course->nama)
@section('page-title','Detail Absensi')

@section('content')

{{-- BACK + HEADER BANNER --}}
<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('siswa.attendance') }}"
                   class="btn btn-sm"
                   style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.25);border-radius:9px;padding:6px 12px;flex-shrink:0">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
                <div>
                    <div style="font-size:11px;opacity:.6;margin-bottom:2px;text-transform:uppercase;letter-spacing:.08em">
                        Absensi Mata Pelajaran
                    </div>
                    <h5 class="fw-bold mb-0" style="color:white">{{ $course->nama }}</h5>
                    @if($course->deskripsi)
                    <span style="font-size:12px;opacity:.75">{{ $course->deskripsi }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <div style="font-size:48px;opacity:.1;line-height:1">
                <i class="bi bi-clipboard2-check-fill"></i>
            </div>
        </div>
    </div>
</div>

@if($classes->isEmpty())
{{-- EMPTY STATE --}}
<div class="dashboard-card fade-up">
    <div class="text-center py-5">
        <div style="width:80px;height:80px;border-radius:50%;background:var(--input-bg);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
            <i class="bi bi-journal-x text-muted" style="font-size:2rem;opacity:.5"></i>
        </div>
        <h6 class="fw-semibold mb-2" style="color:var(--text-primary)">Tidak Ada Kelas Ditemukan</h6>
        <p class="text-muted mb-4" style="font-size:13px">
            Anda belum terdaftar di kelas untuk mata pelajaran ini.
        </p>
        <a href="{{ route('siswa.attendance') }}" class="btn btn-outline-primary" style="border-radius:10px">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Absensi
        </a>
    </div>
</div>

@else
@foreach($classes as $class)
<div class="dashboard-card mb-4 fade-up">

    {{-- CLASS HEADER --}}
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div style="width:44px;height:44px;border-radius:12px;background:rgba(200,77,223,.1);display:flex;align-items:center;justify-content:center">
                <i class="bi bi-diagram-3-fill text-primary" style="font-size:18px"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0" style="color:var(--text-primary)">{{ $class->nama_kelas }}</h6>
                <div class="text-muted" style="font-size:12px">
                    <i class="bi bi-building me-1"></i>{{ $class->cabang->name ?? 'Pusat' }}
                    @if($class->guru)
                    · <i class="bi bi-person-badge me-1"></i>{{ $class->guru->name ?? '' }}
                    @endif
                </div>
            </div>
        </div>
        <span class="badge"
              style="background:var(--soft-success-bg);color:var(--soft-success-text);padding:5px 12px;border-radius:8px;font-size:11.5px;font-weight:600">
            <i class="bi bi-circle-fill me-1" style="font-size:7px"></i>Aktif
        </span>
    </div>

    @if($class->jadwal->isEmpty())
    <div class="text-center py-4" style="background:var(--input-bg);border-radius:12px">
        <i class="bi bi-calendar-x text-muted d-block mb-2" style="font-size:2rem;opacity:.4"></i>
        <p class="text-muted mb-0" style="font-size:13px">Belum ada jadwal untuk kelas ini</p>
    </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th class="small text-muted fw-semibold py-3" style="width:40px">#</th>
                    <th class="small text-muted fw-semibold py-3">TANGGAL</th>
                    <th class="small text-muted fw-semibold py-3">WAKTU</th>
                    <th class="small text-muted fw-semibold py-3 d-none d-md-table-cell">LOKASI</th>
                    <th class="small text-muted fw-semibold py-3">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($class->jadwal as $i => $j)
                @php
                    $tgl     = $j->tanggal instanceof \Carbon\Carbon ? $j->tanggal : \Carbon\Carbon::parse($j->tanggal);
                    $isPast  = $tgl->isPast();
                    $isToday = $tgl->isToday();
                @endphp
                <tr>
                    <td class="text-muted small fw-semibold">{{ $i + 1 }}</td>
                    <td>
                        <div class="fw-semibold" style="font-size:13px">
                            {{ $tgl->locale('id')->isoFormat('dddd, D MMM Y') }}
                        </div>
                        @if($isToday)
                        <span class="badge" style="background:var(--soft-success-bg);color:var(--soft-success-text);font-size:10px;border-radius:6px;padding:2px 7px">Hari ini</span>
                        @endif
                    </td>
                    <td>
                        <span style="font-size:13px;font-weight:600;color:var(--text-primary)">
                            {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}
                        </span>
                        <span class="text-muted" style="font-size:12px">
                            — {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                        </span>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <span style="font-size:13px;color:var(--text-muted)">
                            @if($j->lokasi ?? null)
                                <i class="bi bi-geo-alt me-1" style="color:#c84ddf"></i>{{ $j->lokasi }}
                            @else
                                <span style="opacity:.5">—</span>
                            @endif
                        </span>
                    </td>
                    <td>
                        @if($isToday)
                        <span class="badge" style="background:var(--soft-success-bg);color:var(--soft-success-text);padding:5px 10px;border-radius:8px;font-size:11px;font-weight:600">
                            <i class="bi bi-broadcast me-1"></i>Berlangsung
                        </span>
                        @elseif($isPast)
                        <span class="badge" style="background:var(--input-bg);color:var(--text-muted);padding:5px 10px;border-radius:8px;font-size:11px;font-weight:600;border:1px solid var(--card-border)">
                            <i class="bi bi-check2 me-1"></i>Selesai
                        </span>
                        @else
                        <span class="badge" style="background:var(--soft-info-bg);color:var(--soft-info-text);padding:5px 10px;border-radius:8px;font-size:11px;font-weight:600">
                            <i class="bi bi-clock me-1"></i>Akan Datang
                        </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>
@endforeach
@endif

@endsection
