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

    {{-- FILTER --}}
    <div class="dashboard-card mb-4">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold" style="font-size:12px">Cari Siswa / No Pembayaran</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Nama siswa atau nomor pembayaran..." value="{{ request('search') }}">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold" style="font-size:12px">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Menunggu</option>
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

    {{-- TABLE --}}
    <div class="dashboard-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr style="background:var(--input-bg);color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.05em">
                        <th class="px-3 py-3">No Pembayaran</th>
                        <th class="py-3">Siswa</th>
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
                            <a href="{{ route('admin.verifikasi-pembayaran.show', $payment) }}"
                                class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:12px">
                                <i class="bi bi-eye me-1"></i>Tinjau
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
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

{{-- APPROVE / REJECT MODALS --}}
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

@push('scripts')
<script>
function showApproveModal(paymentId) {
    document.getElementById('approveForm').action = `/admin/verifikasi-pembayaran/${paymentId}/approve`;
    new bootstrap.Modal(document.getElementById('approveModal')).show();
}
function showRejectModal(paymentId) {
    document.getElementById('rejectForm').action = `/admin/verifikasi-pembayaran/${paymentId}/reject`;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>
@endpush
@endsection
