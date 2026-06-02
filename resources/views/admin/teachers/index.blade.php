@extends('layouts.app')
@section('title', 'Data Guru')
@section('page-title', 'Data Guru')

@section('content')

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-icon bg-success bg-opacity-10 text-success mx-auto mb-2">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div class="stat-value text-success" id="totalGuru">-</div>
            <div class="stat-label">Total Guru</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary mx-auto mb-2">
                <i class="bi bi-person-check-fill"></i>
            </div>
            <div class="stat-value text-primary" id="totalAktif">-</div>
            <div class="stat-label">Guru Aktif</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning mx-auto mb-2">
                <i class="bi bi-gender-male"></i>
            </div>
            <div class="stat-value text-warning" id="totalL">-</div>
            <div class="stat-label">Laki-laki</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-icon bg-danger bg-opacity-10 text-danger mx-auto mb-2">
                <i class="bi bi-gender-female"></i>
            </div>
            <div class="stat-value text-danger" id="totalP">-</div>
            <div class="stat-label">Perempuan</div>
        </div>
    </div>
</div>

<div class="stat-card">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h6 class="fw-bold mb-0"><i class="bi bi-person-badge text-success me-2"></i>Daftar Guru</h6>
        <button class="btn btn-success btn-sm px-3" onclick="openModal()">
            <i class="bi bi-plus-lg me-1"></i>Tambah Guru
        </button>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-md-5">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari nama atau NIG...">
            </div>
        </div>
        <div class="col-md-3">
            <select id="filterStatus" class="form-select form-select-sm" onchange="loadTeachers()">
                <option value="">Semua Status</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>
        <div class="col-md-4">
            <select id="filterBranch" class="form-select form-select-sm" onchange="loadTeachers()">
                <option value="">Semua Cabang</option>
                @foreach($branches as $branch)
                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead style="background:#f8fafc">
                <tr>
                    <th class="text-muted small fw-semibold" style="width:45px">#</th>
                    <th class="text-muted small fw-semibold">GURU</th>
                    <th class="text-muted small fw-semibold">NIG</th>
                    <th class="text-muted small fw-semibold">CABANG</th>
                    <th class="text-muted small fw-semibold">MATA PELAJARAN</th>
                    <th class="text-muted small fw-semibold">KONTAK</th>
                    <th class="text-muted small fw-semibold">STATUS</th>
                    <th class="text-muted small fw-semibold text-center" style="width:100px">AKSI</th>
                </tr>
            </thead>
            <tbody id="teacherBody">
                <tr><td colspan="8" class="text-center py-5">
                    <div class="spinner-border text-success"></div>
                    <div class="text-muted small mt-2">Memuat data...</div>
                </td></tr>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
        <small class="text-muted" id="paginationInfo"></small>
        <div id="paginationLinks"></div>
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="teacherModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0" style="background:linear-gradient(135deg,#28a745,#20c997);color:#fff">
                <h6 class="modal-title fw-bold" id="modalTitle">
                    <i class="bi bi-person-plus me-2"></i>Tambah Guru
                </h6>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="teacherForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="teacherId">

                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img id="photoPreview"
                                 src="https://ui-avatars.com/api/?name=G&background=28a745&color=fff&size=100"
                                 class="rounded-circle border border-3 border-success"
                                 width="90" height="90" style="object-fit:cover">
                            <label class="position-absolute bottom-0 end-0 btn btn-success btn-sm rounded-circle p-1"
                                   style="width:28px;height:28px;cursor:pointer">
                                <i class="bi bi-camera" style="font-size:.75rem"></i>
                                <input type="file" name="photo" id="photoInput" class="d-none" accept="image/*">
                            </label>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">NIG <span class="text-danger">*</span></label>
                            <input type="text" name="nig" id="nig" class="form-control form-control-sm" required>
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
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" name="email" id="email" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">No. HP</label>
                            <input type="text" name="phone" id="phone" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Pendidikan</label>
                            <select name="education" id="education" class="form-select form-select-sm">
                                <option value="">Pilih</option>
                                <option value="S1">S1</option>
                                <option value="S2">S2</option>
                                <option value="S3">S3</option>
                                <option value="D3">D3</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Mata Pelajaran</label>
                            <input type="text" name="subjects" id="subjects" class="form-control form-control-sm" placeholder="Matematika, Fisika, dll">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Alamat</label>
                            <textarea name="address" id="address" class="form-control form-control-sm" rows="2"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 bg-light rounded-bottom">
                <button type="button" class="btn btn-sm btn-light px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-success px-4" onclick="saveTeacher()">
                    <i class="bi bi-check-lg me-1"></i>Simpan Data
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentPage = 1;

