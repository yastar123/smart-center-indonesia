@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="font-size:22px">Verifikasi Pembayaran</h4>
            <p class="text-muted mb-0" style="font-size:13px">Tinjau dan setujui bukti pembayaran yang diunggah siswa.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- TABS --}}
    <ul class="nav nav-pills mb-4 gap-2" id="verifikasiTab">
        <li class="nav-item">
            <button class="nav-link active" onclick="showTab('invoice')" id="tab-invoice"
                    style="border-radius:10px;font-size:13px">
                <i class="bi bi-receipt me-1"></i>Tagihan Registrasi
                @if($counts['pending'] > 0)
                <span class="badge bg-danger ms-1">{{ $counts['pending'] }}</span>
                @endif
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" onclick="showTab('package')" id="tab-package"
                    style="border-radius:10px;font-size:13px">
                <i class="bi bi-box-seam me-1"></i>Pembayaran Paket
                @if($pkgCounts['pending'] > 0)
                <span class="badge bg-danger ms-1">{{ $pkgCounts['pending'] }}</span>
                @endif
            </button>
        </li>
    </ul>

    {{-- TAB 1: INVOICE / REGISTRATION PAYMENTS --}}
    <div id="pane-invoice">

        {{-- FILTER --}}
        <div class="dashboard-card mb-4">
            <form method="GET" class="row g-2 align-items-end">
                <input type="hidden" name="pkg_status" value="{{ request('pkg_status','pending') }}">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold" style="font-size:12px">Cari Siswa / No Pembayaran</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Nama siswa atau nomor pembayaran..." value="{{ request('search') }}">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold" style="font-size:12px">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="pending"  {{ request('status','pending')=='pending'  ? 'selected' : '' }}>Menunggu</option>
                        <option value="verified" {{ request('status')=='verified' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Filter</button>
                    <a href="{{ route('admin.verifikasi-pembayaran.index') }}" class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
                </div>
            </form>
        </div>

        {{-- SUMMARY PILLS --}}
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
                            $statusMap = [
                                'pending'  => ['bg'=>'rgba(245,158,11,.1)','color'=>'#f59e0b','label'=>'Menunggu'],
                                'verified' => ['bg'=>'rgba(16,185,129,.1)','color'=>'#10b981','label'=>'Disetujui'],
                                'rejected' => ['bg'=>'rgba(239,68,68,.1)','color'=>'#ef4444','label'=>'Ditolak'],
                            ];
                        @endphp
                        @forelse($payments as $payment)
                        @php $sc = $statusMap[$payment->status] ?? ['bg'=>'rgba(100,116,139,.1)','color'=>'#64748b','label'=>$payment->status]; @endphp
                        <tr>
                            <td class="px-3" style="font-size:12px;font-family:monospace;color:var(--text-muted)">
                                {{ $payment->nomor_pembayaran ?? '—' }}
                            </td>
                            <td>
                                <div class="fw-semibold" style="font-size:13px">{{ $payment->siswa?->name ?? '—' }}</div>
                                <div class="text-muted" style="font-size:11px">{{ $payment->cabang?->name ?? 'Pusat' }}</div>
                            </td>
                            <td>
                                <div style="font-size:12px;color:var(--text-muted)">{{ $payment->invoice?->nomor_invoice ?? '—' }}</div>
                            </td>
                            <td>
                                <span class="fw-bold" style="font-size:14px">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                <span style="font-size:12px;text-transform:capitalize">{{ $payment->metode ?? '—' }}</span>
                                @if($payment->nama_bank)
                                <div class="text-muted" style="font-size:11px">{{ $payment->nama_bank }}</div>
                                @endif
                            </td>
                            <td>
                                <span style="font-size:12px">{{ $payment->tanggal_pembayaran ? \Carbon\Carbon::parse($payment->tanggal_pembayaran)->isoFormat('D MMM YYYY') : '—' }}</span>
                            </td>
                            <td class="text-center">
                                <span style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600">
                                    {{ $sc['label'] }}
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

    {{-- TAB 2: PACKAGE PAYMENTS (StudentCoursePayment) --}}
    <div id="pane-package" style="display:none">

        {{-- FILTER --}}
        <div class="dashboard-card mb-4">
            <form method="GET" class="row g-2 align-items-end">
                <input type="hidden" name="status" value="{{ request('status','pending') }}">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold" style="font-size:12px">Cari Siswa</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Nama siswa..." value="{{ request('search') }}">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold" style="font-size:12px">Status</label>
                    <select name="pkg_status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="pending"  {{ request('pkg_status','pending')=='pending'  ? 'selected' : '' }}>Menunggu</option>
                        <option value="verified" {{ request('pkg_status')=='verified' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('pkg_status')=='rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Filter</button>
                    <a href="{{ route('admin.verifikasi-pembayaran.index') }}" class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
                </div>
            </form>
        </div>

        {{-- SUMMARY PILLS --}}
        <div class="d-flex gap-2 flex-wrap mb-3">
            <span class="badge" style="background:rgba(245,158,11,.12);color:#f59e0b;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600">
                <i class="bi bi-hourglass-split me-1"></i>{{ $pkgCounts['pending'] }} Menunggu
            </span>
            <span class="badge" style="background:rgba(16,185,129,.12);color:#10b981;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600">
                <i class="bi bi-check-circle me-1"></i>{{ $pkgCounts['verified'] }} Disetujui
            </span>
            <span class="badge" style="background:rgba(239,68,68,.12);color:#ef4444;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600">
                <i class="bi bi-x-circle me-1"></i>{{ $pkgCounts['rejected'] }} Ditolak
            </span>
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
                        @php $sc = $statusMap[$pp->status] ?? ['bg'=>'rgba(100,116,139,.1)','color'=>'#64748b','label'=>$pp->status]; @endphp
                        <tr>
                            <td class="px-3">
                                <div class="fw-semibold" style="font-size:13px">{{ $pp->student?->name ?? '—' }}</div>
                                <div class="text-muted" style="font-size:11px">{{ $pp->student?->nis ?? '' }}</div>
                            </td>
                            <td>
                                <div style="font-size:13px">{{ $pp->course?->nama ?? '—' }}</div>
                            </td>
                            <td>
                                <span class="fw-bold" style="font-size:14px">Rp {{ number_format($pp->amount, 0, ',', '.') }}</span>
                            </td>
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
                            <td>
                                <span style="font-size:12px;color:var(--text-muted)">{{ $pp->catatan ?: '—' }}</span>
                            </td>
                            <td class="text-center">
                                <span style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600">
                                    {{ $sc['label'] }}
                                </span>
                                @if($pp->status === 'rejected' && $pp->rejected_reason)
                                <div class="text-muted mt-1" style="font-size:10px">{{ Str::limit($pp->rejected_reason, 30) }}</div>
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

</div>

{{-- INVOICE: APPROVE / REJECT MODALS --}}
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

{{-- PACKAGE: APPROVE / REJECT MODALS --}}
<div class="modal fade" id="pkgApproveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="pkgApproveForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-check-circle-fill text-success me-2"></i>Setujui Pembayaran Paket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p style="font-size:14px">Apakah Anda yakin ingin menyetujui pembayaran paket ini?</p>
                </div>
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
function showTab(tab) {
    document.getElementById('pane-invoice').style.display = tab === 'invoice' ? '' : 'none';
    document.getElementById('pane-package').style.display = tab === 'package' ? '' : 'none';
    document.getElementById('tab-invoice').classList.toggle('active', tab === 'invoice');
    document.getElementById('tab-package').classList.toggle('active', tab === 'package');
}

function showApproveModal(paymentId) {
    document.getElementById('approveForm').action = `/admin/verifikasi-pembayaran/${paymentId}/approve`;
    new bootstrap.Modal(document.getElementById('approveModal')).show();
}
function showRejectModal(paymentId) {
    document.getElementById('rejectForm').action = `/admin/verifikasi-pembayaran/${paymentId}/reject`;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}

function showPkgApproveModal(pkgPaymentId) {
    document.getElementById('pkgApproveForm').action = `/admin/verifikasi-pembayaran/package/${pkgPaymentId}/approve`;
    new bootstrap.Modal(document.getElementById('pkgApproveModal')).show();
}
function showPkgRejectModal(pkgPaymentId) {
    document.getElementById('pkgRejectForm').action = `/admin/verifikasi-pembayaran/package/${pkgPaymentId}/reject`;
    new bootstrap.Modal(document.getElementById('pkgRejectModal')).show();
}

// Auto-switch to package tab if pkg_status is set in URL
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.has('pkg_status') || urlParams.has('pkg_page')) {
    showTab('package');
}
</script>
@endpush
@endsection
