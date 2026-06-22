@extends('layouts.app')
@section('title', 'Detail Guru — '.$teacher->name)
@section('page-title', 'Preview Guru')

@section('content')
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.teachers.index') }}">Data Guru</a></li>
        <li class="breadcrumb-item active">{{ $teacher->name }}</li>
    </ol>
</nav>

{{-- HEADER --}}
<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center gap-4" style="position:relative">
        @php
            $photoUrl = $teacher->photo ? asset('storage/'.$teacher->photo) : 'https://ui-avatars.com/api/?name='.urlencode($teacher->name).'&background=68117e&color=fff&size=120';
        @endphp
        <img src="{{ $photoUrl }}" alt="{{ $teacher->name }}"
             style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,.3);flex-shrink:0">
        <div style="flex:1">
            <div style="font-size:11px;opacity:.6;text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px">Profil Guru</div>
            <h4 class="fw-bold mb-1" style="color:white">{{ $teacher->name }}</h4>
            <div style="opacity:.8;font-size:13px">
                NIG: {{ $teacher->nig ?? '—' }}
                @if($teacher->branch)· {{ $teacher->branch->name }}@endif
                @if($teacher->status)
                · <span class="badge" style="background:{{ $teacher->status === 'aktif' ? 'rgba(16,185,129,.3)' : 'rgba(239,68,68,.3)' }};color:white;font-size:11px">{{ ucfirst($teacher->status) }}</span>
                @endif
            </div>
        </div>
        <div class="d-flex gap-2 flex-shrink-0">
            <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn fw-semibold" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
            <a href="{{ route('admin.teachers.index') }}" class="btn fw-semibold" style="background:rgba(255,255,255,.1);color:white;border:1px solid rgba(255,255,255,.2);border-radius:10px">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- LEFT --}}
    <div class="col-lg-8">

        {{-- DATA PRIBADI --}}
        <div class="dashboard-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-person-badge me-2 text-primary"></i>Data Pribadi</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="small text-muted">Nama Lengkap</div>
                    <div class="fw-semibold">{{ $teacher->name }}</div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">NIG</div>
                    <div class="fw-semibold">{{ $teacher->nig ?? '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Jenis Kelamin</div>
                    <div class="fw-semibold">{{ $teacher->gender === 'L' ? 'Laki-laki' : ($teacher->gender === 'P' ? 'Perempuan' : '—') }}</div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Tanggal Lahir</div>
                    <div class="fw-semibold">{{ $teacher->birth_date?->format('d M Y') ?? '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">No. HP</div>
                    <div class="fw-semibold">{{ $teacher->phone ?? '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Email</div>
                    <div class="fw-semibold">{{ $teacher->email ?? $teacher->user?->email ?? '—' }}</div>
                </div>
                <div class="col-12">
                    <div class="small text-muted">Mata Pelajaran Diampu</div>
                    @if($teacher->subjects && count($teacher->subjects))
                        <div class="d-flex flex-wrap gap-2 mt-1">
                            @foreach($teacher->subjects as $subj)
                            <span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);border:1px solid var(--soft-primary-border);font-size:12px;padding:5px 12px;border-radius:8px">{{ $subj }}</span>
                            @endforeach
                        </div>
                    @else
                        <div class="fw-semibold">—</div>
                    @endif
                </div>
                @if($teacher->bio)
                <div class="col-12">
                    <div class="small text-muted">Bio / Catatan</div>
                    <div class="fw-semibold">{{ $teacher->bio }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- PAKET YANG DIAJARKAN --}}
        @if($packages->count())
        <div class="dashboard-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-box-seam me-2 text-primary"></i>Paket yang Diajarkan</h6>
            <div class="row g-2">
                @foreach($packages as $pkg)
                <div class="col-md-6">
                    <div class="p-3 rounded-3" style="background:var(--soft-primary-bg);border:1px solid var(--soft-primary-border)">
                        <div class="fw-semibold text-primary mb-1">{{ $pkg->nama }}</div>
                        <div class="small text-muted">
                            <span class="me-2"><strong>Jenis:</strong> {{ $pkg->jenis ?? '—' }}</span>
                            <span><strong>Sesi:</strong> {{ $pkg->jumlah_pertemuan ?? '—' }}</span>
                        </div>
                        <div class="small text-muted mt-1">{{ $pkg->mataPelajaran->pluck('nama')->join(', ') ?: '—' }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- AKUN --}}
        @if($teacher->user)
        <div class="dashboard-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-person-lock me-2 text-primary"></i>Akun Login</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="small text-muted">Email</div>
                    <div class="fw-semibold">{{ $teacher->user->email }}</div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Status Akun</div>
                    <span class="badge" style="background:{{ $teacher->user->is_active ? 'var(--soft-success-bg)' : 'var(--soft-danger-bg)' }};color:{{ $teacher->user->is_active ? 'var(--soft-success-text)' : 'var(--soft-danger-text)' }};font-size:12px">
                        {{ $teacher->user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Bergabung</div>
                    <div class="fw-semibold">{{ $teacher->user->created_at?->format('d M Y') ?? '—' }}</div>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- RIGHT --}}
    <div class="col-lg-4">

        {{-- CABANG --}}
        <div class="dashboard-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-building me-2 text-primary"></i>Cabang</h6>
            @if($teacher->branch)
            <div class="fw-semibold">{{ $teacher->branch->name }}</div>
            <div class="small text-muted mt-1">{{ $teacher->branch->address ?? '—' }}</div>
            @else
            <div class="text-muted small">Semua Cabang / Pusat</div>
            @endif
        </div>

        {{-- STATUS --}}
        <div class="dashboard-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Status Guru</h6>
            <span class="badge" style="background:{{ $teacher->status === 'aktif' ? 'var(--soft-success-bg)' : 'var(--soft-danger-bg)' }};color:{{ $teacher->status === 'aktif' ? 'var(--soft-success-text)' : 'var(--soft-danger-text)' }};border:1px solid {{ $teacher->status === 'aktif' ? 'var(--soft-success-border)' : 'var(--soft-danger-border)' }};font-size:13px;padding:6px 14px;border-radius:10px">
                {{ ucfirst($teacher->status ?? '—') }}
            </span>
        </div>

        {{-- CV --}}
        @if($teacher->cv_path)
        <div class="dashboard-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-file-earmark-text me-2 text-primary"></i>CV / Dokumen</h6>
            <a href="{{ asset('storage/'.$teacher->cv_path) }}" target="_blank" class="btn btn-outline-primary btn-sm w-100" style="border-radius:10px">
                <i class="bi bi-download me-1"></i>Unduh CV
            </a>
        </div>
        @endif

    </div>
</div>

</div>
@endsection
