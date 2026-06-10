@extends('layouts.app')
@section('title','Verifikasi Pembayaran Mapel')
@section('page-title','Verifikasi Pembayaran')

@section('content')

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;">
    <h5 class="fw-bold mb-0" style="color:white">Verifikasi Pembayaran Mata Pelajaran</h5>
    <p class="mb-0 mt-1" style="opacity:.75;font-size:13px">Periksa bukti transfer dari siswa</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="stat-title">Menunggu</div>
            <div class="stat-value text-warning">{{ $stats['pending'] }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="stat-title">Terverifikasi</div>
            <div class="stat-value text-success">{{ $stats['verified'] }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="border-top:3px solid #ef4444">
            <div class="stat-title">Ditolak</div>
            <div class="stat-value text-danger">{{ $stats['rejected'] }}</div>
        </div>
    </div>
</div>

<div class="dashboard-card fade-up">
    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-4">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama siswa...">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="pending" @selected(request('status')==='pending')>Menunggu</option>
                <option value="verified" @selected(request('status')==='verified')>Terverifikasi</option>
                <option value="rejected" @selected(request('status')==='rejected')>Ditolak</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="thead-modern">
                <tr>
                    <th>Siswa</th>
                    <th>Mata Pelajaran</th>
                    <th>Nominal</th>
                    <th>Catatan</th>
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
                    <td>Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                    <td style="font-size:12px">{{ $p->catatan ?? '—' }}</td>
                    <td>
                        @if($p->proof)
                        <a href="{{ asset('storage/'.$p->proof) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> Lihat
                        </a>
                        @else —
                        @endif
                    </td>
                    <td>
                        @if($p->status === 'verified')
                        <span class="badge bg-success">Terverifikasi</span>
                        @elseif($p->status === 'rejected')
                        <span class="badge bg-danger" title="{{ $p->rejected_reason }}">Ditolak</span>
                        @else
                        <span class="badge bg-warning text-dark">Menunggu</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @if($p->status === 'pending')
                        <form action="{{ route('admin.course-payments.verify', $p) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i></button>
                        </form>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $p->id }}">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        @else
                        <span class="text-muted small">{{ $p->verifier->name ?? '' }}</span>
                        @endif
                    </td>
                </tr>

                @if($p->status === 'pending')
                <div class="modal fade" id="rejectModal{{ $p->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('admin.course-payments.reject', $p) }}" method="POST" class="modal-content">
                            @csrf
                            <div class="modal-header">
                                <h6 class="modal-title">Tolak Pembayaran</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <label class="form-label">Alasan penolakan</label>
                                <textarea name="rejected_reason" class="form-control" required rows="3"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger">Tolak</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pembayaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $payments->links() }}
</div>

@endsection
