@extends('layouts.app')
@section('title', 'Data Siswa')
@section('page-title', 'Data Siswa')

@section('content')

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary mx-auto mb-2">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-value text-primary">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Siswa</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-icon bg-success bg-opacity-10 text-success mx-auto mb-2">
                <i class="bi bi-person-check-fill"></i>
            </div>
            <div class="stat-value text-success">{{ $stats['aktif'] }}</div>
            <div class="stat-label">Siswa Aktif</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning mx-auto mb-2">
                <i class="bi bi-gender-male"></i>
            </div>
            <div class="stat-value text-warning">{{ $stats['male'] }}</div>
            <div class="stat-label">Laki-laki</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-icon bg-danger bg-opacity-10 text-danger mx-auto mb-2">
                <i class="bi bi-gender-female"></i>
            </div>
            <div class="stat-value text-danger">{{ $stats['female'] }}</div>
            <div class="stat-label">Perempuan</div>
        </div>
    </div>
</div>

{{-- Table Card --}}
<div class="stat-card">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h6 class="fw-bold mb-0"><i class="bi bi-people text-primary me-2"></i>Daftar Siswa</h6>
        <button class="btn btn-primary btn-sm px-3" onclick="openModal()">
            <i class="bi bi-plus-lg me-1"></i> Tambah Siswa
        </button>
    </div>

    {{-- Filter (pakai form GET) --}}
    <form method="GET" action="{{ route('admin.students.index') }}">
        <div class="row g-2 mb-3">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control border-start-0" placeholder="Cari nama atau NIS...">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="aktif"    {{ request('status') == 'aktif'    ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="lulus"    {{ request('status') == 'lulus'    ? 'selected' : '' }}>Lulus</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="branch_id" class="form-select form-select-sm">
                    <option value="">Semua Cabang</option>
                    @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="bi bi-funnel"></i> Filter
                </button>
            </div>
        </div>
    </form>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead style="background:#f8fafc">
                <tr>
                    <th class="text-muted small fw-semibold" style="width:45px">#</th>
                    <th class="text-muted small fw-semibold">SISWA</th>
                    <th class="text-muted small fw-semibold">NIS</th>
                    <th class="text-muted small fw-semibold">CABANG</th>
                    <th class="text-muted small fw-semibold">KELAS</th>
                    <th class="text-muted small fw-semibold">ORANG TUA</th>
                    <th class="text-muted small fw-semibold">STATUS</th>
                    <th class="text-muted small fw-semibold text-center" style="width:100px">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $i => $s)
                @php
                    $avatar = 'https://ui-avatars.com/api/?name='.urlencode($s->name).'&background='.($s->gender === 'P' ? 'ec4899' : '4f8ef7').'&color=fff&size=40';
                    $statusMap = [
                        'aktif'    => ['bg' => '#dcfce7', 'color' => '#15803d', 'label' => 'Aktif'],
                        'nonaktif' => ['bg' => '#f3f4f6', 'color' => '#6b7280', 'label' => 'Nonaktif'],
                        'lulus'    => ['bg' => '#dbeafe', 'color' => '#1d4ed8', 'label' => 'Lulus'],
                    ];
                    $badge = $statusMap[$s->status] ?? null;
                @endphp
                <tr>
                    <td class="text-muted small">{{ $students->firstItem() + $i }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $s->photo ? Storage::url($s->photo) : $avatar }}"
                                 class="rounded-circle" width="38" height="38"
                                 style="object-fit:cover;border:2px solid #e5e7eb">
                            <div>
                                <div class="fw-semibold small">{{ $s->name }}</div>
                                <div class="text-muted" style="font-size:.72rem">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $s->birth_place ?? 'Tidak diketahui' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td><code class="small">{{ $s->nis }}</code></td>
                    <td><span class="badge bg-light text-dark small">{{ $s->branch->name ?? '-' }}</span></td>
                    <td><span class="small">{{ $s->grade ?? '-' }}</span></td>
                    <td>
                        <div class="small">{{ $s->parent_name ?? '-' }}</div>
                        <div class="text-muted" style="font-size:.72rem">{{ $s->parent_phone ?? '' }}</div>
                    </td>
                    <td>
                        @if($badge)
                        <span class="badge rounded-pill" style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }}">
                            ● {{ $badge['label'] }}
                        </span>
                        @else
                        <span class="badge bg-secondary">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-info"    onclick="showDetail({{ $s->id }})" title="Detail"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-outline-warning" onclick="editStudent({{ $s->id }})" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-outline-danger"  onclick="deleteStudent({{ $s->id }})" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size:3rem"></i>
                        <div class="text-muted mt-2">Belum ada data siswa</div>
                        <button class="btn btn-sm btn-primary mt-2" onclick="openModal()">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Siswa
                        </button>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
        <small class="text-muted">
            Menampilkan {{ $students->firstItem() ?? 0 }}-{{ $students->lastItem() ?? 0 }}
            dari {{ $students->total() }} siswa
        </small>
        <div>{{ $students->withQueryString()->links('pagination::bootstrap-5') }}</div>
    </div>
