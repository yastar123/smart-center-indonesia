@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Owner Akademi</h3>
            <small class="text-muted">Dashboard Monitoring Seluruh Cabang</small>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="row g-3">

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary text-white mb-2">
                    <i class="bi bi-building"></i>
                </div>
                <div class="stat-value">12</div>
                <div class="stat-label">Cabang Aktif</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-success text-white mb-2">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-value">1.240</div>
                <div class="stat-label">Total Siswa</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning text-white mb-2">
                    <i class="bi bi-person-badge"></i>
                </div>
                <div class="stat-value">85</div>
                <div class="stat-label">Guru</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-danger text-white mb-2">
                    <i class="bi bi-cash"></i>
                </div>
                <div class="stat-value">Rp 120JT</div>
                <div class="stat-label">Pendapatan</div>
            </div>
        </div>

    </div>

    {{-- MENU QUICK ACCESS --}}
    <div class="row mt-4 g-3">

        <div class="col-md-3">
            <div class="card p-3">
                <h6>Monitoring Cabang</h6>
                <p class="text-muted small">Pantau semua cabang</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3">
                <h6>Laporan Keuangan</h6>
                <p class="text-muted small">Cashflow & laporan</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3">
                <h6>Manajemen User</h6>
                <p class="text-muted small">Admin, guru, siswa</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3">
                <h6>Tryout CBT</h6>
                <p class="text-muted small">Monitoring ujian</p>
            </div>
        </div>

    </div>

</div>
@endsection