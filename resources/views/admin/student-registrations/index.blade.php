@extends('layouts.app')
@section('title', 'Siswa Sementara')
@section('page-title', 'Siswa Sementara')

@section('content')
<div class="dashboard-card mb-4 fade-up">
    <div class="row g-3 align-items-center">
        <div class="col-md-8">
            <h5 class="fw-bold mb-1">Data Calon Siswa</h5>
            <p class="text-muted mb-0" style="font-size:12px">Daftar pendaftar yang menunggu verifikasi admin</p>
        </div>
        <div class="col-md-4 text-md-end">
            <span class="badge bg-warning text-dark">{{ $stats['pending'] }} menunggu</span>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-title">Total</div>
            <div class="stat-value text-primary">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-title">Pending</div>
            <div class="stat-value text-warning">{{ $stats['pending'] }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-title">Verified</div>
            <div class="stat-value text-success">{{ $stats['verified'] }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-title">Rejected</div>
            <div class="stat-value text-danger">{{ $stats['rejected'] }}</div>
        </div>
    </div>
</div>

<div class="dashboard-card fade-up">
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-6">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari nama / no registrasi / HP">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>No. Reg</th>
                    <th>Nama</th>
                    <th>HP</th>
                    <th>Orang Tua</th>
                    <th>Cabang</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $registration)
                <tr>
                    <td><code>{{ $registration->no_reg }}</code></td>
                    <td>
                        <div class="fw-semibold">{{ $registration->name }}</div>
                        <div class="small text-muted">{{ $registration->program ?? '-' }} · {{ $registration->schedule_time ?? '-' }}</div>
                    </td>
                    <td>{{ $registration->phone ?? '-' }}</td>
                    <td>{{ $registration->parent_name ?? '-' }}</td>
                    <td>{{ $registration->branch ?? '-' }}</td>
                    <td>
                        <span class="badge bg-{{ $registration->status === 'pending' ? 'warning text-dark' : ($registration->status === 'verified' ? 'success' : 'danger') }}">
                            {{ ucfirst($registration->status) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            @if($registration->status === 'pending')
                            <a href="{{ route('admin.student-registrations.verify', $registration) }}" class="btn btn-sm btn-success" onclick="return confirm('Verifikasi data ini dan pindahkan ke daftar siswa?')">
                                <i class="bi bi-check2"></i>
                            </a>
                            @endif
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteRegistration({{ $registration->id }})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">Belum ada data pendaftaran.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($registrations->hasPages())
    <div class="mt-3">{{ $registrations->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function deleteRegistration(id) {
    if (!confirm('Hapus data pendaftaran ini?')) return;

    $.ajax({
        url: '/admin/student-registrations/' + id,
        type: 'POST',
        data: {
            _method: 'DELETE',
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(res) {
            if (res.success) {
                location.reload();
            }
        }
    });
}
</script>
@endpush
