@extends('layouts.app')
@section('title', 'Pengumuman')
@section('page-title', 'Pengumuman')

@section('content')

{{-- HEADER --}}
<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center gap-3" style="position:relative">
        <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
            <i class="bi bi-megaphone-fill"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-0" style="color:white">Pengumuman</h5>
            <span style="font-size:12px;opacity:.8">Informasi terbaru dari lembaga Smart Center Indonesia</span>
        </div>
        <div class="ms-auto d-flex align-items-center gap-2">
            <span class="badge" style="background:rgba(255,255,255,.15);color:white;padding:6px 14px;border-radius:20px;font-size:12px">
                <i class="bi bi-bell me-1"></i>{{ $announcements->total() }} pengumuman aktif
            </span>
        </div>
    </div>
</div>

@php
$jenisConfig = [
    'info'    => ['label'=>'Info',    'icon'=>'bi-info-circle-fill',       'bg'=>'var(--soft-primary-bg)',  'color'=>'var(--soft-primary-text)',  'border'=>'var(--soft-primary-border)'],
    'promo'   => ['label'=>'Promo',   'icon'=>'bi-tag-fill',               'bg'=>'rgba(246,175,35,.12)',     'color'=>'#d97706',                   'border'=>'rgba(246,175,35,.3)'],
    'penting' => ['label'=>'Penting', 'icon'=>'bi-exclamation-triangle-fill','bg'=>'rgba(239,68,68,.08)',    'color'=>'#dc2626',                   'border'=>'rgba(239,68,68,.25)'],
    'update'  => ['label'=>'Update',  'icon'=>'bi-arrow-up-circle-fill',   'bg'=>'var(--soft-success-bg)',  'color'=>'var(--soft-success-text)',  'border'=>'var(--soft-success-border)'],
];
@endphp

@if($announcements->isEmpty())
{{-- EMPTY STATE --}}
<div class="dashboard-card fade-up text-center py-5">
    <i class="bi bi-megaphone" style="font-size:4rem;opacity:.15;display:block;margin-bottom:16px"></i>
    <h5 class="fw-bold mb-2">Belum Ada Pengumuman</h5>
    <p class="text-muted mb-4" style="font-size:14px;max-width:360px;margin:0 auto">
        Tidak ada pengumuman aktif saat ini. Pantau terus halaman ini untuk informasi terbaru.
    </p>
    <a href="{{ route('siswa.dashboard') }}" class="btn btn-primary px-4 fw-semibold" style="border-radius:10px">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
    </a>
</div>
@else

