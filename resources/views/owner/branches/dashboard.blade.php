@extends('layouts.app')
@section('title', 'Dashboard Cabang — ' . ($branch->name ?? ''))
@section('page-title', 'Dashboard Cabang')

@section('content')
<div class="dashboard-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-0">{{ $branch->name }}</h5>
            <div class="text-muted small">{{ $branch->city }} {{ $branch->regency ? '· '.$branch->regency : '' }}</div>
        </div>
        <div class="text-end">
            <a href="{{ route('owner.branches.index') }}" class="btn btn-sm btn-outline-secondary">Kembali ke Daftar Cabang</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-title">Total Siswa</div>
                <div class="stat-value text-primary">{{ $studentsCount }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-title">Status</div>
                <div class="stat-value">{{ ucfirst($branch->status) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-title">Kontak</div>
                <div class="stat-value">{{ $branch->phone ?? '-' }}</div>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <p class="text-muted">Halaman ringkasan cabang. Anda bisa menambahkan metrik lebih lengkap di sini sesuai kebutuhan (keuangan, guru, jadwal, dsb.).</p>
</div>
@endsection
