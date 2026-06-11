@extends('layouts.app')
@section('title','Sertifikat Saya')
@section('page-title','Sertifikat Saya')

@section('content')
<div>

{{-- HEADER --}}
<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0"><i class="bi bi-award"></i></div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Sertifikat Mata Pelajaran</h5>
                    <span style="font-size:12px;opacity:.85">Lihat dan unduh sertifikat untuk setiap mata pelajaran yang Anda ambil</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Mapel</div>
                    <div class="stat-value count-up" data-target="{{ $stats['total_courses'] }}">{{ $stats['total_courses'] }}</div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-book"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Sudah Punya Sertifikat</div>
                    <div class="stat-value text-success">{{ $stats['certified'] }}</div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-check-circle"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Belum Ada Sertifikat</div>
                    <div class="stat-value text-primary">{{ $stats['pending'] }}</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-clock"></i></div>
            </div>
        </div>
    </div>
</div>

{{-- COURSE CARDS --}}
@if($courseData->isEmpty())
<div class="dashboard-card fade-up">
    <div class="empty-state">
        <div class="empty-state-icon"><i class="bi bi-book"></i></div>
        <div class="empty-state-title">Belum Ada Mata Pelajaran</div>
        <div class="empty-state-desc">Anda belum terdaftar pada mata pelajaran apa pun.</div>
    </div>
</div>
@else
<div class="row g-3">
    @foreach($courseData as $course)
    @php
        $hasCert = $course['has_certificate'];
        $cert = $course['certificate'];
        $colors = $hasCert 
            ? ['#10b981','rgba(16,185,129,.1)','bi-award-fill'] 
            : ['#94a3b8','rgba(148,163,184,.1)','bi-clock'];
    @endphp
    <div class="col-md-6 col-lg-4">
        <div class="dashboard-card h-100" style="border-top:4px solid {{ $colors[0] }};position:relative">
            {{-- Icon & Badge --}}
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div style="width:48px;height:48px;border-radius:12px;background:{{ $colors[1] }};display:flex;align-items:center;justify-content:center">
                    <i class="bi {{ $colors[2] }}" style="font-size:22px;color:{{ $colors[0] }}"></i>
                </div>
                @if($hasCert)
                <span class="badge" style="background:rgba(16,185,129,.1);color:#10b981;border:1px solid rgba(16,185,129,.3)">
                    <i class="bi bi-check-circle me-1"></i>Tersedia
                </span>
                @else
                <span class="badge" style="background:rgba(148,163,184,.1);color:#94a3b8;border:1px solid rgba(148,163,184,.3)">
                    <i class="bi bi-clock me-1"></i>Belum Tersedia
                </span>
                @endif
            </div>

            {{-- Course Name --}}
            <h6 class="fw-bold mb-2" style="font-size:15px">{{ $course['course_name'] }}</h6>

            {{-- Certificate Info --}}
            @if($hasCert && $cert)
            <div class="mb-3">
                <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px">{{ $cert->judul ?? 'Sertifikat Kompetensi' }}</div>
                <div class="d-flex align-items-center gap-2" style="font-size:11px;color:var(--text-muted)">
                    <i class="bi bi-calendar3"></i>
                    <span>{{ $cert->tanggal_terbit ? \Carbon\Carbon::parse($cert->tanggal_terbit)->format('d M Y') : '–' }}</span>
                    @if($cert->jenis)
                    <span class="badge ms-1" style="background:rgba(200,77,223,.1);color:#c84ddf;font-size:10px;text-transform:capitalize">{{ $cert->jenis }}</span>
                    @endif
                </div>
                @if($cert->nomor_sertifikat)
                <code style="font-size:10px;color:var(--text-muted);margin-top:4px;display:block">{{ $cert->nomor_sertifikat }}</code>
                @endif
            </div>
            @else
            <div class="text-muted mb-3" style="font-size:12px">
                <i class="bi bi-info-circle me-1"></i>
                Sertifikat belum diunggah oleh admin
            </div>
            @endif

            {{-- Action Button --}}
            <div class="mt-auto pt-3 border-top">
                @if($hasCert && $cert)
                <a href="{{ route('siswa.certificates.download', $cert) }}" class="btn btn-sm w-100" 
                   style="background:{{ $colors[1] }};color:{{ $colors[0] }};border:1px solid {{ $colors[0] }}40;border-radius:10px;font-weight:600">
                    <i class="bi bi-download me-1"></i>Download Sertifikat
                </a>
                @else
                <button class="btn btn-sm w-100 btn-secondary" disabled 
                        style="border-radius:10px;font-weight:600;opacity:.6">
                    <i class="bi bi-lock me-1"></i>Sertifikat Belum Tersedia
                </button>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

</div>
@endsection
