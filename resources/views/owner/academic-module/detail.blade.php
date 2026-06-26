@extends('layouts.app')
@section('title', 'Detail Modul')
@section('page-title', 'Detail Modul')

@section('content')
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('owner.module.index') }}">Modul Akademik</a></li>
        <li class="breadcrumb-item active">Detail Modul</li>
    </ol>
</nav>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="dashboard-card">
            {{-- Header --}}
            <div class="d-flex align-items-start justify-content-between mb-4 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;font-size:24px;color:white">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">{{ $module->judul }}</h5>
                        @if($module->kode_modul)
                            <code style="background:var(--soft-primary);color:#461256;padding:3px 10px;border-radius:6px;font-size:13px;font-weight:600">
                                {{ $module->kode_modul }}
                            </code>
                        @endif
                    </div>
                </div>
                @if($module->status === 'aktif')
                    <span style="background:var(--soft-success);color:#059669;padding:6px 14px;border-radius:20px;font-size:13px;font-weight:600">
                        <i class="bi bi-check-circle-fill me-1"></i>Aktif
                    </span>
                @else
                    <span style="background:var(--soft-warning);color:#d97706;padding:6px 14px;border-radius:20px;font-size:13px;font-weight:600">
                        <i class="bi bi-hourglass-split me-1"></i>Review
                    </span>
                @endif
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted)" class="mb-1">Mata Pelajaran</div>
                    <div class="fw-semibold">{{ $module->mataPelajaran?->nama ?? '—' }}</div>
                </div>
                <div class="col-md-3">
                    <div style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted)" class="mb-1">Jenis</div>
                    <div class="fw-semibold text-capitalize">{{ $module->jenis ?? '—' }}</div>
                </div>
                <div class="col-md-3">
                    <div style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted)" class="mb-1">Dibuat</div>
                    <div class="fw-semibold">{{ $module->created_at->format('d M Y') }}</div>
                </div>

                @if($module->deskripsi)
                <div class="col-12">
                    <div style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted)" class="mb-2">Deskripsi / Silabus</div>
                    <div class="p-3 rounded" style="background:var(--input-bg);line-height:1.7">{{ $module->deskripsi }}</div>
                </div>
                @endif
            </div>

            <div class="d-flex gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('owner.module.edit', $module) }}" class="btn btn-primary fw-semibold">
                    <i class="bi bi-pencil me-2"></i>Edit Modul
                </a>
                <a href="{{ route('owner.module.index') }}" class="btn btn-outline-secondary fw-semibold">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="dashboard-card">
            <h6 class="fw-bold mb-3">Info Cepat</h6>
            <div class="d-flex flex-column gap-3">
                <div class="d-flex justify-content-between">
                    <span class="text-muted" style="font-size:13px">Kode Modul</span>
                    <span class="fw-semibold" style="font-size:13px">{{ $module->kode_modul ?: '—' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted" style="font-size:13px">Jenis</span>
                    <span class="fw-semibold text-capitalize" style="font-size:13px">{{ $module->jenis ?? 'Materi' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted" style="font-size:13px">Status</span>
                    <span class="fw-semibold text-capitalize" style="font-size:13px">{{ ucfirst($module->status) }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted" style="font-size:13px">Mata Pelajaran</span>
                    <span class="fw-semibold" style="font-size:13px">{{ $module->mataPelajaran?->nama ?? '—' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
@endsection
