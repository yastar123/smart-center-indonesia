@extends('layouts.app')

@section('title', 'Detail Mata Pelajaran')
@section('page-title', 'Detail Mata Pelajaran')

@section('content')
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('owner.subject.index') }}">Mata Pelajaran</a></li>
        <li class="breadcrumb-item active">{{ $subject->nama }}</li>
    </ol>
</nav>

<div class="row g-3">
    {{-- INFO CARD --}}
    <div class="col-md-4">
        <div class="dashboard-card h-100">
            <div class="text-center mb-3">
                <div style="width:80px;height:80px;border-radius:20px;background:linear-gradient(135deg,#260632,#c84ddf);display:flex;align-items:center;justify-content:center;font-size:36px;color:white;margin:0 auto 12px">
                    <i class="bi bi-book-half"></i>
                </div>
                <h5 class="fw-bold mb-1">{{ $subject->nama }}</h5>
                <code class="text-muted">{{ $subject->kode }}</code>
            </div>

            <div class="d-flex justify-content-center gap-2 mb-3">
                @if($subject->kategori === 'academic')
                    <span class="badge px-3 py-2" style="background:rgba(59,130,246,.15);color:#3b82f6">
                        <i class="bi bi-mortarboard me-1"></i>Academic
                    </span>
                @else
                    <span class="badge px-3 py-2" style="background:rgba(246,175,35,.15);color:#e09000">
                        <i class="bi bi-lightning me-1"></i>Skill / Soft-Skill
                    </span>
                @endif
                @if($subject->status === 'aktif')
                    <span class="badge px-3 py-2" style="background:rgba(16,185,129,.15);color:#059669">Aktif</span>
                @else
                    <span class="badge px-3 py-2" style="background:rgba(239,68,68,.15);color:#dc2626">Tidak Aktif</span>
                @endif
            </div>

            @if($subject->deskripsi)
            <p class="text-muted text-center" style="font-size:13px">{{ $subject->deskripsi }}</p>
            @endif

            <div class="border-top pt-3 mt-2">
                <div class="d-flex justify-content-between mb-1"><span class="text-muted" style="font-size:13px">Cabang</span><span class="fw-semibold" style="font-size:13px">{{ $subject->cabang->name ?? 'Semua' }}</span></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-muted" style="font-size:13px">Total Kelas</span><span class="fw-semibold" style="font-size:13px">{{ $subject->kelas->count() }}</span></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-muted" style="font-size:13px">Total Guru</span><span class="fw-semibold" style="font-size:13px">{{ $subject->guru->count() }}</span></div>
                <div class="d-flex justify-content-between"><span class="text-muted" style="font-size:13px">Total Modul</span><span class="fw-semibold" style="font-size:13px">{{ $subject->modul->count() }}</span></div>
            </div>

            <div class="d-grid gap-2 mt-3">
                <a href="{{ route('owner.subject.edit', $subject) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Edit Mata Pelajaran
                </a>
                <a href="{{ route('owner.subject.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- PAKET & KELAS --}}
    <div class="col-md-8">
        <div class="dashboard-card mb-3">
            <h6 class="fw-bold mb-3"><i class="bi bi-box-seam me-2 text-primary"></i>Paket Belajar Terkait</h6>
            @forelse($subject->paket as $p)
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                    <div>
                        <div class="fw-semibold">{{ $p->nama }}</div>
                        <div class="text-muted" style="font-size:12px">{{ $p->jumlah_pertemuan }} sesi · {{ $p->jenis }}</div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-primary">Rp{{ number_format($p->harga, 0, ',', '.') }}</div>
                        @if($p->status === 'aktif')
                            <span class="badge" style="background:rgba(16,185,129,.15);color:#059669;font-size:10px">Aktif</span>
                        @else
                            <span class="badge" style="background:rgba(246,175,35,.15);color:#e09000;font-size:10px">Draft</span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-muted mb-0" style="font-size:13px">Belum ada paket yang menggunakan mapel ini.</p>
            @endforelse
        </div>

        <div class="dashboard-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-diagram-3 me-2 text-success"></i>Kelas Aktif</h6>
            @forelse($subject->kelas->where('status','aktif') as $k)
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                    <div>
                        <div class="fw-semibold">{{ $k->nama_kelas }}</div>
                        <div class="text-muted" style="font-size:12px">{{ $k->guru->name ?? '—' }} · {{ $k->jenis }}</div>
                    </div>
                    <span class="badge" style="background:rgba(16,185,129,.15);color:#059669">Aktif</span>
                </div>
            @empty
                <p class="text-muted mb-0" style="font-size:13px">Belum ada kelas untuk mapel ini.</p>
            @endforelse
        </div>
    </div>
</div>

</div>
@endsection
