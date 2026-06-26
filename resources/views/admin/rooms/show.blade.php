@extends('layouts.app')
@section('title','Detail Ruangan')
@section('page-title','Detail Ruangan')

@section('content')
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.rooms.index') }}">Fasilitas Ruangan</a></li>
        <li class="breadcrumb-item active">{{ $room->nama_ruangan }}</li>
    </ol>
</nav>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="dashboard-card">
            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                <div style="width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:22px">
                    <i class="bi bi-door-open-fill"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">{{ $room->nama_ruangan }}</h5>
                    <span class="text-muted" style="font-size:13px">{{ $room->branch->name ?? '–' }}</span>
                </div>
                <div class="ms-auto">
                    @if($room->status === 'aktif')
                        <span class="badge" style="background:var(--soft-success);color:#10b981;font-size:13px;padding:6px 14px">
                            <i class="bi bi-check-circle me-1"></i>Bisa Digunakan
                        </span>
                    @else
                        <span class="badge" style="background:var(--soft-warning);color:#b45309;font-size:13px;padding:6px 14px">
                            <i class="bi bi-tools me-1"></i>Maintenance
                        </span>
                    @endif
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 rounded-3" style="background:var(--input-bg)">
                        <div class="text-muted" style="font-size:12px">Cabang</div>
                        <div class="fw-semibold mt-1">{{ $room->branch->name ?? '–' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded-3" style="background:var(--input-bg)">
                        <div class="text-muted" style="font-size:12px">Kapasitas Maksimal</div>
                        <div class="fw-semibold mt-1">{{ $room->kapasitas }} Siswa</div>
                    </div>
                </div>
                @if($room->keterangan)
                <div class="col-12">
                    <div class="p-3 rounded-3" style="background:var(--input-bg)">
                        <div class="text-muted" style="font-size:12px">Keterangan</div>
                        <div class="fw-semibold mt-1">{{ $room->keterangan }}</div>
                    </div>
                </div>
                @endif
                <div class="col-md-6">
                    <div class="p-3 rounded-3" style="background:var(--input-bg)">
                        <div class="text-muted" style="font-size:12px">Dibuat</div>
                        <div class="fw-semibold mt-1">{{ $room->created_at->format('d M Y') }}</div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-primary fw-semibold px-4">
                    <i class="bi bi-pencil me-2"></i>Edit Ruangan
                </a>
                <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary fw-semibold px-4">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
</div>

</div>
@endsection
