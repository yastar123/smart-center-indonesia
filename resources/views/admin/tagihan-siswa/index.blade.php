@extends('layouts.app')
@section('title', 'Tagihan Siswa')
@section('page-title', 'Tagihan Siswa')

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
                    <h5 class="fw-bold mb-0" style="color:white">Tagihan Siswa</h5>
                    <span style="font-size:12px;opacity:.8">Tagihan kelas (cicilan/pascabayar) &amp; invoice pendaftaran siswa</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('admin.billing.index') }}" class="btn fw-semibold px-3"
               style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px">
                <i class="bi bi-receipt me-2"></i>Lihat Pembayaran Lunas
            </a>
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

{{-- STATS --}}
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

{{-- TAB NAV --}}
<ul class="nav nav-pills mb-4 gap-2 fade-up" id="tagihanTab">
    <li class="nav-item">
        <button class="nav-link {{ !request('tab') || request('tab')=='kelas' ? 'active' : '' }}"
                onclick="switchTab('kelas')" id="btn-tab-kelas"
                style="border-radius:10px;font-size:13px">
            <i class="bi bi-diagram-3 me-1"></i>Tagihan Kelas
            @if($stats['total'] > 0)
            <span class="badge ms-1" style="background:rgba(200,77,223,.2);color:#c84ddf">{{ $stats['total'] }}</span>
            @endif
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link {{ request('tab')=='registrasi' ? 'active' : '' }}"
                onclick="switchTab('registrasi')" id="btn-tab-registrasi"
                style="border-radius:10px;font-size:13px">
            <i class="bi bi-file-earmark-text me-1"></i>Invoice Registrasi
            @if($stats['reg_belum'] > 0)
            <span class="badge bg-danger ms-1">{{ $stats['reg_belum'] }}</span>
            @endif
        </button>
    </li>
</ul>

