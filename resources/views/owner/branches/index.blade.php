@extends('layouts.app')
@section('title', 'Monitoring Cabang')
@section('page-title', 'Monitoring Cabang')

@section('content')

{{-- HEADER BANNER --}}
<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                    <i class="bi bi-building-fill"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Monitoring Cabang</h5>
                    <span style="font-size:12px;opacity:.8">Pantau dan kelola performa seluruh cabang Smart Center Indonesia</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <button class="btn fw-semibold px-4" data-bs-toggle="modal" data-bs-target="#addModal"
                style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                <i class="bi bi-plus-lg me-2"></i>Tambah Cabang
            </button>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 fade-up">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Cabang</div>
                    <div class="stat-value text-primary">{{ $total }}</div>
                    <div class="stat-growth text-muted"><i class="bi bi-building me-1"></i>Semua cabang</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-building-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 fade-up" style="animation-delay:.05s">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Cabang Aktif</div>
                    <div class="stat-value text-success">{{ $active }}</div>
                    <div class="stat-growth text-success"><i class="bi bi-check-circle me-1"></i>Beroperasi</div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-building-fill-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 fade-up" style="animation-delay:.10s">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Siswa</div>
                    <div class="stat-value text-warning">{{ $students }}</div>
                    <div class="stat-growth text-muted"><i class="bi bi-people me-1"></i>Semua cabang</div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-people-fill"></i></div>
            </div>
        </div>
    </div>
</div>

{{-- TABLE CARD --}}
<div class="dashboard-card fade-up">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <h6 class="fw-bold mb-0" style="color:var(--text-primary)">
            <i class="bi bi-building text-primary me-2"></i>Daftar Cabang
        </h6>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('owner.branches.export.excel') }}" class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-excel me-1"></i>Excel
            </a>
            <a href="{{ route('owner.branches.export.pdf') }}" class="btn btn-danger btn-sm">
                <i class="bi bi-file-earmark-pdf me-1"></i>PDF
            </a>
            <button onclick="window.print()" class="btn btn-secondary btn-sm">
                <i class="bi bi-printer me-1"></i>Print
            </button>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-lg me-1"></i>Tambah Cabang
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success border-0 rounded-3 mb-3 d-flex align-items-center gap-2" style="background:#f0fdf4;color:#15803d">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger border-0 rounded-3 mb-3">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}
    </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead style="background:var(--input-bg)">
                <tr>
                    <th class="small text-muted fw-semibold py-3">CABANG</th>
                    <th class="small text-muted fw-semibold py-3">LOKASI</th>
                    <th class="small text-muted fw-semibold py-3 text-center">SISWA</th>
                    <th class="small text-muted fw-semibold py-3">KONTAK</th>
                    <th class="small text-muted fw-semibold py-3">FITUR</th>
                    <th class="small text-muted fw-semibold py-3 text-center">STATUS</th>
                    <th class="small text-muted fw-semibold py-3 text-center" style="width:160px">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($branches as $branch)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#c84ddf,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:15px;flex-shrink:0">
                                <i class="bi bi-building"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small">{{ $branch->name }}</div>
                                <div class="text-muted" style="font-size:.72rem">{{ $branch->email ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="small fw-semibold">{{ $branch->city ?? '-' }}</div>
                        <div class="text-muted" style="font-size:.72rem">{{ $branch->regency ?? '' }}</div>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-primary">{{ $branch->students->count() }}</span>
                    </td>
                    <td class="small text-muted">{{ $branch->phone ?? '-' }}</td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            @if($branch->can_students)
                                <span class="badge" style="background:#f3d6fa;color:#461256;font-size:10px">Siswa</span>
                            @endif
                            @if($branch->can_teachers)
                                <span class="badge" style="background:#dcfce7;color:#15803d;font-size:10px">Guru</span>
                            @endif
                            @if($branch->can_schedules)
                                <span class="badge" style="background:#fdf4ff;color:#68117e;font-size:10px">Jadwal</span>
                            @endif
                            @if($branch->can_payments)
                                <span class="badge" style="background:#fef3c7;color:#92400e;font-size:10px">Keuangan</span>
                            @endif
                            @if($branch->can_tryouts)
                                <span class="badge" style="background:#f3e8ff;color:#6b21a8;font-size:10px">Tryout</span>
                            @endif
                        </div>
                    </td>
                    <td class="text-center">
                        @if($branch->status === 'active')
                            <span class="badge" style="background:#dcfce7;color:#15803d">
                                <i class="bi bi-circle-fill me-1" style="font-size:8px"></i>Aktif
                            </span>
                        @else
                            <span class="badge" style="background:#fee2e2;color:#991b1b">
                                <i class="bi bi-circle-fill me-1" style="font-size:8px"></i>Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <form method="POST" action="{{ route('owner.branches.impersonate', $branch) }}" style="display:inline;margin-left:4px">
                                @csrf
                                <button class="btn btn-outline-secondary" title="Masuk sebagai cabang">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                </button>
                            </form>
                            <button class="btn btn-outline-warning"
                                data-bs-toggle="modal" data-bs-target="#editModal{{ $branch->id }}"
                                title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-outline-info"
                                data-bs-toggle="modal" data-bs-target="#resetModal{{ $branch->id }}"
                                title="Reset Password">
                                <i class="bi bi-key"></i>
                            </button>
                            <form method="POST" action="{{ route('owner.branches.destroy', $branch) }}"
                                  onsubmit="return confirmDelete(event, '{{ $branch->name }}')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                @push('modals')
                {{-- EDIT MODAL --}}
                <div class="modal fade" id="editModal{{ $branch->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header border-0" style="background:linear-gradient(135deg,#f6af23,#e09000);color:white">
                                <h6 class="modal-title fw-bold"><i class="bi bi-pencil me-2"></i>Edit Cabang — {{ $branch->name }}</h6>
                                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <form method="POST" action="{{ route('owner.branches.update', $branch) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Nama Cabang</label>
                                        <input type="text" class="form-control form-control-sm" name="name" value="{{ $branch->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Kota</label>
                                        <input type="text" class="form-control form-control-sm" name="city" value="{{ $branch->city }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Status</label>
                                        <select name="status" class="form-select form-select-sm">
                                            <option value="active" {{ $branch->status==='active'?'selected':'' }}>Aktif</option>
                                            <option value="inactive" {{ $branch->status==='inactive'?'selected':'' }}>Nonaktif</option>
                                        </select>
                                    </div>
                                    <button class="btn btn-warning w-100 fw-semibold">
                                        <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RESET PASSWORD MODAL --}}
                <div class="modal fade" id="resetModal{{ $branch->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header border-0" style="background:linear-gradient(135deg,#06b6d4,#68117e);color:white">
                                <h6 class="modal-title fw-bold"><i class="bi bi-key me-2"></i>Reset Password — {{ $branch->name }}</h6>
                                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <form method="POST" action="{{ route('owner.branches.resetPassword', $branch) }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Password Baru</label>
                                        <input type="password" class="form-control form-control-sm" name="password" placeholder="Min. 8 karakter" required minlength="8">
                                    </div>
                                    <button class="btn btn-info text-white w-100 fw-semibold">
                                        <i class="bi bi-key me-2"></i>Reset Password
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endpush

                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="empty-state">
                            <i class="bi bi-building text-muted d-block mb-2" style="font-size:3rem;opacity:.3"></i>
                            <p class="text-muted">Belum ada data cabang</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@stack('modals')

