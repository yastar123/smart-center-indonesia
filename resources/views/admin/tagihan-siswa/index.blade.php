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
                    <span style="font-size:12px;opacity:.8">Siswa dengan pembayaran cicilan dan pascabayar (per sesi)</span>
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
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Tagihan Siswa</div>
                    <div class="stat-value text-primary count-up" data-target="{{ $stats['total'] }}">{{ $stats['total'] }}</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-people"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Cicilan (Prabayar)</div>
                    <div class="stat-value text-warning count-up" data-target="{{ $stats['cicilan'] }}">{{ $stats['cicilan'] }}</div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-pie-chart"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #0ea5e9">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Pascabayar (Per Sesi)</div>
                    <div class="stat-value count-up" data-target="{{ $stats['postpaid'] }}" style="color:#0ea5e9">{{ $stats['postpaid'] }}</div>
                </div>
                <div class="stat-icon bg-info-soft" style="color:white"><i class="bi bi-receipt"></i></div>
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

{{-- FILTER --}}
<div class="dashboard-card mb-4 fade-up">
    <form method="GET" action="{{ route('admin.tagihan-siswa.index') }}">
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

{{-- TABLE --}}
<div class="dashboard-card fade-up">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr style="background:var(--input-bg)">
                    <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Siswa / Kelas</th>
                    <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Cabang</th>
                    <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Guru</th>
                    <th class="text-center" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Tipe Tagihan</th>
                    <th class="text-center" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Status Invoice</th>
                    <th class="text-center" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classes as $kelas)
                @php
                    $siswa = $kelas->siswa->first();
                    $siswaNama = $siswa?->user?->name ?? $siswa?->name ?? '—';
                    $invoices = $siswa ? \App\Models\Invoice::where('siswa_id', $siswa->id)->orderByDesc('created_at')->get() : collect();
                    $latestInv = $invoices->first();
                    $statusMap = [
                        'belum_bayar' => ['bg'=>'var(--soft-danger-bg)','fg'=>'#dc2626','label'=>'Belum Bayar'],
                        'sebagian'    => ['bg'=>'rgba(59,130,246,.12)','fg'=>'#2563eb','label'=>'Dibayar Sebagian'],
                        'lunas'       => ['bg'=>'var(--soft-success-bg)','fg'=>'#059669','label'=>'Lunas'],
                    ];
                    $billingLabel = $kelas->billing_mode === 'postpaid' ? 'Pascabayar' : 'Cicilan';
                    $billingColor = $kelas->billing_mode === 'postpaid' ? '#0ea5e9' : '#f6af23';
                    $billingBg    = $kelas->billing_mode === 'postpaid' ? 'rgba(14,165,233,.12)' : 'rgba(246,175,35,.15)';
                @endphp
                <tr>
                    <td>
                        <div class="fw-semibold" style="font-size:13px">{{ $siswaNama }}</div>
                        <div class="text-muted" style="font-size:12px"><i class="bi bi-diagram-3 me-1"></i>{{ $kelas->nama_kelas }}</div>
                    </td>
                    <td style="font-size:13px">{{ $kelas->cabang?->name ?? '—' }}</td>
                    <td style="font-size:13px">{{ $kelas->guru?->name ?? '—' }}</td>
                    <td class="text-center">
                        <span style="background:{{ $billingBg }};color:{{ $billingColor }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600">
                            {{ $billingLabel }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if($latestInv)
                            @php $s = $statusMap[$latestInv->status] ?? ['bg'=>'var(--soft-muted-bg)','fg'=>'#6b7280','label'=>ucfirst($latestInv->status)]; @endphp
                            <span style="background:{{ $s['bg'] }};color:{{ $s['fg'] }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600">
                                {{ $s['label'] }}
                            </span>
                            <div class="text-muted mt-1" style="font-size:10px">Rp {{ number_format($latestInv->total,0,',','.') }}</div>
                        @else
                            <span style="background:var(--soft-muted-bg);color:var(--text-muted);padding:3px 10px;border-radius:20px;font-size:11px">
                                Belum ada invoice
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        <button onclick="openInvoiceModal({{ $kelas->id }}, '{{ addslashes($kelas->nama_kelas) }}')"
                                class="btn btn-sm btn-primary" style="border-radius:8px;font-size:11px">
                            <i class="bi bi-plus-lg me-1"></i>Buat Invoice
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
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
            <div class="text-muted" style="font-size:13px">
                Menampilkan {{ $classes->firstItem() }}–{{ $classes->lastItem() }} dari {{ $classes->total() }} kelas
            </div>
            {{ $classes->appends(request()->all())->links() }}
        </div>
    @endif
</div>

</div>

{{-- MODAL: BUAT INVOICE --}}
<div id="modalBuatInvoice" style="display:none;position:fixed;inset:0;z-index:1050;background:rgba(0,0,0,.5);align-items:center;justify-content:center">
    <div class="dashboard-card" style="width:100%;max-width:460px;margin:20px;max-height:90vh;overflow-y:auto">
        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
            <h6 class="fw-bold mb-0"><i class="bi bi-receipt text-primary me-2"></i>Buat Invoice</h6>
            <button onclick="document.getElementById('modalBuatInvoice').style.display='none'" class="btn-close"></button>
        </div>
        <p class="text-muted mb-3" style="font-size:13px" id="invoiceModalDesc"></p>
        <form method="POST" id="invoiceForm" action="">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold">Deskripsi Invoice <span class="text-danger">*</span></label>
                    <input type="text" name="deskripsi" class="form-control" required id="invDeskripsi" placeholder="cth: Tagihan Sesi Bulan Juli">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nominal (Rp) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="total" class="form-control" required min="1000" placeholder="500000">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jatuh Tempo <span class="text-danger">*</span></label>
                    <input type="date" name="jatuh_tempo" class="form-control" required min="{{ date('Y-m-d') }}">
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary flex-fill fw-semibold"><i class="bi bi-receipt me-2"></i>Terbitkan Invoice</button>
                <button type="button" onclick="document.getElementById('modalBuatInvoice').style.display='none'"
                        class="btn btn-outline-secondary fw-semibold">Batal</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openInvoiceModal(kelasId, kelasNama) {
    document.getElementById('invoiceForm').action = '/admin/tagihan-siswa/' + kelasId + '/generate-invoice';
    document.getElementById('invoiceModalDesc').textContent = 'Kelas: ' + kelasNama;
    document.getElementById('invDeskripsi').value = 'Tagihan Sesi – ' + kelasNama;
    document.getElementById('modalBuatInvoice').style.display = 'flex';
}
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.getElementById('modalBuatInvoice').style.display = 'none';
});
</script>
@endpush
@endsection