{{-- ==================== TAB 1: TAGIHAN KELAS ==================== --}}
<div id="pane-kelas" class="{{ request('tab')=='registrasi' ? 'd-none' : '' }}">

    {{-- FILTER KELAS --}}
    <div class="dashboard-card mb-4 fade-up">
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

    <div class="dashboard-card fade-up">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr style="background:var(--input-bg)">
                        <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Nama Siswa / Kelas</th>
                        <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Paket</th>
                        <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Harga Paket</th>
                        <th class="text-center" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Tipe Tagihan</th>
                        <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Cicilan Dibayar</th>
                        <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Sisa Cicilan</th>
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
                        $kelasInvoices   = $invoicesByKelas[$kelas->id] ?? collect();
                        $totalTagihan    = $kelasInvoices->sum('total');
                        $totalDibayar    = $kelasInvoices->flatMap->pembayaran->where('status', 'verified')->sum('jumlah');
                        $sisaCicilan     = max(0, $totalTagihan - $totalDibayar);
                    @endphp
                    <tr>
                        <td>
                            <div class="fw-semibold" style="font-size:13px">{{ $siswaNama }}</div>
                            <div class="text-muted" style="font-size:12px"><i class="bi bi-diagram-3 me-1"></i>{{ $kelas->nama_kelas }}</div>
                        </td>
                        <td style="font-size:13px">
                            @if($paket)
                                <div class="fw-semibold" style="font-size:13px">{{ $paket->nama }}</div>
                                <div class="text-muted" style="font-size:11px">{{ ucfirst($paket->jenis ?? '—') }} · {{ $paket->jumlah_pertemuan ?? '?' }} sesi</div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td style="font-size:13px">
                            @if($paket?->harga)
                                <span class="fw-semibold">Rp {{ number_format($paket->harga, 0, ',', '.') }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span style="background:{{ $billingBg }};color:{{ $billingColor }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600">
                                {{ $billingLabel }}
                            </span>
                        </td>
                        <td style="font-size:13px">
                            @if($totalDibayar > 0)
                                <span class="fw-semibold text-success">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td style="font-size:13px">
                            @if($sisaCicilan > 0)
                                <span class="fw-semibold text-danger">Rp {{ number_format($sisaCicilan, 0, ',', '.') }}</span>
                            @elseif($totalTagihan > 0)
                                <span class="text-success fw-semibold"><i class="bi bi-check-circle me-1"></i>Lunas</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td style="font-size:13px">{{ $kelas->cabang?->name ?? '—' }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.tagihan-siswa.show', $kelas->id) }}"
                               class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:11px">
                                <i class="bi bi-eye me-1"></i>Lihat Detail
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
            <div class="text-muted" style="font-size:13px">Menampilkan {{ $classes->firstItem() }}–{{ $classes->lastItem() }} dari {{ $classes->total() }} kelas</div>
            {{ $classes->appends(request()->all())->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ==================== TAB 2: INVOICE REGISTRASI ==================== --}}
<div id="pane-registrasi" class="{{ request('tab')=='registrasi' ? '' : 'd-none' }}">

    {{-- FILTER REGISTRASI --}}
    <div class="dashboard-card mb-4 fade-up">
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
                    <a href="{{ route('admin.tagihan-siswa.index', ['tab'=>'registrasi']) }}" class="btn btn-outline-secondary px-3">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="dashboard-card fade-up">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr style="background:var(--input-bg)">
                        <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">No Invoice</th>
                        <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Nama Siswa</th>
                        <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Deskripsi Program</th>
                        <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Total</th>
                        <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Jatuh Tempo</th>
                        <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Cabang</th>
                        <th class="text-center" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $statusMap = [
                            'belum_bayar' => ['rgba(239,68,68,.1)','#ef4444','Belum Dibayar'],
                            'sebagian'    => ['rgba(245,158,11,.1)','#f59e0b','Sebagian'],
                            'lunas'       => ['rgba(16,185,129,.1)','#10b981','Lunas'],
                        ];
                    @endphp
                    @forelse($registrationInvoices as $inv)
                    @php $sc = $statusMap[$inv->status] ?? ['rgba(100,116,139,.1)','#64748b',$inv->status]; @endphp
                    <tr>
                        <td>
                            <span style="font-size:11px;font-family:monospace;color:var(--text-muted)">{{ $inv->nomor_invoice }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold" style="font-size:13px">{{ $inv->siswa?->name ?? '—' }}</div>
                            <div class="text-muted" style="font-size:11px">{{ $inv->siswa?->user?->email ?? '' }}</div>
                        </td>
                        <td style="font-size:12px;max-width:220px">
                            <span class="text-muted" style="white-space:normal">{{ $inv->deskripsi }}</span>
                        </td>
                        <td>
                            <span class="fw-bold" style="font-size:14px">Rp {{ number_format($inv->total, 0, ',', '.') }}</span>
                        </td>
                        <td style="font-size:12px">
                            {{ $inv->jatuh_tempo ? \Carbon\Carbon::parse($inv->jatuh_tempo)->isoFormat('D MMM YYYY') : '—' }}
                        </td>
                        <td style="font-size:12px">{{ $inv->cabang?->name ?? '—' }}</td>
                        <td class="text-center">
                            <span class="fw-semibold" style="background:{{ $sc[0] }};color:{{ $sc[1] }};padding:3px 12px;border-radius:20px;font-size:11px">
                                {{ $sc[2] }}
                            </span>
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
            <div class="text-muted" style="font-size:13px">Menampilkan {{ $registrationInvoices->firstItem() }}–{{ $registrationInvoices->lastItem() }} dari {{ $registrationInvoices->total() }} invoice</div>
            {{ $registrationInvoices->appends(request()->all())->links() }}
        </div>
        @endif
    </div>
</div>

</div>

<script>
function switchTab(tab) {
    document.getElementById('pane-kelas').classList.toggle('d-none', tab !== 'kelas');
    document.getElementById('pane-registrasi').classList.toggle('d-none', tab !== 'registrasi');
    document.getElementById('btn-tab-kelas').classList.toggle('active', tab === 'kelas');
    document.getElementById('btn-tab-registrasi').classList.toggle('active', tab === 'registrasi');
}
</script>

@endsection
