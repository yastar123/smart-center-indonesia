@extends('layouts.app')
@section('title', 'Manajemen Pembayaran')
@section('page-title', 'Pembayaran')

@section('content')

{{-- HEADER BANNER --}}
<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Manajemen Keuangan & Pembayaran</h5>
                    <span style="font-size:12px;opacity:.8">Kelola invoice dan pembayaran siswa</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <button onclick="openModal()" class="btn fw-semibold px-4"
                style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                <i class="bi bi-plus-lg me-2"></i>Buat Invoice
            </button>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Tagihan</div>
                    <div class="stat-value text-success">Rp {{ number_format($stats['total_tagihan'],0,',','.') }}</div>
                    <div class="stat-growth text-muted"><i class="bi bi-receipt me-1"></i>Semua invoice</div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-receipt-cutoff"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Sudah Lunas</div>
                    <div class="stat-value text-primary">{{ $stats['lunas'] }} Invoice</div>
                    <div class="stat-growth text-success"><i class="bi bi-check-circle me-1"></i>Terbayar</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-check-circle-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Belum Bayar</div>
                    <div class="stat-value text-warning">{{ $stats['belum_bayar'] }} Invoice</div>
                    <div class="stat-growth text-warning"><i class="bi bi-exclamation-circle me-1"></i>Menunggak</div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-clock-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Pendapatan</div>
                    <div class="stat-value text-success">Rp {{ number_format($stats['pendapatan'],0,',','.') }}</div>
                    <div class="stat-growth text-success"><i class="bi bi-graph-up me-1"></i>Terverifikasi</div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-cash-coin"></i></div>
            </div>
        </div>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="dashboard-card mb-4 fade-up">
    <form id="filterForm" method="GET" action="{{ route('admin.payments.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold" style="font-size:12px">Cari Invoice / Siswa</label>
                <div class="input-group">
                    <span class="input-group-text" style="border-radius:10px 0 0 10px;background:var(--input-bg);border-color:var(--card-border)">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nomor invoice atau nama siswa..."
                        class="form-control" style="border-radius:0 10px 10px 0;border-color:var(--card-border);background:var(--input-bg)"
                        onchange="document.getElementById('filterForm').submit()">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label fw-semibold" style="font-size:12px">Status</label>
                <select name="status" class="form-select" style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="">Semua Status</option>
                    <option value="belum_bayar" {{ request('status')=='belum_bayar'?'selected':'' }}>Belum Bayar</option>
                    <option value="sebagian"    {{ request('status')=='sebagian'?'selected':'' }}>Sebagian</option>
                    <option value="lunas"       {{ request('status')=='lunas'?'selected':'' }}>Lunas</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label fw-semibold" style="font-size:12px">Cabang</label>
                <select name="branch_id" class="form-select" style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="">Semua Cabang</option>
                    @foreach($branches as $b)
                    <option value="{{ $b->id }}" {{ request('branch_id')==$b->id?'selected':'' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2">
                @if(request()->hasAny(['search','status','branch_id']))
                <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary w-100" style="border-radius:10px">
                    <i class="bi bi-x-lg me-1"></i>Reset
                </a>
                @else
                <button type="button" onclick="openModal()" class="btn btn-success w-100 fw-semibold" style="border-radius:10px">
                    <i class="bi bi-plus-lg me-1"></i>Buat Invoice
                </button>
                @endif
            </div>
        </div>
    </form>
</div>

{{-- TABLE --}}
<div class="dashboard-card fade-up">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-list-ul text-success me-2"></i>Daftar Invoice
            <span class="badge ms-2" style="background:var(--soft-success-bg);color:var(--soft-success-text);font-size:11px">{{ $invoices->total() }} data</span>
        </h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-modern align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th class="ps-3">No. Invoice</th>
                    <th>Siswa</th>
                    <th class="d-none d-md-table-cell">Cabang</th>
                    <th class="d-none d-md-table-cell">Periode</th>
                    <th>Total</th>
                    <th class="d-none d-lg-table-cell">Jatuh Tempo</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                @php
                    $statusMap = [
                        'lunas'       => ['bg'=>'var(--soft-success-bg)','color'=>'var(--soft-success-text)','label'=>'Lunas'],
                        'sebagian'    => ['bg'=>'var(--soft-warning-bg)','color'=>'var(--soft-warning-text)','label'=>'Sebagian'],
                        'belum_bayar' => ['bg'=>'var(--soft-danger-bg)','color'=>'var(--soft-danger-text)','label'=>'Belum Bayar'],
                    ];
                    $st = $statusMap[$inv->status] ?? ['bg'=>'var(--soft-muted-bg)','color'=>'var(--soft-muted-text)','label'=>$inv->status];
                @endphp
                <tr style="border-bottom:1px solid var(--card-border);transition:background .15s" onmouseover="this.style.background='rgba(104,17,126,.05)'" onmouseout="this.style.background=''">
                    <td class="ps-3">
                        <code style="background:var(--soft-primary-bg);color:var(--soft-primary-text);padding:3px 8px;border-radius:6px;font-size:11px">{{ $inv->nomor_invoice }}</code>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;border-radius:50%;background:var(--soft-primary-bg);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:var(--soft-primary-text);flex-shrink:0">
                                {{ strtoupper(substr($inv->siswa?->name ?? 'S', 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:13px">{{ $inv->siswa?->name ?? '–' }}</div>
                                <div class="text-muted" style="font-size:11px">{{ $inv->siswa?->nis ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-md-table-cell text-muted" style="font-size:.85rem">{{ $inv->cabang?->name ?? '–' }}</td>
                    <td class="d-none d-md-table-cell text-muted" style="font-size:.85rem">{{ $inv->periode ?? '–' }}</td>
                    <td class="fw-bold text-success" style="font-size:.9rem">Rp {{ number_format($inv->total,0,',','.') }}</td>
                    <td class="d-none d-lg-table-cell text-muted" style="font-size:.85rem">
                        @if($inv->jatuh_tempo)
                            {{ $inv->jatuh_tempo->format('d M Y') }}
                            @if($inv->jatuh_tempo->isPast() && $inv->status !== 'lunas')
                                <span class="ms-1 badge rounded-pill" style="background:var(--soft-danger-bg);color:var(--soft-danger-text);font-size:10px">Terlambat</span>
                            @endif
                        @else
                            –
                        @endif
                    </td>
                    <td>
                        <span style="background:{{ $st['bg'] }};color:{{ $st['color'] }};padding:4px 10px;border-radius:8px;font-size:11px;font-weight:600">
                            {{ $st['label'] }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <button onclick="showDetail({{ $inv->id }})" class="btn btn-sm btn-act-view" title="Detail">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                            @if($inv->status !== 'lunas')
                            <button onclick="openPayModal({{ $inv->id }}, '{{ addslashes($inv->siswa?->name ?? '') }}', {{ $inv->total }})" class="btn btn-sm btn-act-pay" title="Bayar">
                                <i class="bi bi-cash-stack"></i>
                            </button>
                            @endif
                            <button onclick="editInvoice({{ $inv->id }})" class="btn btn-sm btn-act-edit" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button onclick="deleteInvoice({{ $inv->id }}, '{{ addslashes($inv->nomor_invoice) }}')" class="btn btn-sm btn-act-del" title="Hapus">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div class="text-muted">
                            <i class="bi bi-receipt" style="font-size:40px;display:block;margin-bottom:12px;opacity:.4"></i>
                            <div class="fw-semibold mb-1">Belum ada invoice</div>
                            <div style="font-size:12px">Klik "Buat Invoice" untuk membuat invoice pertama</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($invoices->hasPages())
    <div class="mt-4 d-flex justify-content-center">
        {{ $invoices->links() }}
    </div>
    @endif
</div>

{{-- MODAL ADD/EDIT --}}
<div class="modal fade" id="invoiceModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:18px;overflow:hidden">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#260632,#68117e);color:#fff">
                <h6 class="modal-title fw-bold" id="modalTitle"><i class="bi bi-receipt-cutoff me-2"></i>Buat Invoice Baru</h6>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="background:var(--input-bg)">
                <input type="hidden" id="invoiceId">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Siswa <span class="text-danger">*</span></label>
                        <select id="siswa_id" class="form-select" style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                            <option value="">— Pilih Siswa —</option>
                            @foreach($students as $s)
                            <option value="{{ $s->id }}" data-branch="{{ $s->branch_id }}">{{ $s->name }} ({{ $s->nis }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Cabang <span class="text-danger">*</span></label>
                        <select id="cabang_id" class="form-select" style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                            <option value="">— Pilih Cabang —</option>
                            @foreach($branches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Subtotal (Rp) <span class="text-danger">*</span></label>
                        <input type="number" id="subtotal" class="form-control" placeholder="0" min="0"
                            style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)"
                            oninput="calcTotal()">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="font-size:12px">Diskon (Rp)</label>
                        <input type="number" id="diskon" class="form-control" placeholder="0" min="0"
                            style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)"
                            oninput="calcTotal()">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="font-size:12px">Pajak (Rp)</label>
                        <input type="number" id="pajak" class="form-control" placeholder="0" min="0"
                            style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)"
                            oninput="calcTotal()">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Total (Rp) <span class="text-danger">*</span></label>
                        <input type="number" id="inv_total" class="form-control fw-bold" placeholder="0" min="0" readonly
                            style="border-radius:10px;border-color:var(--soft-success-border);background:var(--soft-success-bg);color:var(--soft-success-text);font-weight:700">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Periode (contoh: Juli 2025)</label>
                        <input type="text" id="periode" class="form-control" placeholder="cth: Juli 2025"
                            style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Jatuh Tempo</label>
                        <input type="date" id="jatuh_tempo" class="form-control"
                            style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                    </div>
                    <div class="col-12" id="statusField" style="display:none">
                        <label class="form-label fw-semibold" style="font-size:12px">Status</label>
                        <select id="inv_status" class="form-select" style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                            <option value="belum_bayar">Belum Bayar</option>
                            <option value="sebagian">Sebagian</option>
                            <option value="lunas">Lunas</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:12px">Deskripsi</label>
                        <input type="text" id="deskripsi" class="form-control" placeholder="cth: SPP Bulan Juli 2025"
                            style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:12px">Catatan</label>
                        <textarea id="catatan" class="form-control" rows="2" placeholder="Catatan tambahan..."
                            style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4" style="background:var(--input-bg)">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius:10px">
                    <i class="bi bi-x me-1"></i>Batal
                </button>
                <button type="button" class="btn btn-success px-5 fw-semibold" id="saveBtn" onclick="saveInvoice()" style="border-radius:10px">
                    <i class="bi bi-check-lg me-1"></i>Simpan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:18px;overflow:hidden">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#461256,#68117e);color:#fff">
                <h6 class="modal-title fw-bold"><i class="bi bi-file-earmark-text me-2"></i>Detail Invoice</h6>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="detailBody">
                <div class="text-center py-5"><div class="spinner-border text-primary"></div></div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL BAYAR --}}
<div class="modal fade" id="payModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:18px;overflow:hidden">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#260632,#68117e);color:#fff">
                <h6 class="modal-title fw-bold" id="payModalTitle"><i class="bi bi-cash-stack me-2"></i>Catat Pembayaran</h6>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="background:var(--input-bg)">
                <input type="hidden" id="payInvoiceId">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="p-3 rounded-3 mb-2" style="background:var(--soft-primary-bg);border:1px solid var(--soft-primary-border)">
                            <div class="fw-semibold" style="font-size:12px;color:var(--soft-primary-text)" id="payStudentName"></div>
                            <div style="font-size:20px;font-weight:700;color:#c84ddf" id="payTotalLabel"></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:12px">Jumlah Bayar (Rp) <span class="text-danger">*</span></label>
                        <input type="number" id="pay_jumlah" class="form-control" placeholder="0" min="1"
                            style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Metode <span class="text-danger">*</span></label>
                        <select id="pay_metode" class="form-select" style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                            <option value="cash">💵 Cash</option>
                            <option value="transfer">🏦 Transfer Bank</option>
                            <option value="qris">📱 QRIS</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Tanggal Bayar <span class="text-danger">*</span></label>
                        <input type="date" id="pay_tanggal" class="form-control"
                            style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:12px">Catatan</label>
                        <input type="text" id="pay_catatan" class="form-control" placeholder="Catatan opsional..."
                            style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4" style="background:var(--input-bg)">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius:10px">Batal</button>
                <button type="button" class="btn btn-success px-5 fw-semibold" id="payBtn" onclick="submitPayment()" style="border-radius:10px">
                    <i class="bi bi-check-lg me-1"></i>Konfirmasi Bayar
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function calcTotal() {
    const sub  = parseFloat(document.getElementById('subtotal').value) || 0;
    const disc = parseFloat(document.getElementById('diskon').value)   || 0;
    const tax  = parseFloat(document.getElementById('pajak').value)    || 0;
    document.getElementById('inv_total').value = Math.max(0, sub - disc + tax);
}

function openModal() {
    document.getElementById('invoiceId').value = '';
    document.getElementById('modalTitle').innerHTML = '<i class="bi bi-receipt-cutoff me-2"></i>Buat Invoice Baru';
    document.getElementById('siswa_id').value   = '';
    document.getElementById('cabang_id').value  = '';
    document.getElementById('subtotal').value   = '';
    document.getElementById('diskon').value     = '';
    document.getElementById('pajak').value      = '';
    document.getElementById('inv_total').value  = '';
    document.getElementById('periode').value    = '';
    document.getElementById('jatuh_tempo').value= '';
    document.getElementById('deskripsi').value  = '';
    document.getElementById('catatan').value    = '';
    document.getElementById('statusField').style.display = 'none';
    document.getElementById('siswa_id').disabled = false;
    document.getElementById('cabang_id').disabled = false;
    new bootstrap.Modal('#invoiceModal').show();
}

function editInvoice(id) {
    $.get('/admin/payments/' + id, function(res) {
        const inv = res.data;
        document.getElementById('modalTitle').innerHTML = '<i class="bi bi-pencil me-2"></i>Edit Invoice';
        document.getElementById('invoiceId').value     = inv.id;
        document.getElementById('siswa_id').value      = inv.siswa_id;
        document.getElementById('cabang_id').value     = inv.cabang_id;
        document.getElementById('subtotal').value      = inv.subtotal;
        document.getElementById('diskon').value        = inv.diskon  || 0;
        document.getElementById('pajak').value         = inv.pajak   || 0;
        document.getElementById('inv_total').value     = inv.total;
        document.getElementById('periode').value       = inv.periode  || '';
        document.getElementById('jatuh_tempo').value   = inv.jatuh_tempo ? inv.jatuh_tempo.substr(0,10) : '';
        document.getElementById('deskripsi').value     = inv.deskripsi || '';
        document.getElementById('catatan').value       = inv.catatan  || '';
        document.getElementById('inv_status').value    = inv.status;
        document.getElementById('statusField').style.display = 'block';
        document.getElementById('siswa_id').disabled = true;
        document.getElementById('cabang_id').disabled = true;
        new bootstrap.Modal('#invoiceModal').show();
    }).fail(() => showToast('Tidak dapat memuat data invoice.', 'error'));
}

function saveInvoice() {
    const id  = document.getElementById('invoiceId').value;
    const url = id ? '/admin/payments/' + id : '{{ route("admin.payments.store") }}';

    const payload = {
        _token:       document.querySelector('meta[name=csrf-token]').content,
        siswa_id:     document.getElementById('siswa_id').value,
        cabang_id:    document.getElementById('cabang_id').value,
        subtotal:     document.getElementById('subtotal').value,
        diskon:       document.getElementById('diskon').value   || 0,
        pajak:        document.getElementById('pajak').value    || 0,
        total:        document.getElementById('inv_total').value,
        periode:      document.getElementById('periode').value,
        jatuh_tempo:  document.getElementById('jatuh_tempo').value,
        deskripsi:    document.getElementById('deskripsi').value,
        catatan:      document.getElementById('catatan').value,
    };
    if (id) { payload._method = 'PUT'; payload.status = document.getElementById('inv_status').value; }

    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

    $.ajax({
        url, method: 'POST', data: payload,
        success(res) {
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('invoiceModal'))?.hide();
                showToast(res.message, 'success');
                setTimeout(() => location.reload(), 1200);
            }
        },
        error(xhr) {
            const errors = xhr.responseJSON?.errors;
            const msg = errors
                ? Object.values(errors).flat().join('; ')
                : (xhr.responseJSON?.message ?? 'Terjadi kesalahan.');
            showToast(msg, 'error');
        },
        complete() { btn.disabled=false; btn.innerHTML='<i class="bi bi-check-lg me-1"></i>Simpan'; }
    });
}

function showDetail(id) {
    document.getElementById('detailBody').innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';
    new bootstrap.Modal('#detailModal').show();
    $.get('/admin/payments/' + id, function(res) {
        const inv = res.data;
        const statusMap = {lunas:'var(--soft-success-bg):var(--soft-success-text):Lunas', sebagian:'var(--soft-warning-bg):var(--soft-warning-text):Sebagian', belum_bayar:'var(--soft-danger-bg):var(--soft-danger-text):Belum Bayar'};
        const [sbg,scol,slbl] = (statusMap[inv.status]||'var(--soft-muted-bg):var(--soft-muted-text):'+inv.status).split(':');

        let paymentsHtml = '';
        if (inv.pembayaran && inv.pembayaran.length > 0) {
            paymentsHtml = `<div style="padding:0 20px 16px"><div class="fw-semibold mb-2" style="font-size:12px;color:var(--text-muted)">Riwayat Pembayaran</div>`;
            inv.pembayaran.forEach(p => {
                paymentsHtml += `<div class="d-flex justify-content-between align-items-center p-2 mb-1 rounded-2" style="background:var(--input-bg);font-size:12px">
                    <div><span class="fw-semibold">${p.nomor_pembayaran}</span> · ${p.metode}</div>
                    <div class="fw-bold text-success">Rp ${Number(p.jumlah).toLocaleString('id-ID')}</div>
                </div>`;
            });
            paymentsHtml += `</div>`;
        }

        document.getElementById('detailBody').innerHTML = `
            <div style="padding:20px 20px 12px">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="fw-bold" style="font-size:15px">${inv.siswa?.name ?? '–'}</div>
                        <div style="font-size:12px;color:var(--text-muted)">${inv.nomor_invoice}</div>
                    </div>
                    <span style="background:${sbg};color:${scol};padding:4px 12px;border-radius:8px;font-size:12px;font-weight:600">${slbl}</span>
                </div>
                <table style="width:100%;border-collapse:collapse;font-size:13px">
                    ${drow('Cabang', inv.cabang?.name ?? '–')}
                    ${drow('Deskripsi', inv.deskripsi ?? '–')}
                    ${drow('Periode', inv.periode ?? '–')}
                    ${drow('Subtotal', 'Rp ' + Number(inv.subtotal).toLocaleString('id-ID'))}
                    ${drow('Diskon', 'Rp ' + Number(inv.diskon||0).toLocaleString('id-ID'))}
                    ${drow('Pajak', 'Rp ' + Number(inv.pajak||0).toLocaleString('id-ID'))}
                    ${drow('Total', '<span class="text-success fw-bold">Rp ' + Number(inv.total).toLocaleString('id-ID') + '</span>')}
                    ${drow('Jatuh Tempo', inv.jatuh_tempo ? inv.jatuh_tempo.substr(0,10) : '–')}
                    ${drow('Catatan', inv.catatan ?? '–')}
                </table>
            </div>
            ${paymentsHtml}
        `;
    }).fail(() => { document.getElementById('detailBody').innerHTML = '<div class="text-center py-5 text-muted">Gagal memuat data</div>'; });
}

function drow(label, val) {
    return `<tr style="border-bottom:1px solid var(--card-border)">
        <td style="padding:7px 4px 7px 0;color:var(--text-muted);font-size:12px;width:36%">${label}</td>
        <td style="padding:7px 0;font-size:13px;font-weight:500;color:var(--text-primary)">${val}</td>
    </tr>`;
}

function openPayModal(id, name, total) {
    document.getElementById('payInvoiceId').value = id;
    document.getElementById('payStudentName').textContent = name;
    document.getElementById('payTotalLabel').textContent = 'Rp ' + Number(total).toLocaleString('id-ID');
    document.getElementById('pay_jumlah').value = total;
    document.getElementById('pay_tanggal').value = new Date().toISOString().substr(0,10);
    document.getElementById('pay_catatan').value = '';
    new bootstrap.Modal('#payModal').show();
}

function submitPayment() {
    const id = document.getElementById('payInvoiceId').value;
    const btn = document.getElementById('payBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

    $.post('/admin/payments/' + id + '/pay', {
        _token:              document.querySelector('meta[name=csrf-token]').content,
        jumlah:              document.getElementById('pay_jumlah').value,
        metode:              document.getElementById('pay_metode').value,
        tanggal_pembayaran:  document.getElementById('pay_tanggal').value,
        catatan:             document.getElementById('pay_catatan').value,
    }, function(res) {
        if (res.success) {
            bootstrap.Modal.getInstance(document.getElementById('payModal'))?.hide();
            showToast(res.message, 'success');
            setTimeout(() => location.reload(), 1200);
        }
    }).fail(xhr => {
        const msg = xhr.responseJSON?.message ?? 'Terjadi kesalahan.';
        showToast(msg, 'error');
    }).always(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Konfirmasi Bayar';
    });
}

function deleteInvoice(id, nomor) {
    confirmAction(`Hapus invoice ${nomor}? Data tidak dapat dikembalikan.`, function() {
        $.post('/admin/payments/' + id, {
            _method: 'DELETE',
            _token:  document.querySelector('meta[name=csrf-token]').content
        }, function(res) {
            if (res.success) {
                showToast(res.message, 'success');
                setTimeout(() => location.reload(), 1200);
            }
        }).fail(() => showToast('Tidak dapat menghapus invoice.', 'error'));
    }, null, {title:'Hapus Invoice', okText:'Ya, Hapus'});
}
</script>
@endpush
