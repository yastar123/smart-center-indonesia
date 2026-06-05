@extends('layouts.app')
@section('title', 'Data Siswa')
@section('page-title', 'Data Siswa')

@section('content')

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Siswa</div>
                    <div class="stat-value text-primary">{{ $stats['total'] }}</div>
                    <div class="stat-growth text-muted">
                        <i class="bi bi-people me-1"></i>Semua cabang
                    </div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Siswa Aktif</div>
                    <div class="stat-value text-success">{{ $stats['aktif'] }}</div>
                    <div class="stat-growth text-success">
                        <i class="bi bi-check-circle me-1"></i>Berkegiatan
                    </div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white">
                    <i class="bi bi-person-check-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Laki-laki</div>
                    <div class="stat-value text-primary">{{ $stats['male'] }}</div>
                    <div class="stat-growth text-muted">
                        <i class="bi bi-gender-male me-1"></i>Siswa putra
                    </div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#3b82f6,#60a5fa);color:white">
                    <i class="bi bi-person-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Perempuan</div>
                    <div class="stat-value" style="color:#ec4899">{{ $stats['female'] }}</div>
                    <div class="stat-growth text-muted">
                        <i class="bi bi-gender-female me-1"></i>Siswa putri
                    </div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#ec4899,#f472b6);color:white">
                    <i class="bi bi-person-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MAIN TABLE CARD --}}
