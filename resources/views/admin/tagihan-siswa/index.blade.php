@extends('layouts.app')
@section('title', 'Tagihan Siswa & Verifikasi Pembayaran')
@section('page-title', 'Tagihan Siswa & Verifikasi Pembayaran')

@section('content')
<div class="fade-up">

{{-- HEADER --}}
<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Tagihan Siswa & Verifikasi Pembayaran</h5>
                    <span style="font-size:12px;opacity:.8">Kelola tagihan kelas, invoice pendaftaran, dan verifikasi bukti bayar</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            @if(($counts['pending'] ?? 0) > 0)
            <span class="badge fw-semibold px-3 py-2" style="background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.3);border-radius:10px;font-size:12px">
                <i class="bi bi-clock me-1"></i>{{ $counts['pending'] }} Menunggu Verifikasi
            </span>
            @endif
        </div>
    </div>
</div>

{{-- FLASH --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3"><i class="bi bi-x-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

{{-- MAIN 4-TAB NAV --}}
<ul class="nav nav-pills mb-4 gap-2 flex-wrap fade-up" id="mainTabNav">
    <li class="nav-item">
        <button class="nav-link {{ !in_array(request('tab'), ['kelas','registrasi','verifikasi']) ? 'active' : '' }}"
                onclick="switchTab('siswa')" id="btn-tab-siswa"
                style="border-radius:10px;font-size:14px;padding:8px 18px">
            <i class="bi bi-wallet2 me-2"></i>Tagihan Siswa
            @if($stats['menunggu'] > 0)
            <span class="badge ms-1" style="background:rgba(239,68,68,.2);color:#ef4444">{{ $stats['menunggu'] }}</span>
            @endif
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link {{ request('tab') === 'kelas' ? 'active' : '' }}"
                onclick="switchTab('kelas')" id="btn-tab-kelas"
                style="border-radius:10px;font-size:14px;padding:8px 18px">
            <i class="bi bi-diagram-3 me-2"></i>Tagihan Kelas
            @if($stats['total'] > 0)
            <span class="badge ms-1" style="background:rgba(200,77,223,.2);color:#c84ddf">{{ $stats['total'] }}</span>
            @endif
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link {{ request('tab') === 'registrasi' ? 'active' : '' }}"
                onclick="switchTab('registrasi')" id="btn-tab-registrasi"
                style="border-radius:10px;font-size:14px;padding:8px 18px">
            <i class="bi bi-file-earmark-text me-2"></i>Tagihan Registrasi
            @if($stats['reg_belum'] > 0)
            <span class="badge bg-danger ms-1">{{ $stats['reg_belum'] }}</span>
            @endif
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link {{ request('tab') === 'verifikasi' ? 'active' : '' }}"
                onclick="switchTab('verifikasi')" id="btn-tab-verifikasi"
                style="border-radius:10px;font-size:14px;padding:8px 18px">
            <i class="bi bi-shield-check me-2"></i>Verifikasi Pembayaran
            @if(($counts['pending'] ?? 0) + ($pkgCounts['pending'] ?? 0) > 0)
            <span class="badge bg-danger ms-1">{{ ($counts['pending'] ?? 0) + ($pkgCounts['pending'] ?? 0) }}</span>
            @endif
        </button>
    </li>
</ul>

{{-- ══════════════════ PANE: TAGIHAN SISWA (overview stats) ══════════════════ --}}
<div id="pane-tab-siswa" class="{{ in_array(request('tab'), ['kelas','registrasi','verifikasi']) ? 'd-none' : '' }}">
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-2 fade-up">
            <div class="stat-card" style="border-top:3px solid #c84ddf">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title">Total Kelas</div>
                        <div class="stat-value text-primary count-up" data-target="{{ $stats['total'] }}">{{ $stats['total'] }}</div>
                    </div>
                    <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-people"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 fade-up" style="animation-delay:.05s">
            <div class="stat-card" style="border-top:3px solid #f6af23">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title">Cicilan</div>
                        <div class="stat-value text-warning count-up" data-target="{{ $stats['cicilan'] }}">{{ $stats['cicilan'] }}</div>
                    </div>
                    <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-pie-chart"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 fade-up" style="animation-delay:.10s">
            <div class="stat-card" style="border-top:3px solid #0ea5e9">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title">Pascabayar</div>
                        <div class="stat-value count-up" data-target="{{ $stats['postpaid'] }}" style="color:#0ea5e9">{{ $stats['postpaid'] }}</div>
                    </div>
                    <div class="stat-icon bg-info-soft" style="color:white"><i class="bi bi-receipt"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 fade-up" style="animation-delay:.12s">
            <div class="stat-card" style="border-top:3px solid #8b5cf6">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title">Invoice Registrasi</div>
                        <div class="stat-value count-up" data-target="{{ $stats['reg_total'] }}" style="color:#8b5cf6">{{ $stats['reg_total'] }}</div>
                        <div class="text-muted" style="font-size:11px">{{ $stats['reg_belum'] }} belum bayar</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(139,92,246,.15);color:white"><i class="bi bi-file-earmark-text"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
            <div class="stat-card" style="border-top:3px solid #ef4444">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title">Invoice Belum Lunas</div>
                        <div class="stat-value text-danger count-up" data-target="{{ $stats['menunggu'] }}">{{ $stats['menunggu'] }}</div>
                    </div>
                    <div class="stat-icon bg-danger-soft" style="color:white"><i class="bi bi-exclamation-triangle"></i></div>
                </div>
            </div>
        </div>
    </div>

</div>{{-- /pane-tab-siswa --}}

{{-- ══════════════════ PANE: TAGIHAN KELAS ══════════════════ --}}
<div id="pane-tab-kelas" class="{{ request('tab') !== 'kelas' ? 'd-none' : '' }}">
        <div class="dashboard-card mb-4">
            <form method="GET" action="{{ route('admin.tagihan-siswa.index') }}">
                <input type="hidden" name="tab" value="kelas">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="font-size:12px">Cari (Nama Siswa / Kelas)</label>
                        <div class="input-group">
                            <span class="input-group-text" style="background:var(--input-bg);border-color:var(--card-border)"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..."
                                   class="form-control" style="border-color:var(--card-border);background:var(--input-bg)">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="font-size:12px">Tipe Tagihan</label>
                        <select name="billing_mode" class="form-select" style="border-color:var(--card-border);background:var(--input-bg)">
                            <option value="">Semua Tipe</option>
                            <option value="cicilan"  {{ request('billing_mode')=='cicilan' ?'selected':'' }}>Cicilan (Prabayar)</option>
                            <option value="postpaid" {{ request('billing_mode')=='postpaid'?'selected':'' }}>Pascabayar (Per Sesi)</option>
                        </select>
                    </div>
                    @if(auth()->user()->hasRole('owner'))
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="font-size:12px">Cabang</label>
                        <select name="cabang_id" class="form-select" style="border-color:var(--card-border);background:var(--input-bg)">
                            <option value="">Semua Cabang</option>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}" {{ request('cabang_id')==$b->id?'selected':'' }}>{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill fw-semibold">Filter</button>
                        <a href="{{ route('admin.tagihan-siswa.index') }}" class="btn btn-outline-secondary px-3">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="dashboard-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="background:var(--input-bg)">
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Nama Siswa / Kelas</th>
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Paket</th>
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Harga Paket</th>
                            <th class="text-center" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Tipe</th>
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Dibayar</th>
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Sisa</th>
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Cabang</th>
                            <th class="text-center" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $kelas)
                        @php
                            $siswa = $kelas->siswa->first();
                            $siswaNama = $siswa?->user?->name ?? $siswa?->name ?? '—';
                            $paket = $siswa?->package;
                            $billingLabel = $kelas->billing_mode === 'postpaid' ? 'Pascabayar' : 'Cicilan';
                            $billingColor = $kelas->billing_mode === 'postpaid' ? '#0ea5e9' : '#f6af23';
                            $billingBg    = $kelas->billing_mode === 'postpaid' ? 'rgba(14,165,233,.12)' : 'rgba(246,175,35,.15)';
                            $kelasInvoices = $invoicesByKelas[$kelas->id] ?? collect();
                            $totalTagihan  = $kelasInvoices->sum('total');
                            $totalDibayar  = $kelasInvoices->flatMap->pembayaran->where('status', 'verified')->sum('jumlah');
                            $sisaCicilan   = max(0, $totalTagihan - $totalDibayar);
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-semibold" style="font-size:13px">{{ $siswaNama }}</div>
                                <div class="text-muted" style="font-size:12px"><i class="bi bi-diagram-3 me-1"></i>{{ $kelas->nama_kelas }}</div>
                            </td>
                            <td style="font-size:13px">
                                @if($paket)
                                    <div class="fw-semibold">{{ $paket->nama }}</div>
                                    <div class="text-muted" style="font-size:11px">{{ ucfirst($paket->jenis ?? '—') }} · {{ $paket->jumlah_pertemuan ?? '?' }} sesi</div>
                                @else <span class="text-muted">—</span> @endif
                            </td>
                            <td style="font-size:13px">
                                @if($paket?->harga)
                                    <span class="fw-semibold">Rp {{ number_format($paket->harga, 0, ',', '.') }}</span>
                                @else <span class="text-muted">—</span> @endif
                            </td>
                            <td class="text-center">
                                <span style="background:{{ $billingBg }};color:{{ $billingColor }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600">{{ $billingLabel }}</span>
                            </td>
                            <td style="font-size:13px">
                                @if($totalDibayar > 0)
                                    <span class="fw-semibold text-success">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</span>
                                @else <span class="text-muted">—</span> @endif
                            </td>
                            <td style="font-size:13px">
                                @if($sisaCicilan > 0)
                                    <span class="fw-semibold text-danger">Rp {{ number_format($sisaCicilan, 0, ',', '.') }}</span>
                                @elseif($totalTagihan > 0)
                                    <span class="text-success fw-semibold"><i class="bi bi-check-circle me-1"></i>Lunas</span>
                                @else <span class="text-muted">—</span> @endif
                            </td>
                            <td style="font-size:13px">{{ $kelas->cabang?->name ?? '—' }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.tagihan-siswa.show', $kelas->id) }}"
                                   class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:11px">
                                    <i class="bi bi-eye me-1"></i>Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div style="font-size:40px;opacity:.3;margin-bottom:8px"><i class="bi bi-wallet2"></i></div>
                                <div class="text-muted">Belum ada data tagihan siswa cicilan atau pascabayar</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($classes->hasPages())
            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-top">
                <div class="text-muted" style="font-size:13px">{{ $classes->firstItem() }}–{{ $classes->lastItem() }} dari {{ $classes->total() }}</div>
                {{ $classes->appends(request()->all())->links() }}
            </div>
            @endif
        </div>
    </div>

{{-- ══════════════════ PANE: TAGIHAN REGISTRASI ══════════════════ --}}
<div id="pane-tab-registrasi" class="{{ request('tab') !== 'registrasi' ? 'd-none' : '' }}">
        <div class="dashboard-card mb-4">
            <form method="GET" action="{{ route('admin.tagihan-siswa.index') }}">
                <input type="hidden" name="tab" value="registrasi">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="font-size:12px">Cari (Nama Siswa / No Invoice)</label>
                        <div class="input-group">
                            <span class="input-group-text" style="background:var(--input-bg);border-color:var(--card-border)"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..."
                                   class="form-control" style="border-color:var(--card-border);background:var(--input-bg)">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="font-size:12px">Status Invoice</label>
                        <select name="reg_status" class="form-select" style="border-color:var(--card-border);background:var(--input-bg)">
                            <option value="">Semua Status</option>
                            <option value="belum_bayar" {{ request('reg_status')=='belum_bayar'?'selected':'' }}>Belum Dibayar</option>
                            <option value="sebagian"    {{ request('reg_status')=='sebagian'   ?'selected':'' }}>Sebagian</option>
                            <option value="lunas"       {{ request('reg_status')=='lunas'      ?'selected':'' }}>Lunas</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill fw-semibold">Filter</button>
                        <a href="{{ route('admin.tagihan-siswa.index', ['tab'=>'registrasi']) }}"
                           class="btn btn-outline-secondary px-3">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="dashboard-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="background:var(--input-bg)">
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">No Invoice</th>
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Nama Siswa</th>
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Program</th>
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Total</th>
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Jatuh Tempo</th>
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Cabang</th>
                            <th class="text-center" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $invStatusMap = [
                                'belum_bayar' => ['rgba(239,68,68,.1)','#ef4444','Belum Dibayar'],
                                'sebagian'    => ['rgba(245,158,11,.1)','#f59e0b','Sebagian'],
                                'lunas'       => ['rgba(16,185,129,.1)','#10b981','Lunas'],
                            ];
                        @endphp
                        @forelse($registrationInvoices as $inv)
                        @php $sc = $invStatusMap[$inv->status] ?? ['rgba(100,116,139,.1)','#64748b',$inv->status]; @endphp
                        <tr>
                            <td><span style="font-size:11px;font-family:monospace;color:var(--text-muted)">{{ $inv->nomor_invoice }}</span></td>
                            <td>
                                <div class="fw-semibold" style="font-size:13px">{{ $inv->siswa?->name ?? '—' }}</div>
                                <div class="text-muted" style="font-size:11px">{{ $inv->siswa?->user?->email ?? '' }}</div>
                            </td>
                            <td style="font-size:12px;max-width:200px"><span class="text-muted">{{ $inv->deskripsi }}</span></td>
                            <td><span class="fw-bold" style="font-size:14px">Rp {{ number_format($inv->total, 0, ',', '.') }}</span></td>
                            <td style="font-size:12px">{{ $inv->jatuh_tempo ? \Carbon\Carbon::parse($inv->jatuh_tempo)->isoFormat('D MMM YYYY') : '—' }}</td>
                            <td style="font-size:12px">{{ $inv->cabang?->name ?? '—' }}</td>
                            <td class="text-center">
                                <span class="fw-semibold" style="background:{{ $sc[0] }};color:{{ $sc[1] }};padding:3px 12px;border-radius:20px;font-size:11px">{{ $sc[2] }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div style="font-size:40px;opacity:.3;margin-bottom:8px"><i class="bi bi-file-earmark-text"></i></div>
                                <div class="text-muted">Belum ada invoice dari pendaftaran siswa</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($registrationInvoices->hasPages())
            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-top">
                <div class="text-muted" style="font-size:13px">{{ $registrationInvoices->firstItem() }}–{{ $registrationInvoices->lastItem() }} dari {{ $registrationInvoices->total() }}</div>
                {{ $registrationInvoices->appends(request()->all())->links() }}
            </div>
            @endif
        </div>
    </div>

</div>{{-- /pane-tab-registrasi --}}

{{-- ══════════════════ PANE: VERIFIKASI PEMBAYARAN ══════════════════ --}}
<div id="pane-tab-verifikasi" class="{{ request('tab') !== 'verifikasi' ? 'd-none' : '' }}">

    {{-- Summary pills --}}
    <div class="d-flex gap-2 flex-wrap mb-3">
        <span class="badge" style="background:rgba(245,158,11,.12);color:#f59e0b;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600">
            <i class="bi bi-hourglass-split me-1"></i>{{ $counts['pending'] }} Menunggu
        </span>
        <span class="badge" style="background:rgba(16,185,129,.12);color:#10b981;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600">
            <i class="bi bi-check-circle me-1"></i>{{ $counts['verified'] }} Disetujui
        </span>
        <span class="badge" style="background:rgba(239,68,68,.12);color:#ef4444;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600">
            <i class="bi bi-x-circle me-1"></i>{{ $counts['rejected'] }} Ditolak
        </span>
        @if(($pkgCounts['pending'] ?? 0) > 0)
        <span class="badge" style="background:rgba(139,92,246,.12);color:#8b5cf6;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600">
            <i class="bi bi-box-seam me-1"></i>{{ $pkgCounts['pending'] }} Paket Menunggu
        </span>
        @endif
    </div>

    {{-- SUB TABS verifikasi --}}
    <ul class="nav nav-pills mb-3 gap-2">
        <li class="nav-item">
            <button class="nav-link active" onclick="showVerTab('invoice')" id="ver-tab-invoice"
                    style="border-radius:10px;font-size:13px">
                <i class="bi bi-receipt me-1"></i>Tagihan / Invoice
                @if($counts['pending'] > 0)
                <span class="badge bg-danger ms-1">{{ $counts['pending'] }}</span>
                @endif
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" onclick="showVerTab('package')" id="ver-tab-package"
                    style="border-radius:10px;font-size:13px">
                <i class="bi bi-box-seam me-1"></i>Pembayaran Paket
                @if($pkgCounts['pending'] > 0)
                <span class="badge bg-danger ms-1">{{ $pkgCounts['pending'] }}</span>
                @endif
            </button>
        </li>
    </ul>

    {{-- VER PANE 1: Invoice / Registration Payments --}}
    <div id="ver-pane-invoice">
        <div class="dashboard-card mb-4">
            <form method="GET" class="row g-2 align-items-end">
                <input type="hidden" name="tab" value="verifikasi">
                <input type="hidden" name="pkg_status" value="{{ request('pkg_status','pending') }}">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold" style="font-size:12px">Cari Siswa / No Pembayaran</label>
                    <input type="text" name="ver_search" class="form-control form-control-sm"
                           placeholder="Nama siswa atau nomor pembayaran..." value="{{ request('ver_search') }}">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold" style="font-size:12px">Status</label>
                    <select name="ver_status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="pending"  {{ (request('ver_status','pending'))=='pending'  ? 'selected' : '' }}>Menunggu</option>
                        <option value="verified" {{ request('ver_status')=='verified' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('ver_status')=='rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Filter</button>
                    <a href="{{ route('admin.tagihan-siswa.index', ['tab'=>'verifikasi']) }}"
                       class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
                </div>
            </form>
        </div>

        <div class="dashboard-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="background:var(--input-bg);color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.05em">
                            <th class="px-3 py-3">No Pembayaran</th>
                            <th class="py-3">Siswa</th>
                            <th class="py-3">Invoice</th>
                            <th class="py-3">Jumlah</th>
                            <th class="py-3">Metode</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $payStatusMap = [
                                'pending'  => ['bg'=>'rgba(245,158,11,.1)','color'=>'#f59e0b','label'=>'Menunggu'],
                                'verified' => ['bg'=>'rgba(16,185,129,.1)','color'=>'#10b981','label'=>'Disetujui'],
                                'rejected' => ['bg'=>'rgba(239,68,68,.1)','color'=>'#ef4444','label'=>'Ditolak'],
                            ];
                        @endphp
                        @forelse($payments as $payment)
                        @php $psc = $payStatusMap[$payment->status] ?? ['bg'=>'rgba(100,116,139,.1)','color'=>'#64748b','label'=>$payment->status]; @endphp
                        <tr>
                            <td class="px-3" style="font-size:12px;font-family:monospace;color:var(--text-muted)">
                                {{ $payment->nomor_pembayaran ?? '—' }}
                            </td>
                            <td>
                                <div class="fw-semibold" style="font-size:13px">{{ $payment->siswa?->name ?? '—' }}</div>
                                <div class="text-muted" style="font-size:11px">{{ $payment->cabang?->name ?? 'Pusat' }}</div>
                            </td>
                            <td><div style="font-size:12px;color:var(--text-muted)">{{ $payment->invoice?->nomor_invoice ?? '—' }}</div></td>
                            <td><span class="fw-bold" style="font-size:14px">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</span></td>
                            <td>
                                <span style="font-size:12px;text-transform:capitalize">{{ $payment->metode ?? '—' }}</span>
                                @if($payment->nama_bank)
                                <div class="text-muted" style="font-size:11px">{{ $payment->nama_bank }}</div>
                                @endif
                            </td>
                            <td><span style="font-size:12px">{{ $payment->tanggal_pembayaran ? \Carbon\Carbon::parse($payment->tanggal_pembayaran)->isoFormat('D MMM YYYY') : '—' }}</span></td>
                            <td class="text-center">
                                <span style="background:{{ $psc['bg'] }};color:{{ $psc['color'] }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600">
                                    {{ $psc['label'] }}
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="{{ route('admin.verifikasi-pembayaran.show', $payment) }}"
                                       class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:12px">
                                        <i class="bi bi-eye me-1"></i>Tinjau
                                    </a>
                                    @if($payment->status === 'pending')
                                    <button type="button" onclick="showApproveModal({{ $payment->id }})"
                                            class="btn btn-sm btn-success" style="border-radius:8px;font-size:12px">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                    <button type="button" onclick="showRejectModal({{ $payment->id }})"
                                            class="btn btn-sm btn-danger" style="border-radius:8px;font-size:12px">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-shield-check" style="font-size:36px;opacity:.2;display:block;margin-bottom:12px"></i>
                                Tidak ada data pembayaran ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($payments->hasPages())
            <div class="px-3 pt-3 border-top">
                {{ $payments->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- VER PANE 2: Package Payments --}}
    <div id="ver-pane-package" style="display:none">
        <div class="dashboard-card mb-4">
            <form method="GET" class="row g-2 align-items-end">
                <input type="hidden" name="tab" value="verifikasi">
                <input type="hidden" name="ver_status" value="{{ request('ver_status','pending') }}">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold" style="font-size:12px">Cari Siswa</label>
                    <input type="text" name="ver_search" class="form-control form-control-sm"
                           placeholder="Nama siswa..." value="{{ request('ver_search') }}">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold" style="font-size:12px">Status</label>
                    <select name="pkg_status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="pending"  {{ (request('pkg_status','pending'))=='pending'  ? 'selected' : '' }}>Menunggu</option>
                        <option value="verified" {{ request('pkg_status')=='verified' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('pkg_status')=='rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Filter</button>
                    <a href="{{ route('admin.tagihan-siswa.index', ['tab'=>'verifikasi']) }}"
                       class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
                </div>
            </form>
        </div>

        <div class="dashboard-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="background:var(--input-bg);color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.05em">
                            <th class="px-3 py-3">Siswa</th>
                            <th class="py-3">Mata Pelajaran</th>
                            <th class="py-3">Jumlah</th>
                            <th class="py-3">Bukti</th>
                            <th class="py-3">Catatan</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packagePayments as $pp)
                        @php $psc2 = $payStatusMap[$pp->status] ?? ['bg'=>'rgba(100,116,139,.1)','color'=>'#64748b','label'=>$pp->status]; @endphp
                        <tr>
                            <td class="px-3">
                                <div class="fw-semibold" style="font-size:13px">{{ $pp->student?->name ?? '—' }}</div>
                                <div class="text-muted" style="font-size:11px">{{ $pp->student?->nis ?? '' }}</div>
                            </td>
                            <td><div style="font-size:13px">{{ $pp->course?->nama ?? '—' }}</div></td>
                            <td><span class="fw-bold" style="font-size:14px">Rp {{ number_format($pp->amount, 0, ',', '.') }}</span></td>
                            <td>
                                @if($pp->proof)
                                <a href="{{ asset('storage/'.$pp->proof) }}" target="_blank"
                                   class="btn btn-sm btn-outline-secondary" style="border-radius:8px;font-size:12px">
                                    <i class="bi bi-file-earmark-image me-1"></i>Lihat
                                </a>
                                @else
                                <span class="text-muted" style="font-size:12px">—</span>
                                @endif
                            </td>
                            <td><span style="font-size:12px;color:var(--text-muted)">{{ $pp->catatan ?: '—' }}</span></td>
                            <td class="text-center">
                                <span style="background:{{ $psc2['bg'] }};color:{{ $psc2['color'] }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600">
                                    {{ $psc2['label'] }}
                                </span>
                                @if($pp->status === 'rejected' && $pp->rejected_reason)
                                <div class="text-muted mt-1" style="font-size:10px">{{ \Illuminate\Support\Str::limit($pp->rejected_reason, 30) }}</div>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                @if($pp->status === 'pending')
                                <div class="d-flex gap-1 justify-content-end">
                                    <button type="button" onclick="showPkgApproveModal({{ $pp->id }})"
                                            class="btn btn-sm btn-success" style="border-radius:8px;font-size:12px">
                                        <i class="bi bi-check-lg me-1"></i>Setujui
                                    </button>
                                    <button type="button" onclick="showPkgRejectModal({{ $pp->id }})"
                                            class="btn btn-sm btn-danger" style="border-radius:8px;font-size:12px">
                                        <i class="bi bi-x-lg me-1"></i>Tolak
                                    </button>
                                </div>
                                @else
                                <span class="text-muted" style="font-size:12px">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-box-seam" style="font-size:36px;opacity:.2;display:block;margin-bottom:12px"></i>
                                Tidak ada pembayaran paket ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($packagePayments->hasPages())
            <div class="px-3 pt-3 border-top">
                {{ $packagePayments->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>

</div>{{-- /pane-tab-verifikasi --}}

</div>

{{-- ══ MODALS: Invoice Approve / Reject ══ --}}
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="approveForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-check-circle-fill text-success me-2"></i>Setujui Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p style="font-size:14px">Apakah Anda yakin ingin menyetujui pembayaran ini? Status invoice akan diperbarui secara otomatis.</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan (opsional)</label>
                        <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan verifikasi..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-semibold"><i class="bi bi-check-lg me-1"></i>Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-x-circle-fill text-danger me-2"></i>Tolak Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan_penolakan" class="form-control" rows="3" placeholder="Tuliskan alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-semibold"><i class="bi bi-x-lg me-1"></i>Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══ MODALS: Package Approve / Reject ══ --}}
<div class="modal fade" id="pkgApproveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="pkgApproveForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-check-circle-fill text-success me-2"></i>Setujui Pembayaran Paket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body"><p style="font-size:14px">Apakah Anda yakin ingin menyetujui pembayaran paket ini?</p></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-semibold"><i class="bi bi-check-lg me-1"></i>Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="pkgRejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="pkgRejectForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-x-circle-fill text-danger me-2"></i>Tolak Pembayaran Paket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan_penolakan" class="form-control" rows="3" placeholder="Tuliskan alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-semibold"><i class="bi bi-x-lg me-1"></i>Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchTab(tab) {
    ['siswa','kelas','registrasi','verifikasi'].forEach(function(t) {
        document.getElementById('pane-tab-'+t).classList.toggle('d-none', t !== tab);
        document.getElementById('btn-tab-'+t).classList.toggle('active', t === tab);
    });
}

function showVerTab(tab) {
    document.getElementById('ver-pane-invoice').style.display  = tab === 'invoice'  ? '' : 'none';
    document.getElementById('ver-pane-package').style.display  = tab === 'package'  ? '' : 'none';
    document.getElementById('ver-tab-invoice').classList.toggle('active', tab === 'invoice');
    document.getElementById('ver-tab-package').classList.toggle('active', tab === 'package');
}

function showApproveModal(paymentId) {
    document.getElementById('approveForm').action = `/admin/verifikasi-pembayaran/${paymentId}/approve`;
    new bootstrap.Modal(document.getElementById('approveModal')).show();
}
function showRejectModal(paymentId) {
    document.getElementById('rejectForm').action = `/admin/verifikasi-pembayaran/${paymentId}/reject`;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
function showPkgApproveModal(id) {
    document.getElementById('pkgApproveForm').action = `/admin/verifikasi-pembayaran/package/${id}/approve`;
    new bootstrap.Modal(document.getElementById('pkgApproveModal')).show();
}
function showPkgRejectModal(id) {
    document.getElementById('pkgRejectForm').action = `/admin/verifikasi-pembayaran/package/${id}/reject`;
    new bootstrap.Modal(document.getElementById('pkgRejectModal')).show();
}

// Auto-open correct tab from URL
(function() {
    const urlP = new URLSearchParams(window.location.search);
    const tab = urlP.get('tab') || 'siswa';
    switchTab(tab);
    if (tab === 'verifikasi' && (urlP.has('pkg_status') || urlP.has('pkg_page'))) {
        showVerTab('package');
    }
})();
</script>
@endpush

@endsection