{{-- PINNED ANNOUNCEMENTS --}}
@php $pinned = $announcements->filter(fn($a) => $a->is_pinned); @endphp
@if($pinned->isNotEmpty())
<div class="mb-3">
    <div class="d-flex align-items-center gap-2 mb-2" style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em">
        <i class="bi bi-pin-fill" style="color:#c84ddf"></i> Disematkan
    </div>
    <div class="row g-3">
        @foreach($pinned as $ann)
        @php $cfg = $jenisConfig[$ann->jenis] ?? $jenisConfig['info']; @endphp
        <div class="col-12 fade-up">
            <div class="dashboard-card" style="border-left:4px solid #c84ddf;position:relative">
                <div style="position:absolute;top:12px;right:14px;display:flex;gap:6px;align-items:center">
                    <span style="background:rgba(200,77,223,.1);color:#c84ddf;border:1px solid rgba(200,77,223,.25);border-radius:20px;padding:3px 10px;font-size:11px;font-weight:600">
                        <i class="bi bi-pin me-1"></i>Disematkan
                    </span>
                    <span style="background:{{ $cfg['bg'] }};color:{{ $cfg['color'] }};border:1px solid {{ $cfg['border'] }};border-radius:20px;padding:3px 10px;font-size:11px;font-weight:600">
                        <i class="{{ $cfg['icon'] }} me-1"></i>{{ $cfg['label'] }}
                    </span>
                </div>
                <h6 class="fw-bold mb-2" style="font-size:15px;padding-right:180px">{{ $ann->judul }}</h6>
                <p class="text-muted mb-3" style="font-size:13.5px;line-height:1.65;white-space:pre-line">{{ $ann->konten }}</p>
                <div class="d-flex align-items-center gap-3 flex-wrap" style="font-size:12px;color:var(--text-muted)">
                    <span><i class="bi bi-calendar3 me-1"></i>{{ $ann->created_at->locale('id')->isoFormat('D MMM Y') }}</span>
                    @if($ann->tanggal_mulai || $ann->tanggal_selesai)
                    <span><i class="bi bi-clock me-1"></i>
                        {{ $ann->tanggal_mulai?->locale('id')->isoFormat('D MMM') ?? '–' }}
                        s/d
                        {{ $ann->tanggal_selesai?->locale('id')->isoFormat('D MMM Y') ?? 'Tidak terbatas' }}
                    </span>
                    @endif
                    @if($ann->cabang)
                    <span><i class="bi bi-building me-1"></i>{{ $ann->cabang->name }}</span>
                    @else
                    <span><i class="bi bi-globe me-1"></i>Semua Cabang</span>
                    @endif
                    @if($ann->file)
                    <a href="{{ Storage::url($ann->file) }}" target="_blank" class="text-decoration-none fw-semibold" style="color:#c84ddf">
                        <i class="bi bi-paperclip me-1"></i>Lampiran
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ALL ANNOUNCEMENTS --}}
@php $regular = $announcements->filter(fn($a) => !$a->is_pinned); @endphp
@if($regular->isNotEmpty())
<div class="d-flex align-items-center gap-2 mb-2" style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em">
    <i class="bi bi-clock-history" style="color:#c84ddf"></i> Terbaru
</div>
<div class="row g-3 mb-4">
    @foreach($regular as $ann)
    @php $cfg = $jenisConfig[$ann->jenis] ?? $jenisConfig['info']; @endphp
    <div class="col-md-6 col-lg-4 fade-up">
        <div class="dashboard-card h-100 d-flex flex-column" style="border-top:3px solid {{ $ann->jenis === 'penting' ? '#ef4444' : ($ann->jenis === 'promo' ? '#f6af23' : '#c84ddf') }}">
            <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                <span style="background:{{ $cfg['bg'] }};color:{{ $cfg['color'] }};border:1px solid {{ $cfg['border'] }};border-radius:20px;padding:3px 10px;font-size:11px;font-weight:600;flex-shrink:0">
                    <i class="{{ $cfg['icon'] }} me-1"></i>{{ $cfg['label'] }}
                </span>
                <span style="font-size:11px;color:var(--text-muted);flex-shrink:0">{{ $ann->created_at->diffForHumans() }}</span>
            </div>
            <h6 class="fw-bold mb-2" style="font-size:14px;line-height:1.4">{{ $ann->judul }}</h6>
            <p class="text-muted flex-grow-1" style="font-size:13px;line-height:1.6;overflow:hidden;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical">{{ $ann->konten }}</p>
            <div class="d-flex align-items-center justify-content-between mt-2 pt-2" style="border-top:1px solid var(--card-border);font-size:11.5px;color:var(--text-muted)">
                <span>
                    @if($ann->cabang)<i class="bi bi-building me-1"></i>{{ $ann->cabang->name }}
                    @else<i class="bi bi-globe me-1"></i>Semua Cabang
                    @endif
                </span>
                <div class="d-flex align-items-center gap-2">
                    @if($ann->tanggal_selesai)
                    <span style="background:var(--input-bg);border-radius:6px;padding:2px 7px;border:1px solid var(--card-border)">
                        <i class="bi bi-hourglass-split me-1"></i>s/d {{ $ann->tanggal_selesai->locale('id')->isoFormat('D MMM Y') }}
                    </span>
                    @endif
                    @if($ann->file)
                    <a href="{{ Storage::url($ann->file) }}" target="_blank" class="text-decoration-none" style="color:#c84ddf" title="Lihat lampiran">
                        <i class="bi bi-paperclip"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- PAGINATION --}}
@if($announcements->hasPages())
<div class="d-flex justify-content-center mt-2">
    {{ $announcements->links() }}
</div>
@endif

@endif

@endsection