{{-- ADD MODAL --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0" style="background:linear-gradient(135deg,#68117e,#c84ddf);color:white">
                <h6 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Tambah Cabang Baru</h6>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('owner.branches.store') }}">
                    @csrf

                    <h6 class="fw-bold mb-3 text-muted" style="font-size:12px;text-transform:uppercase;letter-spacing:.06em">
                        <i class="bi bi-building me-2 text-primary"></i>Info Cabang
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Cabang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="name" placeholder="Contoh: Cabang Jakarta" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Kota</label>
                            <input type="text" class="form-control form-control-sm" name="city" placeholder="Jakarta">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Kabupaten / Kecamatan</label>
                            <input type="text" class="form-control form-control-sm" name="regency" placeholder="Kebayoran Baru">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Telepon</label>
                            <input type="text" class="form-control form-control-sm" name="phone" placeholder="021-xxxxxxxx">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Alamat</label>
                            <input type="text" class="form-control form-control-sm" name="address" placeholder="Alamat lengkap cabang">
                        </div>
                    </div>

                    <hr class="my-3" style="border-color:var(--card-border)">

                    <h6 class="fw-bold mb-3 text-muted" style="font-size:12px;text-transform:uppercase;letter-spacing:.06em">
                        <i class="bi bi-person-badge me-2 text-success"></i>Akun Login Cabang
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Admin <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="admin_name" placeholder="Nama admin cabang" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Username (opsional)</label>
                            <input type="text" class="form-control form-control-sm" name="admin_username" placeholder="admin.jakarta">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control form-control-sm" name="email" placeholder="admin@cabang.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control form-control-sm" name="password" placeholder="Min. 8 karakter" required minlength="8">
                        </div>
                    </div>

                    <hr class="my-3" style="border-color:var(--card-border)">

                    <h6 class="fw-bold mb-3 text-muted" style="font-size:12px;text-transform:uppercase;letter-spacing:.06em">
                        <i class="bi bi-toggles me-2 text-warning"></i>Fitur Akses
                    </h6>
                    <div class="row g-2 mb-4">
                        @foreach([
                            ['can_students','Manajemen Siswa','people','primary'],
                            ['can_teachers','Manajemen Guru','person-workspace','success'],
                            ['can_schedules','Jadwal & Kelas','calendar-week','info'],
                            ['can_payments','Keuangan','wallet2','warning'],
                            ['can_tryouts','Tryout CBT','ui-checks-grid','purple'],
                        ] as [$name, $label, $icon, $color])
                        <div class="col-md-6">
                            <div class="form-check form-switch p-3 rounded-3" style="background:var(--input-bg);border:1.5px solid var(--card-border);padding-left:3rem !important">
                                <input class="form-check-input" type="checkbox" name="{{ $name }}" id="{{ $name }}" checked>
                                <label class="form-check-label fw-semibold small" for="{{ $name }}">
                                    <i class="bi bi-{{ $icon }} me-1 text-{{ $color==='purple'?'primary':$color }}"></i>{{ $label }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <button class="btn btn-primary w-100 py-2 fw-bold">
                        <i class="bi bi-check-lg me-2"></i>Simpan Cabang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function confirmDelete(e, name) {
    e.preventDefault();
    const form = e.target;
    Swal.fire({
        title: `Hapus cabang "${name}"?`,
        text: 'Semua data terkait cabang ini akan dihapus!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: '<i class="bi bi-trash me-1"></i>Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then(r => { if (r.isConfirmed) form.submit(); });
    return false;
}
</script>
@endpush
