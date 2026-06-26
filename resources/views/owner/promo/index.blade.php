@extends('layouts.app')
@section('title', 'Manajemen Promo')
@section('page-title', 'Manajemen Promo')

@section('content')

{{-- HEADER BANNER --}}
<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative;padding:28px">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-3">
            <div style="width:56px;height:56px;border-radius:18px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:26px;flex-shrink:0;border:1.5px solid rgba(255,255,255,.12)">
                <i class="bi bi-megaphone-fill"></i>
            </div>
            <div>
                <div style="font-size:11px;opacity:.55;margin-bottom:4px;text-transform:uppercase;letter-spacing:.1em">Owner Panel</div>
                <h4 style="font-weight:800;margin-bottom:4px;color:white;letter-spacing:-.03em;font-size:clamp(18px,2.5vw,24px)">
                    Manajemen Promo & Konten
                </h4>
                <p style="opacity:.65;margin:0;font-size:13px">Kelola seluruh penawaran yang muncul di dashboard portal siswa.</p>
            </div>
        </div>
        <a href="{{ route('owner.promo.create') }}"
           class="btn fw-bold px-4"
           style="background:white;color:#461256;border:none;border-radius:12px;font-size:13px;box-shadow:0 4px 12px rgba(0,0,0,.15);letter-spacing:.03em">
            <i class="bi bi-plus-lg me-2"></i>BUAT PROMO BARU
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert d-flex align-items-center gap-2 mb-4 fade-up" style="border-radius:12px;border:none;background:rgba(16,185,129,.1);color:#065f46">
    <i class="bi bi-check-circle-fill"></i>{{ session('success') }}
</div>
@endif

{{-- FILTERS --}}
<div class="dashboard-card mb-4 fade-up" style="animation-delay:.03s;padding:16px 20px">
    <form method="GET" class="d-flex align-items-center gap-3 flex-wrap">
        <div style="flex:1;min-width:220px;position:relative">
            <i class="bi bi-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control" placeholder="Cari judul promo..."
                   style="padding-left:34px;border-radius:10px;font-size:13px">
        </div>
        <select name="status" class="form-select" style="width:160px;border-radius:10px;font-size:13px" onchange="this.form.submit()">
            <option value="semua" {{ request('status','semua') === 'semua' ? 'selected' : '' }}>Semua Status</option>
            <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
            <option value="draft"    {{ request('status') === 'draft'    ? 'selected' : '' }}>Draft</option>
            <option value="berakhir" {{ request('status') === 'berakhir' ? 'selected' : '' }}>Berakhir</option>
        </select>
        <button type="submit" class="btn btn-primary" style="border-radius:10px;font-size:13px;padding:8px 18px">
            <i class="bi bi-funnel me-1"></i>Filter
        </button>
        @if(request('search') || (request('status') && request('status') !== 'semua'))
        <a href="{{ route('owner.promo.index') }}" class="btn" style="border-radius:10px;font-size:13px;border:1px solid var(--card-border);color:var(--text-muted)">Reset</a>
        @endif
    </form>
</div>

{{-- TABLE --}}
<div class="dashboard-card fade-up" style="animation-delay:.06s">
    @if($promos->isEmpty())
    <div class="text-center py-5">
        <div style="width:80px;height:80px;border-radius:50%;background:var(--input-bg);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
            <i class="bi bi-megaphone text-muted" style="font-size:2rem;opacity:.4"></i>
        </div>
        <h6 class="fw-semibold mb-2">Belum Ada Promo</h6>
        <p class="text-muted mb-4" style="font-size:13px">Mulai buat kampanye promo pertama Anda.</p>
        <a href="{{ route('owner.promo.create') }}" class="btn btn-primary" style="border-radius:10px">
            <i class="bi bi-plus-lg me-2"></i>Buat Promo Baru
        </a>
    </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th class="small text-muted fw-semibold py-3" style="min-width:240px">PROMO CAMPAIGN</th>
                    <th class="small text-muted fw-semibold py-3 d-none d-md-table-cell" style="width:180px">TIPE & PERIODE</th>
                    <th class="small text-muted fw-semibold py-3 d-none d-lg-table-cell text-center" style="width:180px">STATISTIK (VIEW/CLAIM)</th>
                    <th class="small text-muted fw-semibold py-3 text-center" style="width:110px">STATUS</th>
                    <th class="small text-muted fw-semibold py-3 text-center" style="width:90px">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($promos as $p)
                @php
                    $statusColor = match($p->status) {
                        'aktif'    => ['bg' => '#dcfce7', 'text' => '#16a34a'],
                        'berakhir' => ['bg' => 'rgba(148,163,184,.15)', 'text' => '#64748b'],
                        default    => ['bg' => 'rgba(246,175,35,.15)', 'text' => '#d97706'],
                    };
                    $tipeColor = match($p->tipe) {
                        'bundle_upgrade' => '#0284c7',
                        'special_price'  => '#10b981',
                        'lainnya'        => '#68117e',
                        default          => '#c84ddf',
                    };
                @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            {{-- Banner thumb --}}
                            <div style="width:52px;height:36px;border-radius:8px;overflow:hidden;flex-shrink:0;background:linear-gradient(135deg,#461256,#c84ddf)">
                                @if($p->banner_path)
                                <img src="{{ asset('storage/'.$p->banner_path) }}" alt="" style="width:100%;height:100%;object-fit:cover">
                                @else
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center">
                                    <i class="bi bi-megaphone-fill text-white" style="font-size:14px;opacity:.6"></i>
                                </div>
                                @endif
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:13.5px;color:var(--text-primary)">{{ $p->judul }}</div>
                                <div class="text-muted" style="font-size:11px">ID: {{ $p->kode }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <span class="badge mb-1" style="background:{{ $tipeColor }}18;color:{{ $tipeColor }};font-size:10.5px;padding:3px 8px;border-radius:6px;display:block;width:fit-content">
                            {{ $p->tipe_label }}
                        </span>
                        <div class="text-muted" style="font-size:11px">
                            {{ $p->tanggal_mulai->format('d M') }} – {{ $p->tanggal_berakhir->format('d M Y') }}
                        </div>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <div class="d-flex justify-content-center gap-4">
                            <div class="text-center">
                                <div class="fw-bold" style="font-size:16px;color:var(--text-primary)">{{ number_format($p->views) }}</div>
                                <div class="text-muted" style="font-size:10px">Views</div>
                            </div>
                            <div style="width:1px;background:var(--card-border)"></div>
                            <div class="text-center">
                                <div class="fw-bold" style="font-size:16px;color:#c84ddf">{{ number_format($p->claims) }}</div>
                                <div class="text-muted" style="font-size:10px">Claims</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge" style="background:{{ $statusColor['bg'] }};color:{{ $statusColor['text'] }};font-size:11.5px;padding:5px 12px;border-radius:20px;font-weight:600">
                            {{ $p->status_label }}
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('owner.promo.show', $p->id) }}"
                           class="btn btn-sm"
                           style="background:var(--input-bg);border:1px solid var(--card-border);color:var(--text-muted);border-radius:8px;font-size:12px;padding:5px 12px">
                            <i class="bi bi-eye me-1"></i>Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($promos->hasPages())
    <div class="mt-4 d-flex justify-content-center">
        {{ $promos->links() }}
    </div>
    @endif
    @endif
</div>

@endsection