<div class="dashboard-card fade-up">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h6 class="fw-bold mb-1" style="color:var(--text-primary)">
                <i class="bi bi-people-fill text-primary me-2"></i>Daftar Siswa
            </h6>
            <p class="text-muted mb-0" style="font-size:12px">Kelola data seluruh siswa di semua cabang</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-outline-secondary btn-sm" onclick="window.print()" title="Print">
                <i class="bi bi-printer me-1"></i><span class="d-none d-md-inline">Print</span>
            </button>
            <button class="btn btn-primary btn-sm" onclick="openModal()">
                <i class="bi bi-plus-lg me-1"></i>Tambah Siswa
            </button>
        </div>
    </div>

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('admin.students.index') }}" id="filterForm">
        <div class="row g-2 mb-4">
            <div class="col-12 col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="background:var(--input-bg);border:1.5px solid var(--card-border);border-right:none;border-radius:10px 0 0 10px">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control" placeholder="Cari nama atau NIS..."
                           style="border-left:none;border-radius:0 10px 10px 0"
                           oninput="debounceFilter()">
                </div>
            </div>
            <div class="col-6 col-md-2">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="aktif"    {{ request('status')==='aktif'    ? 'selected':'' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status')==='nonaktif' ? 'selected':'' }}>Nonaktif</option>
                    <option value="lulus"    {{ request('status')==='lulus'    ? 'selected':'' }}>Lulus</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="gender" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Gender</option>
                    <option value="L" {{ request('gender')==='L' ? 'selected':'' }}>Laki-laki</option>
                    <option value="P" {{ request('gender')==='P' ? 'selected':'' }}>Perempuan</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <select name="branch_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Cabang</option>
                    @foreach($branches as $b)
                    <option value="{{ $b->id }}" {{ request('branch_id')==$b->id ? 'selected':'' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-1">
                @if(request()->hasAny(['search','status','gender','branch_id']))
                <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-outline-secondary w-100" title="Reset filter">
                    <i class="bi bi-x-lg"></i>
                </a>
                @else
                <button type="submit" class="btn btn-sm btn-outline-primary w-100" title="Filter">
                    <i class="bi bi-funnel"></i>
                </button>
                @endif
            </div>
        </div>
    </form>

    {{-- Active filters badge --}}
    @if(request()->hasAny(['search','status','gender','branch_id']))
    <div class="d-flex gap-2 mb-3 flex-wrap">
        @if(request('search'))
        <span class="badge" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;font-size:12px;padding:6px 12px;border-radius:8px">
            <i class="bi bi-search me-1"></i>{{ request('search') }}
        </span>
        @endif
        @if(request('status'))
        <span class="badge" style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;font-size:12px;padding:6px 12px;border-radius:8px">
            <i class="bi bi-circle-fill me-1" style="font-size:8px"></i>{{ ucfirst(request('status')) }}
        </span>
        @endif
        @if(request('gender'))
        <span class="badge" style="background:#fdf2f8;color:#be185d;border:1px solid #fbcfe8;font-size:12px;padding:6px 12px;border-radius:8px">
            <i class="bi bi-person me-1"></i>{{ request('gender')==='L' ? 'Laki-laki':'Perempuan' }}
        </span>
        @endif
        <small class="text-muted align-self-center">{{ $students->total() }} hasil ditemukan</small>
    </div>
    @endif

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead style="background:var(--input-bg)">
                <tr>
                    <th class="small text-muted fw-semibold py-3" style="width:46px">#</th>
                    <th class="small text-muted fw-semibold py-3">SISWA</th>
                    <th class="small text-muted fw-semibold py-3">NIS</th>
                    <th class="small text-muted fw-semibold py-3 d-none d-md-table-cell">CABANG</th>
                    <th class="small text-muted fw-semibold py-3 d-none d-lg-table-cell">KELAS</th>
                    <th class="small text-muted fw-semibold py-3 d-none d-xl-table-cell">ORANG TUA</th>
                    <th class="small text-muted fw-semibold py-3">STATUS</th>
                    <th class="small text-muted fw-semibold py-3 text-center" style="width:110px">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $i => $s)
                @php
                    $avatar = 'https://ui-avatars.com/api/?name='.urlencode($s->name).'&background='.($s->gender==='P'?'ec4899':'3b82f6').'&color=fff&size=80';
                    $statusMap = [
                        'aktif'    => ['bg'=>'#dcfce7','color'=>'#15803d','label'=>'Aktif'],
                        'nonaktif' => ['bg'=>'#f3f4f6','color'=>'#6b7280','label'=>'Nonaktif'],
                        'lulus'    => ['bg'=>'#dbeafe','color'=>'#1d4ed8','label'=>'Lulus'],
                    ];
                    $badge = $statusMap[$s->status] ?? ['bg'=>'#f3f4f6','color'=>'#6b7280','label'=>ucfirst($s->status??'-')];
                @endphp
                <tr>
                    <td class="text-muted small fw-semibold">
                        {{ $students->firstItem() + $i }}
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="position-relative flex-shrink-0">
                                <img src="{{ $s->photo ? Storage::url($s->photo) : $avatar }}"
                                     class="rounded-circle"
                                     width="40" height="40"
                                     style="object-fit:cover;border:2.5px solid {{ $s->gender==='P' ? '#f9a8d4':'#93c5fd' }}"
                                     loading="lazy">
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:13.5px">{{ $s->name }}</div>
                                <div class="text-muted" style="font-size:11px">
                                    {{ $s->gender==='L'?'👦 Laki-laki':'👧 Perempuan' }}
                                    @if($s->birth_place) · {{ $s->birth_place }} @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <code style="background:var(--input-bg);padding:3px 8px;border-radius:6px;font-size:12px;color:var(--primary)">
                            {{ $s->nis }}
                        </code>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <span class="badge" style="background:var(--input-bg);color:var(--text-muted);border:1px solid var(--card-border);font-size:11px">
                            <i class="bi bi-building me-1"></i>{{ $s->branch->name ?? '-' }}
                        </span>
                    </td>
                    <td class="small text-muted d-none d-lg-table-cell">{{ $s->grade ?? '–' }}</td>
                    <td class="d-none d-xl-table-cell">
                        <div style="font-size:13px">{{ $s->parent_name ?? '–' }}</div>
                        @if($s->parent_phone)
                        <div class="text-muted" style="font-size:11px"><i class="bi bi-telephone me-1"></i>{{ $s->parent_phone }}</div>
                        @endif
                    </td>
                    <td>
                        <span class="badge" style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};padding:5px 10px;border-radius:8px;font-size:11px;font-weight:600">
                            <i class="bi bi-circle-fill me-1" style="font-size:7px"></i>{{ $badge['label'] }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <button class="btn btn-sm" onclick="showDetail({{ $s->id }})"
                                    title="Detail" style="background:var(--input-bg);border:1px solid var(--card-border);color:var(--text-muted);border-radius:8px;padding:5px 8px">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning" onclick="editStudent({{ $s->id }})"
                                    title="Edit" style="border-radius:8px;padding:5px 8px">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteStudent({{ $s->id }}, '{{ addslashes($s->name) }}')"
                                    title="Hapus" style="border-radius:8px;padding:5px 8px">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-5">
                        <div class="text-center">
                            <div style="width:80px;height:80px;border-radius:50%;background:var(--input-bg);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                                <i class="bi bi-people text-muted" style="font-size:2rem;opacity:.5"></i>
                            </div>
                            <p class="text-muted mb-3">
                                @if(request()->hasAny(['search','status','gender','branch_id']))
                                    Tidak ada siswa yang cocok dengan filter yang dipilih.
                                @else
                                    Belum ada data siswa. Tambahkan siswa pertama Anda!
                                @endif
                            </p>
                            @if(request()->hasAny(['search','status','gender','branch_id']))
                            <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-outline-secondary me-2">
                                <i class="bi bi-x me-1"></i>Reset Filter
                            </a>
                            @endif
                            <button class="btn btn-sm btn-primary" onclick="openModal()">
                                <i class="bi bi-plus-lg me-1"></i>Tambah Siswa
                            </button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    @if($students->hasPages())
    <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 gap-2 pt-3" style="border-top:1px solid var(--card-border)">
        <small class="text-muted">
            Menampilkan <strong>{{ $students->firstItem() ?? 0 }}</strong>–<strong>{{ $students->lastItem() ?? 0 }}</strong>
            dari <strong>{{ $students->total() }}</strong> siswa
        </small>
        <div>{{ $students->withQueryString()->links('pagination::bootstrap-5') }}</div>
    </div>
    @else
    <div class="text-muted small mt-3 pt-3" style="border-top:1px solid var(--card-border)">
        Total: <strong>{{ $students->total() }}</strong> siswa
    </div>
    @endif

</div>

{{-- ===== MODAL TAMBAH / EDIT ===== --}}
<div class="modal fade" id="studentModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#2563eb,#6366f1);color:#fff">
                <div>
                    <h6 class="modal-title fw-bold mb-0" id="modalTitle">
                        <i class="bi bi-person-plus me-2"></i>Tambah Siswa Baru
                    </h6>
                    <div style="font-size:12px;opacity:.75;margin-top:3px">Isi data lengkap siswa di bawah ini</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <form id="studentForm" enctype="multipart/form-data" novalidate>
                    @csrf
                    <input type="hidden" id="studentId">

                    {{-- PHOTO UPLOAD --}}
                    <div class="text-center mb-4 pb-4" style="border-bottom:1px solid var(--card-border)">
                        <div class="position-relative d-inline-block">
                            <img id="photoPreview"
                                 src="https://ui-avatars.com/api/?name=Siswa&background=3b82f6&color=fff&size=120"
                                 class="rounded-circle"
                                 width="100" height="100"
                                 style="object-fit:cover;border:3px solid #3b82f6;box-shadow:0 8px 24px rgba(59,130,246,.3)">
                            <label class="position-absolute bottom-0 end-0 d-flex align-items-center justify-content-center"
                                   style="width:32px;height:32px;background:#3b82f6;border-radius:50%;cursor:pointer;border:2.5px solid white;box-shadow:0 2px 8px rgba(0,0,0,.2)">
                                <i class="bi bi-camera-fill text-white" style="font-size:13px"></i>
                                <input type="file" name="photo" id="photoInput" class="d-none" accept="image/*">
                            </label>
                        </div>
                        <div class="text-muted mt-2" style="font-size:12px">Klik ikon kamera untuk upload foto (opsional)</div>
                    </div>

                    {{-- SECTION: DATA PRIBADI --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:4px;height:20px;background:linear-gradient(#3b82f6,#6366f1);border-radius:4px"></div>
                            <span class="fw-bold text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.08em">Data Pribadi</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control form-control-sm" placeholder="Nama lengkap sesuai KK" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">NIS <span class="text-danger">*</span></label>
                                <input type="text" name="nis" id="nis" class="form-control form-control-sm" placeholder="Nomor Induk Siswa" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select name="gender" id="gender" class="form-select form-select-sm" required>
                                    <option value="">Pilih...</option>
                                    <option value="L">👦 Laki-laki</option>
                                    <option value="P">👧 Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Tempat Lahir</label>
                                <input type="text" name="birth_place" id="birth_place" class="form-control form-control-sm" placeholder="Kota kelahiran">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Tanggal Lahir</label>
                                <input type="date" name="birth_date" id="birth_date" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small fw-semibold">Alamat</label>
                                <textarea name="address" id="address" class="form-control form-control-sm" rows="2" placeholder="Alamat lengkap tempat tinggal"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION: DATA AKADEMIK --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:4px;height:20px;background:linear-gradient(#10b981,#059669);border-radius:4px"></div>
                            <span class="fw-bold text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.08em">Data Akademik</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Cabang <span class="text-danger">*</span></label>
                                <select name="branch_id" id="branch_id" class="form-select form-select-sm" required>
                                    <option value="">Pilih Cabang</option>
                                    @foreach($branches as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Asal Sekolah</label>
                                <input type="text" name="school_name" id="school_name" class="form-control form-control-sm" placeholder="Nama sekolah asal">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Kelas / Tingkat</label>
                                <input type="text" name="grade" id="grade" class="form-control form-control-sm" placeholder="XII IPA 1">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">No. HP Siswa</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">+62</span>
                                    <input type="text" name="phone" id="phone" class="form-control" placeholder="8xxxxxxxxxx">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION: DATA ORANG TUA --}}
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:4px;height:20px;background:linear-gradient(#f59e0b,#d97706);border-radius:4px"></div>
                            <span class="fw-bold text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.08em">Data Orang Tua / Wali</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nama Orang Tua / Wali</label>
                                <input type="text" name="parent_name" id="parent_name" class="form-control form-control-sm" placeholder="Nama ayah / ibu / wali">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">HP Orang Tua</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">+62</span>
                                    <input type="text" name="parent_phone" id="parent_phone" class="form-control" placeholder="8xxxxxxxxxx">
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer border-0 p-4" style="background:var(--input-bg)">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius:10px">
                    <i class="bi bi-x me-1"></i>Batal
                </button>
                <button type="button" class="btn btn-primary px-5 fw-semibold" id="saveBtn" onclick="saveStudent()" style="border-radius:10px">
                    <i class="bi bi-check-lg me-1"></i>Simpan Data
                </button>
            </div>

        </div>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#2563eb,#6366f1);color:#fff">
                <h6 class="modal-title fw-bold"><i class="bi bi-person-lines-fill me-2"></i>Detail Siswa</h6>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="detailBody">
                <div class="text-center py-5"><div class="spinner-border text-primary"></div></div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ---- OPEN MODAL (ADD) ----
function openModal() {
    document.getElementById('studentForm').reset();
    document.getElementById('studentId').value = '';
    document.getElementById('modalTitle').innerHTML = '<i class="bi bi-person-plus me-2"></i>Tambah Siswa Baru';
    document.getElementById('photoPreview').src = 'https://ui-avatars.com/api/?name=Siswa&background=3b82f6&color=fff&size=120';
    new bootstrap.Modal('#studentModal').show();
}

// ---- EDIT ----
function editStudent(id) {
    $.get('/admin/students/' + id, function(res) {
        const s = res.data;
        document.getElementById('modalTitle').innerHTML = '<i class="bi bi-pencil me-2"></i>Edit Data Siswa';
        document.getElementById('studentId').value  = s.id;
        document.getElementById('name').value        = s.name        ?? '';
        document.getElementById('nis').value         = s.nis         ?? '';
        document.getElementById('gender').value      = s.gender      ?? '';
        document.getElementById('birth_date').value  = s.birth_date  ?? '';
        document.getElementById('birth_place').value = s.birth_place ?? '';
        document.getElementById('branch_id').value   = s.branch_id   ?? '';
        document.getElementById('school_name').value = s.school_name ?? '';
        document.getElementById('grade').value       = s.grade       ?? '';
        document.getElementById('phone').value       = s.phone       ?? '';
        document.getElementById('parent_name').value  = s.parent_name  ?? '';
        document.getElementById('parent_phone').value = s.parent_phone ?? '';
        document.getElementById('address').value     = s.address     ?? '';
        const avatar = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(s.name) + '&background=3b82f6&color=fff&size=120';
        document.getElementById('photoPreview').src = s.photo ? '/storage/' + s.photo : avatar;
        new bootstrap.Modal('#studentModal').show();
    }).fail(function() {
        Swal.fire({ icon:'error', title:'Gagal', text:'Tidak dapat memuat data siswa.' });
    });
}

// ---- DETAIL ----
function showDetail(id) {
    document.getElementById('detailBody').innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';
    new bootstrap.Modal('#detailModal').show();
    $.get('/admin/students/' + id, function(res) {
        const s = res.data;
        const avatar = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(s.name) + '&background=' + (s.gender==='P'?'ec4899':'3b82f6') + '&color=fff&size=120';
        const statusMap = {aktif:'#dcfce7:#15803d:Aktif', nonaktif:'#f3f4f6:#6b7280:Nonaktif', lulus:'#dbeafe:#1d4ed8:Lulus'};
        const [sbg,scol,slbl] = (statusMap[s.status]||'#f3f4f6:#6b7280:'+s.status).split(':');
        document.getElementById('detailBody').innerHTML = `
            <div style="text-align:center;padding:24px 24px 16px">
                <img src="${s.photo ? '/storage/'+s.photo : avatar}" class="rounded-circle mb-3"
                     width="90" height="90" style="object-fit:cover;border:3px solid #3b82f6;box-shadow:0 8px 24px rgba(59,130,246,.25)">
                <h6 class="fw-bold mb-1" style="font-size:16px">${s.name}</h6>
                <code style="background:#eff6ff;color:#1d4ed8;padding:3px 10px;border-radius:6px;font-size:12px">${s.nis}</code>
                <div style="margin-top:10px">
                    <span style="background:${sbg};color:${scol};padding:4px 12px;border-radius:8px;font-size:12px;font-weight:600">
                        ● ${slbl}
                    </span>
                </div>
            </div>
            <div style="padding:0 24px 24px">
                <table style="width:100%;border-collapse:collapse">
                    ${row('Cabang', s.branch?.name ?? '–')}
                    ${row('Kelas', s.grade ?? '–')}
                    ${row('Gender', s.gender==='L'?'👦 Laki-laki':'👧 Perempuan')}
                    ${row('Tgl Lahir', s.birth_date ?? '–')}
                    ${row('Tempat Lahir', s.birth_place ?? '–')}
                    ${row('Sekolah', s.school_name ?? '–')}
                    ${row('No. HP', s.phone ?? '–')}
                    ${row('Orang Tua', (s.parent_name??'–') + (s.parent_phone?' · '+s.parent_phone:''))}
                    ${row('Alamat', s.address ?? '–')}
                    ${row('Bergabung', s.created_at ? s.created_at.substr(0,10) : '–')}
                </table>
            </div>
        `;
    }).fail(function() {
        document.getElementById('detailBody').innerHTML = '<div class="text-center py-5 text-muted">Gagal memuat data</div>';
    });
}

function row(label, val) {
    return `<tr style="border-bottom:1px solid var(--card-border,#e2e8f0)">
        <td style="padding:9px 4px 9px 0;color:#6b7280;font-size:12.5px;width:38%">${label}</td>
        <td style="padding:9px 0;font-size:13px;font-weight:600;color:var(--text-primary,#0f172a)">${val}</td>
    </tr>`;
}

// ---- SAVE ----
function saveStudent() {
    const id  = document.getElementById('studentId').value;
    const url = id ? '/admin/students/' + id : '{{ route("admin.students.store") }}';
    const fd  = new FormData(document.getElementById('studentForm'));
    if (id) fd.append('_method', 'PUT');

    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

    $.ajax({
        url, method: 'POST', data: fd, processData: false, contentType: false,
        success(res) {
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('studentModal'))?.hide();
                Swal.fire({
                    icon: 'success', title: 'Berhasil!',
                    text: res.message, timer: 2200,
                    showConfirmButton: false,
                    iconColor: '#10b981'
                }).then(() => location.reload());
            }
        },
        error(xhr) {
            const errors = xhr.responseJSON?.errors;
            const msg = errors ? '<ul class="text-start mb-0">' + Object.values(errors).flat().map(e=>`<li>${e}</li>`).join('') + '</ul>'
                               : (xhr.responseJSON?.message ?? 'Terjadi kesalahan. Coba lagi.');
            Swal.fire({ icon:'error', title:'Gagal Menyimpan', html: msg });
        },
        complete() {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Simpan Data';
        }
    });
}

// ---- DELETE ----
function deleteStudent(id, name) {
    Swal.fire({
        title: `Hapus "${name}"?`,
        text: 'Data siswa ini akan dihapus secara permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="bi bi-trash me-1"></i>Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then(r => {
        if (r.isConfirmed) {
            $.post('/admin/students/' + id, {
                _method: 'DELETE',
                _token: document.querySelector('meta[name=csrf-token]').content
            }, function(res) {
                if (res.success) {
                    Swal.fire({ icon:'success', title:'Terhapus!', text:res.message, timer:2000, showConfirmButton:false })
                        .then(() => location.reload());
                }
            }).fail(function() {
                Swal.fire({ icon:'error', title:'Gagal!', text:'Tidak dapat menghapus data.' });
            });
        }
    });
}

// ---- PHOTO PREVIEW ----
document.getElementById('photoInput').addEventListener('change', function() {
    if (this.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { document.getElementById('photoPreview').src = e.target.result; };
        reader.readAsDataURL(this.files[0]);
    }
});

// ---- SEARCH DEBOUNCE ----
let filterTimer;
function debounceFilter() {
    clearTimeout(filterTimer);
    filterTimer = setTimeout(() => document.getElementById('filterForm').submit(), 500);
}
</script>
@endpush