</div>

{{-- ===== MODAL TAMBAH / EDIT ===== --}}
<div class="modal fade" id="studentModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            {{-- Header --}}
            <div class="modal-header border-0" style="background:linear-gradient(135deg,#4f8ef7,#6c63ff);color:#fff">
                <h6 class="modal-title fw-bold" id="modalTitle">
                    <i class="bi bi-person-plus me-2"></i>Tambah Siswa
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <form id="studentForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="studentId">

                    {{-- Foto --}}
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img id="photoPreview"
                                 src="https://ui-avatars.com/api/?name=S&background=4f8ef7&color=fff&size=100"
                                 class="rounded-circle border border-3 border-primary"
                                 width="90" height="90" style="object-fit:cover">
                            <label class="position-absolute bottom-0 end-0 btn btn-primary btn-sm rounded-circle p-1"
                                   style="width:28px;height:28px;cursor:pointer">
                                <i class="bi bi-camera" style="font-size:.75rem"></i>
                                <input type="file" name="photo" id="photoInput" class="d-none" accept="image/*">
                            </label>
                        </div>
                        <div class="text-muted small mt-1">Klik kamera untuk upload foto</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control form-control-sm" placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">NIS <span class="text-danger">*</span></label>
                            <input type="text" name="nis" id="nis" class="form-control form-control-sm" placeholder="Nomor Induk Siswa" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="gender" id="gender" class="form-select form-select-sm" required>
                                <option value="">Pilih</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Tanggal Lahir</label>
                            <input type="date" name="birth_date" id="birth_date" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Cabang <span class="text-danger">*</span></label>
                            <select name="branch_id" id="branch_id" class="form-select form-select-sm" required>
                                <option value="">Pilih Cabang</option>
                                @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Asal Sekolah</label>
                            <input type="text" name="school_name" id="school_name" class="form-control form-control-sm" placeholder="Nama sekolah">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Kelas / Grade</label>
                            <input type="text" name="grade" id="grade" class="form-control form-control-sm" placeholder="Contoh: XII IPA 1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">No. HP Siswa</label>
                            <input type="text" name="phone" id="phone" class="form-control form-control-sm" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Orang Tua</label>
                            <input type="text" name="parent_name" id="parent_name" class="form-control form-control-sm" placeholder="Nama ayah/ibu">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">HP Orang Tua</label>
                            <input type="text" name="parent_phone" id="parent_phone" class="form-control form-control-sm" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Tempat Lahir</label>
                            <input type="text" name="birth_place" id="birth_place" class="form-control form-control-sm" placeholder="Kota kelahiran">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Alamat</label>
                            <textarea name="address" id="address" class="form-control form-control-sm" rows="2" placeholder="Alamat lengkap"></textarea>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer border-0 bg-light rounded-bottom">
                <button type="button" class="btn btn-sm btn-light px-4" data-bs-dismiss="modal">
                    <i class="bi bi-x me-1"></i>Batal
                </button>
                <button type="button" class="btn btn-sm btn-primary px-4" onclick="saveStudent()">
                    <i class="bi bi-check-lg me-1"></i>Simpan Data
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Detail --}}
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0" style="background:linear-gradient(135deg,#4f8ef7,#6c63ff);color:#fff">
                <h6 class="modal-title fw-bold"><i class="bi bi-person-lines-fill me-2"></i>Detail Siswa</h6>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailBody"></div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let modalInstance = null;

function openModal() {
    $('#studentForm')[0].reset();
    $('#studentId').val('');
    $('#modalTitle').html('<i class="bi bi-person-plus me-2"></i>Tambah Siswa');
    $('#photoPreview').attr('src', 'https://ui-avatars.com/api/?name=S&background=4f8ef7&color=fff&size=100');
    modalInstance = new bootstrap.Modal('#studentModal');
    modalInstance.show();
}

function editStudent(id) {
    $.get(`/admin/students/${id}`, function(res) {
        const s = res.data;
        $('#modalTitle').html('<i class="bi bi-pencil me-2"></i>Edit Siswa');
        $('#studentId').val(s.id);
        $('#name').val(s.name);         $('#nis').val(s.nis);
        $('#gender').val(s.gender);     $('#birth_date').val(s.birth_date);
        $('#birth_place').val(s.birth_place); $('#branch_id').val(s.branch_id);
        $('#school_name').val(s.school_name); $('#grade').val(s.grade);
        $('#phone').val(s.phone);       $('#parent_name').val(s.parent_name);
        $('#parent_phone').val(s.parent_phone); $('#address').val(s.address);
        const avatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(s.name)}&background=4f8ef7&color=fff&size=100`;
        $('#photoPreview').attr('src', s.photo ? '/storage/'+s.photo : avatar);
        modalInstance = new bootstrap.Modal('#studentModal');
        modalInstance.show();
    });
}

function showDetail(id) {
    $.get(`/admin/students/${id}`, function(res) {
        const s = res.data;
        const avatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(s.name)}&background=4f8ef7&color=fff&size=80`;
        $('#detailBody').html(`
            <div class="text-center py-3">
                <img src="${s.photo ? '/storage/'+s.photo : avatar}"
                     class="rounded-circle mb-3" width="80" height="80" style="object-fit:cover">
                <h6 class="fw-bold mb-0">${s.name}</h6>
                <span class="badge bg-primary small">${s.nis}</span>
            </div>
            <table class="table table-sm table-borderless">
                <tr><td class="text-muted small" width="40%">Cabang</td><td class="small fw-semibold">${s.branch?.name ?? '-'}</td></tr>
                <tr><td class="text-muted small">Kelas</td><td class="small fw-semibold">${s.grade ?? '-'}</td></tr>
                <tr><td class="text-muted small">Gender</td><td class="small fw-semibold">${s.gender === 'L' ? '👦 Laki-laki' : '👧 Perempuan'}</td></tr>
                <tr><td class="text-muted small">Tgl Lahir</td><td class="small fw-semibold">${s.birth_date ?? '-'}</td></tr>
                <tr><td class="text-muted small">Sekolah</td><td class="small fw-semibold">${s.school_name ?? '-'}</td></tr>
                <tr><td class="text-muted small">HP</td><td class="small fw-semibold">${s.phone ?? '-'}</td></tr>
                <tr><td class="text-muted small">Orang Tua</td><td class="small fw-semibold">${s.parent_name ?? '-'} (${s.parent_phone ?? '-'})</td></tr>
                <tr><td class="text-muted small">Alamat</td><td class="small fw-semibold">${s.address ?? '-'}</td></tr>
            </table>
        `);
        new bootstrap.Modal('#detailModal').show();
    });
}