function loadTeachers(page = 1) {
    currentPage = page;
    $('#teacherBody').html(`<tr><td colspan="8" class="text-center py-5">
        <div class="spinner-border text-success"></div>
        <div class="text-muted small mt-2">Memuat data...</div>
    </td></tr>`);

    $.get('{{ route("admin.teachers.index") }}', {
        search: $('#searchInput').val(),
        status: $('#filterStatus').val(),
        branch_id: $('#filterBranch').val(),
        page
    }, function(res) {
        $('#totalGuru').text(res.total ?? 0);
        $('#totalAktif').text(res.data?.filter(t => t.status === 'aktif').length ?? 0);
        $('#totalL').text(res.data?.filter(t => t.gender === 'L').length ?? 0);
        $('#totalP').text(res.data?.filter(t => t.gender === 'P').length ?? 0);

        let html = '';
        if (!res.data || res.data.length === 0) {
            html = `<tr><td colspan="8" class="text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size:3rem"></i>
                <div class="text-muted mt-2">Belum ada data guru</div>
            </td></tr>`;
        } else {
            res.data.forEach((t, i) => {
                const badge = t.status === 'aktif'
                    ? '<span class="badge rounded-pill" style="background:#dcfce7;color:#15803d">● Aktif</span>'
                    : '<span class="badge rounded-pill" style="background:#f3f4f6;color:#6b7280">● Nonaktif</span>';
                const avatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(t.name)}&background=28a745&color=fff&size=40`;
                html += `<tr>
                    <td class="text-muted small">${res.from + i}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="${t.photo ? '/storage/'+t.photo : avatar}"
                                 class="rounded-circle" width="38" height="38" style="object-fit:cover;border:2px solid #e5e7eb">
                            <div>
                                <div class="fw-semibold small">${t.name}</div>
                                <div class="text-muted" style="font-size:.72rem">${t.education ?? '-'}</div>
                            </div>
                        </div>
                    </td>
                    <td><code class="small">${t.nig}</code></td>
                    <td><span class="badge bg-light text-dark small">${t.branch?.name ?? '-'}</span></td>
                    <td><span class="small text-truncate d-block" style="max-width:150px">${t.subjects ?? '-'}</span></td>
                    <td>
                        <div class="small">${t.phone ?? '-'}</div>
                        <div class="text-muted" style="font-size:.72rem">${t.email ?? ''}</div>
                    </td>
                    <td>${badge}</td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-warning" onclick="editTeacher(${t.id})" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-outline-danger" onclick="deleteTeacher(${t.id})" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>`;
            });
        }
        $('#teacherBody').html(html);
        $('#paginationInfo').text(`Menampilkan ${res.from ?? 0}-${res.to ?? 0} dari ${res.total ?? 0} guru`);
    });
}

function openModal() {
    $('#modalTitle').html('<i class="bi bi-person-plus me-2"></i>Tambah Guru');
    $('#teacherForm')[0].reset();
    $('#teacherId').val('');
    $('#photoPreview').attr('src', 'https://ui-avatars.com/api/?name=G&background=28a745&color=fff&size=100');
    new bootstrap.Modal('#teacherModal').show();
}

function editTeacher(id) {
    $.get(`/admin/teachers/${id}`, function(res) {
        const t = res.data;
        $('#modalTitle').html('<i class="bi bi-pencil me-2"></i>Edit Guru');
        $('#teacherId').val(t.id);
        $('#name').val(t.name); $('#nig').val(t.nig);
        $('#gender').val(t.gender); $('#birth_date').val(t.birth_date);
        $('#branch_id').val(t.branch_id); $('#email').val(t.email);
        $('#phone').val(t.phone); $('#education').val(t.education);
        $('#subjects').val(t.subjects); $('#address').val(t.address);
        const avatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(t.name)}&background=28a745&color=fff&size=100`;
        $('#photoPreview').attr('src', t.photo ? '/storage/'+t.photo : avatar);
        new bootstrap.Modal('#teacherModal').show();
    });
}

function saveTeacher() {
    const id = $('#teacherId').val();
    const url = id ? `/admin/teachers/${id}` : '{{ route("admin.teachers.store") }}';
    const fd = new FormData($('#teacherForm')[0]);
    if (id) fd.append('_method', 'PUT');
    $.ajax({
        url, method: 'POST', data: fd, processData: false, contentType: false,
        success(res) {
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('teacherModal'))?.hide();
                Swal.fire({ icon: 'success', title: res.message, timer: 2000, showConfirmButton: false });
                loadTeachers(currentPage);
            }
        },
        error(xhr) {
            const errors = xhr.responseJSON?.errors;
            Swal.fire({ icon: 'error', title: 'Gagal!', html: errors ? Object.values(errors).flat().join('<br>') : 'Terjadi kesalahan' });
        }
    });
}

function deleteTeacher(id) {
    Swal.fire({
        title: 'Hapus Guru?', icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#dc3545',
        confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
    }).then(r => {
        if (r.isConfirmed) {
            $.post(`/admin/teachers/${id}`, { _method: 'DELETE', _token: $('meta[name=csrf-token]').attr('content') }, function(res) {
                Swal.fire({ icon: 'success', title: res.message, timer: 2000, showConfirmButton: false });
                loadTeachers(currentPage);
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

let timer;
$('#searchInput').on('input', () => { clearTimeout(timer); timer = setTimeout(loadTeachers, 400); });
$(document).ready(() => loadTeachers());
</script>
@endpush