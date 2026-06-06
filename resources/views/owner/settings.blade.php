@extends('layouts.app')
@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan')

@section('content')

{{-- HEADER --}}
<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632,#461256,#68117e);color:white;border:none">
    <div class="d-flex align-items-center gap-3">
        <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px">
            <i class="bi bi-gear-fill"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-0" style="color:white">Pengaturan Sistem</h5>
            <span style="font-size:12px;opacity:.8">Konfigurasi platform Akademi Bimbel</span>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- GENERAL SETTINGS --}}
    <div class="col-lg-8">
        <div class="dashboard-card fade-up mb-4">
            <h6 class="fw-bold mb-4"><i class="bi bi-building me-2 text-primary"></i>Informasi Lembaga</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:12px">Nama Lembaga</label>
                    <input type="text" class="form-control" value="Smart Center Indonesia" style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:12px">Singkatan</label>
                    <input type="text" class="form-control" value="SCI" style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:12px">Email Utama</label>
                    <input type="email" class="form-control" placeholder="admin@smartcenter.id" style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:12px">Nomor Telepon</label>
                    <input type="text" class="form-control" placeholder="08xxx" style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold" style="font-size:12px">Alamat Pusat</label>
                    <textarea class="form-control" rows="2" placeholder="Alamat kantor pusat..." style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)"></textarea>
                </div>
            </div>
            <div class="mt-3 d-flex justify-content-end">
                <button class="btn btn-primary px-4 fw-semibold" style="border-radius:10px" onclick="Swal.fire({icon:'info',title:'Coming Soon',text:'Fitur simpan pengaturan akan segera tersedia.',timer:2000,showConfirmButton:false})">
                    <i class="bi bi-save me-2"></i>Simpan Perubahan
                </button>
            </div>
        </div>

        {{-- ACADEMIC SETTINGS --}}
        <div class="dashboard-card fade-up mb-4">
            <h6 class="fw-bold mb-4"><i class="bi bi-mortarboard me-2 text-success"></i>Pengaturan Akademik</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:12px">Tahun Ajaran Aktif</label>
                    <input type="text" class="form-control" value="2025/2026" style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:12px">Semester Aktif</label>
                    <select class="form-select" style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                        <option>Semester 1 (Ganjil)</option>
                        <option>Semester 2 (Genap)</option>
                    </select>
                </div>
            </div>
            <div class="mt-3 d-flex justify-content-end">
                <button class="btn btn-success px-4 fw-semibold" style="border-radius:10px" onclick="Swal.fire({icon:'info',title:'Coming Soon',text:'Fitur simpan pengaturan akan segera tersedia.',timer:2000,showConfirmButton:false})">
                    <i class="bi bi-save me-2"></i>Simpan
                </button>
            </div>
        </div>
    </div>

    {{-- SIDE PANEL --}}
    <div class="col-lg-4">
        {{-- SYSTEM INFO --}}
        <div class="dashboard-card fade-up mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2" style="color:#68117e"></i>Info Sistem</h6>
            @php
                $sysInfo = [
                    ['label'=>'Laravel', 'value'=>app()->version()],
                    ['label'=>'PHP', 'value'=>PHP_VERSION],
                    ['label'=>'Environment', 'value'=>app()->environment()],
                    ['label'=>'Debug Mode', 'value'=>config('app.debug') ? 'On' : 'Off'],
                    ['label'=>'Timezone', 'value'=>config('app.timezone')],
                ];
            @endphp
            @foreach($sysInfo as $info)
            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid var(--card-border)">
                <span style="font-size:12px;color:#6b7280">{{ $info['label'] }}</span>
                <span style="font-size:12px;font-weight:600">{{ $info['value'] }}</span>
            </div>
            @endforeach
        </div>

        {{-- QUICK ACTIONS --}}
        <div class="dashboard-card fade-up">
            <h6 class="fw-bold mb-3"><i class="bi bi-lightning me-2 text-warning"></i>Aksi Cepat</h6>
            <div class="d-grid gap-2">
                <a href="{{ route('owner.branches.index') }}" class="btn btn-outline-primary text-start" style="border-radius:10px;font-size:13px">
                    <i class="bi bi-building me-2"></i>Kelola Cabang
                </a>
                <a href="{{ route('owner.activity-log') }}" class="btn btn-outline-secondary text-start" style="border-radius:10px;font-size:13px">
                    <i class="bi bi-journal-text me-2"></i>Log Aktivitas
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-success text-start" style="border-radius:10px;font-size:13px">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard Owner
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
