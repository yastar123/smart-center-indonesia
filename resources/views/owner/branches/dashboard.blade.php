@extends('layouts.app')
@section('title', 'Dashboard Cabang — ' . ($branch->name ?? ''))
@section('page-title', 'Dashboard Cabang')

@section('content')

{{-- HEADER --}}
<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                    <i class="bi bi-building-fill"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">{{ $branch->name }}</h5>
                    <span style="font-size:12px;opacity:.8">
                        <i class="bi bi-geo-alt me-1"></i>{{ $branch->city }}{{ $branch->regency ? ' · '.$branch->regency : '' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('owner.branches.index') }}" class="btn fw-semibold px-4"
               style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 fade-up">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Siswa</div>
                    <div class="stat-value text-primary">{{ $studentsCount }}</div>
                    <div class="stat-growth text-muted"><i class="bi bi-mortarboard me-1"></i>Terdaftar</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-people-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 fade-up" style="animation-delay:.05s">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Status Cabang</div>
                    <div class="stat-value {{ $branch->status === 'active' ? 'text-success' : 'text-warning' }}" style="font-size:18px">
                        {{ ucfirst($branch->status ?? '-') }}
                    </div>
                    <div class="stat-growth {{ $branch->status === 'active' ? 'text-success' : 'text-warning' }}">
                        <i class="bi bi-circle-fill me-1" style="font-size:8px"></i>{{ $branch->status === 'active' ? 'Beroperasi' : 'Tidak Aktif' }}
                    </div>
                </div>
                <div class="stat-icon {{ $branch->status === 'active' ? 'bg-success-soft' : 'bg-warning-soft' }}" style="color:white">
                    <i class="bi bi-building-fill-check"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 fade-up" style="animation-delay:.10s">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Kontak</div>
                    <div class="stat-value" style="font-size:16px">{{ $branch->phone ?? '–' }}</div>
                    <div class="stat-growth text-muted"><i class="bi bi-telephone me-1"></i>Nomor cabang</div>
                </div>
                <div class="stat-icon bg-info-soft" style="color:white"><i class="bi bi-telephone-fill"></i></div>
            </div>
        </div>
    </div>
</div>

{{-- INFO CARD --}}
<div class="dashboard-card fade-up">
    <h6 class="fw-bold mb-4">
        <i class="bi bi-info-circle text-primary me-2"></i>Informasi Cabang
    </h6>
    <div class="row g-4">
        @if($branch->address)
        <div class="col-md-6">
            <div class="data-label">Alamat</div>
            <div class="data-value">{{ $branch->address }}</div>
        </div>
        @endif
        @if($branch->email)
        <div class="col-md-6">
            <div class="data-label">Email</div>
            <div class="data-value">{{ $branch->email }}</div>
        </div>
        @endif
        @if($branch->city)
        <div class="col-md-6">
            <div class="data-label">Kota</div>
            <div class="data-value">{{ $branch->city }}</div>
        </div>
        @endif
        <div class="col-12">
            <div class="alert d-flex gap-3 align-items-start mb-0" style="border-radius:12px;border:none;background:rgba(200,77,223,.07);border-left:4px solid #c84ddf">
                <i class="bi bi-info-circle-fill text-primary mt-1" style="font-size:18px;flex-shrink:0"></i>
                <div>
                    <div class="fw-semibold mb-1" style="font-size:13px">Dashboard Cabang</div>
                    <p class="mb-0 text-muted" style="font-size:12px">Halaman ini menampilkan ringkasan cabang. Metrik detail keuangan, jadwal, dan guru tersedia melalui menu admin cabang yang bersangkutan.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
