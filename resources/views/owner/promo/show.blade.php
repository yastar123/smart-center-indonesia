@extends('layouts.app')
@section('title', 'Detail Promo')
@section('page-title', 'Detail Promo')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3 fade-up">
    <a href="{{ route('owner.promo.index') }}" class="btn btn-sm"
       style="background:var(--input-bg);border:1px solid var(--card-border);color:var(--text-muted);border-radius:9px;font-size:13px">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <div class="d-flex gap-2">
        <a href="{{ route('owner.promo.edit', $promo->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:9px;font-size:13px">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <form action="{{ route('owner.promo.destroy', $promo->id) }}" method="POST" class="d-inline"
              onsubmit="return confirm('Hapus promo ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm" style="border-radius:9px;font-size:13px;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);color:#dc2626">
                <i class="bi bi-trash me-1"></i>Hapus
            </button>
        </form>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">

        {{-- Banner --}}
        @if($promo->banner_path)
        <div class="dashboard-card mb-4 fade-up p-0" style="overflow:hidden;border-radius:18px">
            <img src="{{ asset('storage/'.$promo->banner_path) }}" alt="{{ $promo->judul }}"
                 style="width:100%;max-height:300px;object-fit:cover;display:block">
        </div>
        @endif

        <div class="dashboard-card mb-4 fade-up" style="animation-delay:.04s">
            <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                <div>
                    <div class="text-muted mb-1" style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.08em">{{ $promo->kode }}</div>
                    <h5 class="fw-bold mb-2" style="color:var(--text-primary)">{{ $promo->judul }}</h5>
                    <span class="badge" style="background:rgba(200,77,223,.12);color:#c84ddf;font-size:11px;padding:4px 10px;border-radius:20px">
                        {{ $promo->tipe_label }}
                    </span>
                </div>
                @php
                    $sc = match($promo->status) {
                        'aktif'    => ['bg'=>'#dcfce7','text'=>'#16a34a'],
                        'berakhir' => ['bg'=>'rgba(148,163,184,.15)','text'=>'#64748b'],
                        default    => ['bg'=>'rgba(246,175,35,.15)','text'=>'#d97706'],
                    };
                @endphp
                <span class="badge flex-shrink-0" style="background:{{ $sc['bg'] }};color:{{ $sc['text'] }};font-size:12px;padding:6px 14px;border-radius:20px;font-weight:600">
                    {{ $promo->status_label }}
                </span>
            </div>

            @if($promo->kode_promo)
            <div class="p-3 mb-3" style="background:var(--input-bg);border-radius:12px;border:1px dashed var(--card-border)">
                <div class="text-muted mb-1" style="font-size:11px">KODE PROMO</div>
                <div class="fw-bold" style="font-size:18px;letter-spacing:.12em;color:#c84ddf;font-family:monospace">{{ $promo->kode_promo }}</div>
            </div>
            @endif

            @if($promo->deskripsi)
            <div style="font-size:14px;color:var(--text-primary);line-height:1.7">{{ $promo->deskripsi }}</div>
            @endif
        </div>

    </div>

    <div class="col-lg-4">
        <div class="dashboard-card mb-3 fade-up" style="animation-delay:.03s">
            <h6 class="fw-bold mb-3" style="color:var(--text-primary);font-size:13px;text-transform:uppercase;letter-spacing:.05em">Periode Tayang</h6>
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-calendar-event text-primary" style="font-size:14px"></i>
                <span style="font-size:13px">{{ $promo->tanggal_mulai->format('d M Y') }}</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-calendar-x text-danger" style="font-size:14px"></i>
                <span style="font-size:13px">{{ $promo->tanggal_berakhir->format('d M Y') }}</span>
            </div>
        </div>

        <div class="dashboard-card mb-3 fade-up" style="animation-delay:.05s">
            <h6 class="fw-bold mb-3" style="color:var(--text-primary);font-size:13px;text-transform:uppercase;letter-spacing:.05em">Target</h6>
            @php
                $targetLabel = [
                    'semua'          => 'Semua Siswa',
                    'paket_intensif' => 'Hanya Paket Intensif',
                    'cabang'         => 'Cabang: '.($promo->cabang?->name ?? '—'),
                    'cicilan'        => 'Hanya Siswa Cicilan',
                ][$promo->target] ?? $promo->target;
            @endphp
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-people-fill text-primary" style="font-size:14px"></i>
                <span style="font-size:13px">{{ $targetLabel }}</span>
            </div>
        </div>

        <div class="dashboard-card fade-up" style="animation-delay:.07s">
            <h6 class="fw-bold mb-3" style="color:var(--text-primary);font-size:13px;text-transform:uppercase;letter-spacing:.05em">Statistik</h6>
            <div class="row g-3">
                <div class="col-6">
                    <div class="text-center p-3" style="background:var(--input-bg);border-radius:12px">
                        <div class="fw-bold" style="font-size:22px;color:var(--text-primary)">{{ number_format($promo->views) }}</div>
                        <div class="text-muted" style="font-size:11px">Views</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="text-center p-3" style="background:rgba(200,77,223,.06);border-radius:12px">
                        <div class="fw-bold" style="font-size:22px;color:#c84ddf">{{ number_format($promo->claims) }}</div>
                        <div class="text-muted" style="font-size:11px">Claims</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
