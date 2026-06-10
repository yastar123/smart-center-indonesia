@extends('layouts.app')
@section('title','Absensi Saya')
@section('page-title','Absensi')

@section('content')

{{-- HEADER BANNER --}}
<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-3">
            <div style="width:52px;height:52px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
                <i class="bi bi-clipboard2-check"></i>
            </div>
            <div>
                <div style="font-size:11px;opacity:.6;margin-bottom:3px;text-transform:uppercase;letter-spacing:.08em">
                    Rekap Kehadiran
                </div>
                <h4 style="font-weight:800;margin-bottom:3px;color:white;letter-spacing:-.02em">
                    Absensi Saya
                </h4>
                <p style="opacity:.65;margin:0;font-size:13px">
                    Lihat catatan kehadiran per mata pelajaran
                </p>
            </div>
        </div>
        <div style="font-size:64px;opacity:.08;line-height:1;flex-shrink:0">
            <i class="bi bi-clipboard2-check-fill"></i>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Mata Pelajaran</div>
                    <div class="stat-value text-primary">{{ $courses->count() }}</div>
                    <div class="stat-label text-muted" style="font-size:11px">terdaftar</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Status</div>
                    <div class="stat-value text-success" style="font-size:16px">Aktif</div>
                    <div class="stat-label" style="font-size:11px;color:#10b981">
                        <i class="bi bi-circle-fill me-1" style="font-size:7px"></i>Berkegiatan
                    </div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white">
                    <i class="bi bi-person-check-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Semester</div>
                    <div class="stat-value" style="color:#e09000;font-size:16px">
                        {{ now()->month <= 6 ? 'Genap' : 'Ganjil' }}
                    </div>
                    <div class="stat-label text-muted" style="font-size:11px">
                        <i class="bi bi-calendar me-1"></i>{{ now()->format('Y') }}
                    </div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white">
                    <i class="bi bi-calendar3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #0284c7">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Bulan Ini</div>
                    <div class="stat-value" style="color:#0284c7;font-size:16px">
                        {{ now()->locale('id')->isoFormat('MMMM') }}
                    </div>
                    <div class="stat-label text-muted" style="font-size:11px">
                        <i class="bi bi-calendar-week me-1"></i>{{ now()->format('Y') }}
                    </div>
                </div>
                <div class="stat-icon bg-info-soft" style="color:white">
                    <i class="bi bi-calendar-week-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- COURSES GRID --}}
<div class="dashboard-card fade-up">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h6 class="fw-bold mb-1" style="color:var(--text-primary)">
                <i class="bi bi-clipboard2-check text-primary me-2"></i>Mata Pelajaran Saya
            </h6>
            <p class="text-muted mb-0" style="font-size:12px">Klik mata pelajaran untuk melihat detail kehadiran</p>
        </div>
    </div>

    @if($courses->isEmpty())
    <div class="text-center py-5">
        <div style="width:80px;height:80px;border-radius:50%;background:var(--input-bg);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
            <i class="bi bi-journal-x text-muted" style="font-size:2rem;opacity:.5"></i>
        </div>
        <h6 class="fw-semibold mb-2" style="color:var(--text-primary)">Belum Terdaftar</h6>
        <p class="text-muted mb-0" style="font-size:13px">
            Anda belum terdaftar di mata pelajaran apapun.<br>
            Hubungi admin untuk pendaftaran kelas.
        </p>
    </div>
    @else
    <div class="row g-3">
        @foreach($courses as $i => $c)
        @php
            $colors = ['#c84ddf','#10b981','#0284c7','#f6af23','#68117e','#059669','#461256','#e09000'];
            $clr = $colors[$i % count($colors)];
        @endphp
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('siswa.attendance.show', $c->id) }}"
               class="text-decoration-none d-block p-4 rounded-3 h-100"
               style="background:var(--input-bg);border:1.5px solid var(--card-border);transition:all .2s;cursor:pointer"
               onmouseover="this.style.borderColor='{{ $clr }}';this.style.transform='translateY(-2px)'"
               onmouseout="this.style.borderColor='var(--card-border)';this.style.transform='translateY(0)'">
                <div class="d-flex align-items-start gap-3">
                    <div style="width:48px;height:48px;border-radius:14px;background:{{ $clr }}18;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi bi-journal-bookmark-fill" style="color:{{ $clr }};font-size:20px"></i>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div class="fw-bold mb-1 text-truncate" style="font-size:14px;color:var(--text-primary)">
                            {{ $c->nama }}
                        </div>
                        @if($c->deskripsi)
                        <div class="text-muted text-truncate" style="font-size:12px">
                            {{ $c->deskripsi }}
                        </div>
                        @else
                        <div class="text-muted" style="font-size:12px">
                            Klik untuk lihat absensi
                        </div>
                        @endif
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-3 pt-3"
                     style="border-top:1px solid var(--card-border)">
                    <span style="font-size:11.5px;color:{{ $clr }};font-weight:600">
                        <i class="bi bi-clipboard2-check me-1"></i>Lihat Absensi
                    </span>
                    <i class="bi bi-arrow-right" style="color:{{ $clr }};font-size:13px"></i>
                </div>
            </a>
        </div>
        @endforeach
    </div>
    @endif

</div>

@endsection
