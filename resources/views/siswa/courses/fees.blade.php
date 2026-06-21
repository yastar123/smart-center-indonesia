@extends('layouts.app')
@section('title','Daftar Paket Tersedia')
@section('page-title','Daftar Paket')

@section('content')

{{-- HEADER BANNER --}}
<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-3">
            <div style="width:52px;height:52px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
                <i class="bi bi-grid-3x3-gap-fill"></i>
            </div>
            <div>
                <div style="font-size:11px;opacity:.6;margin-bottom:3px;text-transform:uppercase;letter-spacing:.08em">Portal Siswa</div>
                <h4 class="fw-bold mb-1" style="color:white;letter-spacing:-.02em">Daftar Paket Tersedia</h4>
                <p class="mb-0" style="opacity:.75;font-size:13px">Pilih paket yang sesuai dan daftarkan diri Anda</p>
            </div>
        </div>
        <div class="text-end">
            <div style="font-size:28px;font-weight:800;color:white">{{ $packages->count() }}</div>
            <div style="font-size:12px;opacity:.7">Paket Tersedia</div>
        </div>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="dashboard-card mb-4 fade-up">
    <form id="filterForm" method="GET" action="{{ route('siswa.courses.fees') }}">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Mata Pelajaran</label>
                <select name="course_id" class="form-select form-select-sm" style="border-radius:8px" onchange="this.form.submit()">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($courses as $c)
                    <option value="{{ $c->id }}" {{ request('course_id') == $c->id ? 'selected' : '' }}>{{ $c->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Cabang</label>
                <select name="cabang_id" class="form-select form-select-sm" style="border-radius:8px" onchange="this.form.submit()">
                    <option value="">Semua Cabang</option>
                    @foreach($branches as $b)
                    <option value="{{ $b->id }}" {{ request('cabang_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Harga</label>
                <select name="harga_filter" class="form-select form-select-sm" style="border-radius:8px" onchange="this.form.submit()">
                    <option value="">Semua Harga</option>
                    <option value="0-500000" {{ request('harga_filter') == '0-500000' ? 'selected' : '' }}>Rp 0 - Rp 500.000</option>
                    <option value="500001-1000000" {{ request('harga_filter') == '500001-1000000' ? 'selected' : '' }}>Rp 500.001 - Rp 1.000.000</option>
                    <option value="1000001-999999999" {{ request('harga_filter') == '1000001-999999999' ? 'selected' : '' }}>Rp 1.000.001+</option>
                </select>
            </div>
            <div class="col-12 col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm w-100" style="border-radius:8px"><i class="bi bi-search"></i></button>
                @if(request()->hasAny(['course_id','cabang_id','harga_filter']))
                <a href="{{ route('siswa.courses.fees') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px" title="Reset"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </div>
    </form>
</div>

{{-- CARD GRID --}}
@if($packages->isEmpty())
<div class="dashboard-card fade-up">
    <div class="text-center py-5">
        <i class="bi bi-journal-x" style="font-size:56px;opacity:.25;display:block;margin-bottom:16px;color:var(--primary)"></i>
        <div class="fw-bold mb-1" style="font-size:17px">Tidak Ada Paket Ditemukan</div>
        <div class="text-muted" style="font-size:13px">Coba ubah atau hapus filter untuk melihat lebih banyak paket.</div>
        @if(request()->hasAny(['course_id','jenis','guru_id','harga_min','harga_max']))
        <a href="{{ route('siswa.courses.fees') }}" class="btn btn-outline-primary mt-3" style="border-radius:10px">
            <i class="bi bi-arrow-clockwise me-1"></i>Reset Filter
        </a>
        @endif
    </div>
</div>
@else
<div class="row g-3 fade-up">
    @foreach($packages as $package)
    @php
        $courseNames = $package->mataPelajaran->pluck('nama')->filter()->implode(', ');
        $statusLabel = match($package->status ?? '') {
            'aktif' => ['text' => 'Aktif', 'class' => 'bg-success'],
            'nonaktif' => ['text' => 'Nonaktif', 'class' => 'bg-secondary'],
            default => ['text' => ucfirst($package->status ?? '—'), 'class' => 'bg-light text-dark'],
        };
    @endphp
    <div class="col-12 col-md-6 col-xl-4">
        <div class="h-100" style="background:var(--card-bg);border:1.5px solid var(--card-border);border-radius:18px;overflow:hidden;display:flex;flex-direction:column;transition:box-shadow .2s, transform .2s"
             onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 32px rgba(104,17,126,.13)'"
             onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div style="background:linear-gradient(135deg,#260632,#68117e);padding:18px 20px;position:relative;overflow:hidden">
                <div style="position:absolute;right:-20px;top:-20px;width:100px;height:100px;background:rgba(255,255,255,.05);border-radius:50%"></div>
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div>
                        <div style="font-size:10px;opacity:.6;text-transform:uppercase;letter-spacing:.07em;color:white;margin-bottom:4px">Paket</div>
                        <div class="fw-bold" style="color:white;font-size:15px;line-height:1.3">{{ $package->nama }}</div>
                    </div>
                    <span class="badge {{ $statusLabel['class'] }}">{{ $statusLabel['text'] }}</span>
                </div>
            </div>
            <div style="padding:16px 20px;flex:1;display:flex;flex-direction:column;gap:10px">
                <div class="small text-muted">
                    <div class="mb-1"><strong>Jenis Paket:</strong> {{ $package->jenis ?? '-' }}</div>
                    <div class="mb-1"><strong>Jumlah Sesi:</strong> {{ $package->jumlah_pertemuan ?? 0 }}</div>
                    <div class="mb-1"><strong>Metode Absensi:</strong> {{ $package->metode_absensi ?? '-' }}</div>
                    <div class="mb-1"><strong>Tipe Kelas:</strong> {{ $package->tipe_kelas ?? '-' }}</div>
                    <div class="mb-1"><strong>Harga:</strong> <span class="fw-semibold text-primary">Rp {{ number_format((float) ($package->harga ?? 0), 0, ',', '.') }}</span></div>
                    <div class="mb-1"><strong>Cabang:</strong> {{ $package->cabang->name ?? 'Pusat' }}</div>
                    <div class="mb-1"><strong>Mata Pelajaran:</strong> {{ $courseNames ?: '-' }}</div>
                    <div class="mb-1"><strong>Deskripsi:</strong> {{ $package->deskripsi ?: '-' }}</div>
                </div>
                <div class="mt-auto pt-2">
                    <a href="{{ route('siswa.messages.index') }}" class="btn btn-primary btn-sm w-100" style="border-radius:10px">
                        <i class="bi bi-whatsapp me-1"></i>Hubungi Admin
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection
