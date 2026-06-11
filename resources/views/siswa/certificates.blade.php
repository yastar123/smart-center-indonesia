@extends('layouts.app')
@section('title','Sertifikat Saya')
@section('page-title','Sertifikat Saya')

@section('content')

{{-- HEADER --}}
<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0"><i class="bi bi-award"></i></div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Sertifikat Saya</h5>
                    <span style="font-size:12px;opacity:.85">Sertifikat yang diterbitkan admin untuk mata pelajaran Anda</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <div style="font-size:28px;font-weight:800;color:white">{{ $certificates->count() }}</div>
            <div style="font-size:12px;opacity:.7">Sertifikat Tersedia</div>
        </div>
    </div>
</div>

{{-- ENROLLED COURSES WITH CERT STATUS --}}
<h6 class="fw-bold mb-3" style="font-size:14px;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)">
    <i class="bi bi-journal-bookmark text-primary me-2"></i>Mata Pelajaran yang Diambil
</h6>

@if($enrolledClasses->isEmpty())
<div class="dashboard-card fade-up mb-4">
    <div class="text-center py-5">
        <i class="bi bi-journal-x" style="font-size:56px;opacity:.2;display:block;margin-bottom:16px;color:var(--primary)"></i>
        <div class="fw-bold mb-1" style="font-size:16px">Belum Ada Kelas Terdaftar</div>
        <div class="text-muted" style="font-size:13px">Daftarkan diri ke kelas untuk mendapatkan sertifikat dari admin.</div>
    </div>
</div>
@else
<div class="row g-3 mb-4 fade-up">
    @foreach($enrolledClasses as $class)
    @php
        $courseName = $class->mataPelajaran?->nama ?? $class->nama_kelas;
        $courseCode = $class->mataPelajaran?->kode ?? '—';
        $certForClass = $certificates->first(function($cert) use ($courseName) {
            return str_contains(strtolower($cert->judul ?? ''), strtolower($courseName));
        });
        $hasCert = !is_null($certForClass);
    @endphp
    <div class="col-12 col-md-6 col-xl-4">
        <div class="dashboard-card h-100" style="border-top:3px solid {{ $hasCert ? '#10b981' : 'var(--card-border)' }};position:relative">
            {{-- Header row --}}
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#260632,#68117e);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi bi-journal-bookmark-fill" style="color:white;font-size:16px"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="font-size:14px;line-height:1.3">{{ $courseName }}</div>
                        <div class="text-muted" style="font-size:11px">{{ $class->nama_kelas }}</div>
                    </div>
                </div>
                @if($hasCert)
                <span style="background:rgba(16,185,129,.1);color:#10b981;border:1px solid rgba(16,185,129,.25);font-size:11px;font-weight:600;padding:4px 10px;border-radius:20px;white-space:nowrap;flex-shrink:0">
                    <i class="bi bi-check-circle-fill me-1"></i>Tersedia
                </span>
                @else
                <span style="background:var(--soft-muted-bg,rgba(100,116,139,.08));color:var(--text-muted);border:1px solid var(--soft-muted-border,rgba(100,116,139,.15));font-size:11px;font-weight:600;padding:4px 10px;border-radius:20px;white-space:nowrap;flex-shrink:0">
                    <i class="bi bi-clock me-1"></i>Belum Ada
                </span>
                @endif
            </div>

            {{-- Info row --}}
            <div class="d-flex flex-wrap gap-3 mb-3" style="font-size:12px;color:var(--text-muted)">
                <div class="d-flex align-items-center gap-1">
                    <i class="bi bi-person-fill" style="color:var(--primary)"></i>
                    <span>{{ $class->guru?->name ?? 'Belum ada guru' }}</span>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <i class="bi bi-geo-alt" style="color:var(--primary)"></i>
                    <span>{{ $class->cabang?->name ?? 'Pusat' }}</span>
                </div>
            </div>

            {{-- Action --}}
            <div class="pt-2 border-top">
                @if($hasCert)
                <a href="{{ route('siswa.certificates.download', $certForClass) }}"
                    class="btn btn-success btn-sm fw-semibold w-100" style="border-radius:8px" target="_blank">
                    <i class="bi bi-download me-1"></i>Unduh Sertifikat
                </a>
                @else
                <div class="text-center py-1" style="font-size:12px;color:var(--text-muted)">
                    <i class="bi bi-info-circle me-1"></i>Sertifikat belum diterbitkan admin
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- ADDITIONAL CERTIFICATES (admin-issued, not matched to enrolled class) --}}
@php
    $matchedCertIds = $enrolledClasses->map(fn($class) => $certificates->first(function($cert) use ($class) {
        $courseName = $class->mataPelajaran?->nama ?? $class->nama_kelas;
        return str_contains(strtolower($cert->judul ?? ''), strtolower($courseName));
    }))->filter()->pluck('id');
    $otherCerts = $certificates->whereNotIn('id', $matchedCertIds);
@endphp

@if($otherCerts->count() > 0)
<h6 class="fw-bold mb-3" style="font-size:14px;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)">
    <i class="bi bi-award text-warning me-2"></i>Sertifikat Lainnya
</h6>
<div class="row g-3 fade-up">
    @foreach($otherCerts as $cert)
    @php
        $colors = ['kompetensi'=>['#c84ddf','rgba(200,77,223,.1)','bi-patch-check'], 'kelulusan'=>['#10b981','rgba(16,185,129,.1)','bi-mortarboard'], 'prestasi'=>['#f59e0b','rgba(245,158,11,.1)','bi-trophy'], 'partisipasi'=>['#6366f1','rgba(99,102,241,.1)','bi-star']];
        $c = $colors[$cert->jenis] ?? ['#64748b','rgba(100,116,139,.1)','bi-award'];
    @endphp
    <div class="col-md-6 col-lg-4">
        <div class="dashboard-card h-100" style="border-top:4px solid {{ $c[0] }}">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div style="width:44px;height:44px;border-radius:12px;background:{{ $c[1] }};display:flex;align-items:center;justify-content:center">
                    <i class="bi {{ $c[2] }}" style="font-size:20px;color:{{ $c[0] }}"></i>
                </div>
                <span class="badge" style="background:{{ $c[1] }};color:{{ $c[0] }};border:1px solid {{ $c[0] }}44;text-transform:capitalize">{{ $cert->jenis }}</span>
            </div>
            <h6 class="fw-bold mb-1">{{ $cert->judul }}</h6>
            <div class="d-flex align-items-center gap-2 mb-3" style="font-size:12px;color:var(--text-muted)">
                <i class="bi bi-calendar3"></i>
                <span>{{ $cert->tanggal_terbit ? $cert->tanggal_terbit->format('d M Y') : '–' }}</span>
            </div>
            <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                <code style="font-size:10px;color:var(--text-muted)">{{ $cert->nomor_sertifikat }}</code>
                <a href="{{ route('siswa.certificates.download', $cert) }}" class="btn btn-sm btn-primary" target="_blank">
                    <i class="bi bi-download me-1"></i>Unduh
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@if($enrolledClasses->isEmpty() && $certificates->isEmpty())
<div class="dashboard-card fade-up">
    <div class="text-center py-5">
        <i class="bi bi-award" style="font-size:56px;opacity:.2;display:block;margin-bottom:16px;color:var(--primary)"></i>
        <div class="fw-bold mb-1" style="font-size:16px">Belum Ada Sertifikat</div>
        <div class="text-muted" style="font-size:13px">Sertifikat akan muncul setelah admin menerbitkannya untuk Anda.</div>
    </div>
</div>
@endif

@endsection
