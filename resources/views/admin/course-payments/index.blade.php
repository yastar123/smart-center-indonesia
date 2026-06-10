@extends('layouts.app')
@section('title','Verifikasi Pembayaran Mapel')
@section('page-title','Verifikasi Pembayaran')

@section('content')

{{-- HEADER BANNER --}}
<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                    <i class="bi bi-credit-card-2-front"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Verifikasi Pembayaran Mata Pelajaran</h5>
                    <span style="font-size:12px;opacity:.8">Tinjau dan verifikasi bukti transfer dari siswa</span>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-dismissible d-flex align-items-center gap-2 mb-4 fade show"
     style="border-radius:12px;border:none;background:rgba(16,185,129,.1);border-left:4px solid #10b981 !important">
    <i class="bi bi-check-circle-fill text-success"></i>
    <span>{{ session('success') }}</span>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- STAT CARDS --}}
<div class="row g-3 mb-4 fade-up">
    <div class="col-6 col-md-4">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Menunggu</div>
                    <div class="stat-value text-warning">{{ $stats['pending'] }}</div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-hourglass-split"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Terverifikasi</div>
                    <div class="stat-value text-success">{{ $stats['verified'] }}</div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-check-circle-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="stat-card" style="border-top:3px solid #ef4444">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Ditolak</div>
                    <div class="stat-value text-danger">{{ $stats['rejected'] }}</div>
                </div>
                <div class="stat-icon bg-danger-soft" style="color:white"><i class="bi bi-x-circle-fill"></i></div>
            </div>
        </div>
    </div>
</div>

{{-- FILTER --}}
<div class="dashboard-card mb-4 fade-up">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-12 col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0" placeholder="Cari nama siswa atau mata pelajaran...">
            </div>
        </div>
        <div class="col-6 col-md-3">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="pending"   @selected(request('status')==='pending')>Menunggu</option>
                <option value="verified"  @selected(request('status')==='verified')>Terverifikasi</option>
                <option value="rejected"  @selected(request('status')==='rejected')>Ditolak</option>
            </select>
        </div>
        <div class="col-4 col-md-2">
            <button class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i>Filter</button>
        </div>
        @if(request('search') || request('status'))
        <div class="col-2 col-md-1">
            <a href="{{ route('admin.course-payments.index') }}" class="btn btn-outline-secondary w-100" title="Reset"><i class="bi bi-x-lg"></i></a>
        </div>
        @endif
    </form>
</div>

{{-- TABLE --}}
<div class="dashboard-card fade-up">
    <div class="table-responsive">
        <table class="table table-hover table-modern align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th>Siswa</th>
                    <th>Mata Pelajaran</th>
                    <th>Nominal</th>
                    <th class="d-none d-md-table-cell">Catatan</th>
                    <th>Bukti</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $p)
                <tr>
                    <td class="fw-semibold">{{ $p->student->name ?? '-' }}</td>
                    <td>{{ $p->course->nama ?? '-' }}</td>
                    <td class="fw-semibold text-primary">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                    <td class="d-none d-md-table-cell" style="font-size:12px;color:var(--text-muted)">{{ $p->catatan ?? '—' }}</td>
                    <td>
                        @if($p->proof)
                        <a href="{{ asset('storage/'.$p->proof) }}" target="_blank" class="btn-act-view btn">
                            <i class="bi bi-eye"></i>
                        </a>
                        @else
                        <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td>
                        @if($p->status === 'verified')
                        <span class="badge" style="background:var(--soft-success-bg);color:var(--soft-success-text);border:1px solid var(--soft-success-border)">
                            <i class="bi bi-check-circle me-1"></i>Terverifikasi
                        </span>
                        @elseif($p->status === 'rejected')
                        <span class="badge" style="background:var(--soft-danger-bg);color:var(--soft-danger-text);border:1px solid var(--soft-danger-border)" title="{{ $p->rejected_reason }}">
                            <i class="bi bi-x-circle me-1"></i>Ditolak
                        </span>
                        @else
                        <span class="badge" style="background:rgba(246,175,35,.12);color:#d97706;border:1px solid rgba(246,175,35,.3)">
                            <i class="bi bi-hourglass me-1"></i>Menunggu
                        </span>
                        @endif
                    </td>
                    <td class="text-end">
                        @if($p->status === 'pending')
                        <div class="d-flex justify-content-end gap-1">
                            <form action="{{ route('admin.course-payments.verify', $p) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn-act-pay btn" title="Verifikasi"><i class="bi bi-check-lg"></i></button>
                            </form>
                            <button class="btn-act-del btn" title="Tolak" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $p->id }}">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        @else
                        <span class="text-muted small">{{ $p->verifier->name ?? '—' }}</span>
                        @endif
                    </td>
                </tr>

                @if($p->status === 'pending')
                <div class="modal fade" id="rejectModal{{ $p->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('admin.course-payments.reject', $p) }}" method="POST" class="modal-content" style="border-radius:20px;border:none">
                            @csrf
                            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#7f1d1d,#dc2626);border-radius:20px 20px 0 0">
                                <h6 class="modal-title fw-bold text-white"><i class="bi bi-x-circle me-2"></i>Tolak Pembayaran</h6>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <label class="form-label fw-semibold">Alasan penolakan <span class="text-danger">*</span></label>
                                <textarea name="rejected_reason" class="form-control" required rows="3" placeholder="Tuliskan alasan penolakan..."></textarea>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger px-4"><i class="bi bi-x-circle me-2"></i>Tolak</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size:2.5rem;opacity:.2;display:block;margin-bottom:12px"></i>
                        <div class="text-muted">Belum ada pengajuan pembayaran.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($payments->hasPages())
    <div class="mt-3 d-flex justify-content-center">
        {{ $payments->links() }}
    </div>
    @endif
</div>

@endsection