function saveStudent() {
    const id  = $('#studentId').val();
    const url = id ? `/admin/students/${id}` : '{{ route("admin.students.store") }}';
    const fd  = new FormData($('#studentForm')[0]);
    if (id) fd.append('_method', 'PUT');

    $.ajax({
        url, method: 'POST', data: fd, processData: false, contentType: false,
        success(res) {
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('studentModal'))?.hide();
                Swal.fire({ icon: 'success', title: '✅ ' + res.message, timer: 2000, showConfirmButton: false })
                    .then(() => location.reload()); // reload halaman setelah simpan
            }
        },
        error(xhr) {
            const errors = xhr.responseJSON?.errors;
            const msg = errors ? Object.values(errors).flat().join('<br>') : (xhr.responseJSON?.message ?? 'Terjadi kesalahan');
            Swal.fire({ icon: 'error', title: 'Gagal Menyimpan', html: msg });
        }
    });
}

function deleteStudent(id) {
    Swal.fire({
        title: 'Hapus Siswa?', text: 'Data akan dihapus permanen!', icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#dc3545',
        confirmButtonText: '<i class="bi bi-trash me-1"></i>Ya, Hapus!', cancelButtonText: 'Batal'
    }).then(r => {
        if (r.isConfirmed) {
            $.post(`/admin/students/${id}`, {
                _method: 'DELETE',
                _token: $('meta[name=csrf-token]').attr('content')
            }, function(res) {
                if (res.success) {
                    Swal.fire({ icon: 'success', title: res.message, timer: 2000, showConfirmButton: false })
                        .then(() => location.reload());
                }
            });
        }
    });
}

$('#photoInput').on('change', function() {
    if (this.files[0]) {
        const reader = new FileReader();
        reader.onload = e => $('#photoPreview').attr('src', e.target.result);
        reader.readAsDataURL(this.files[0]);
    }
});
</script>
@endpush
