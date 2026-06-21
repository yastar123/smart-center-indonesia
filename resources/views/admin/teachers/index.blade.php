@extends('layouts.app')
@section('title', 'Data Guru')
@section('page-title', 'Data Guru')

@section('content')

{{-- HEADER BANNER --}}
<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                    <i class="bi bi-person-workspace"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Manajemen Data Guru</h5>
                    <span style="font-size:12px;opacity:.8">Kelola data pengajar dan tenaga pendidik di seluruh cabang</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <button onclick="window.location.href='{{ route('admin.teachers.create') }}'" class="btn fw-semibold px-4"
                style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                <i class="bi bi-plus-lg me-2"></i>Tambah Guru
            </button>
        </div>
    </div>
</div>

{{-- STAT CARDS (populated by AJAX) --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Guru</div>
                    <div class="stat-value text-success" id="statTotal">
                        <span class="placeholder col-4 bg-success-subtle" style="height:1.5rem;border-radius:6px"></span>
                    </div>
                    <div class="stat-growth text-muted"><i class="bi bi-person-badge me-1"></i>Semua cabang</div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Guru Aktif</div>
                    <div class="stat-value text-primary" id="statAktif">
                        <span class="placeholder col-4 bg-primary-subtle" style="height:1.5rem;border-radius:6px"></span>
                    </div>
                    <div class="stat-growth text-success"><i class="bi bi-check-circle me-1"></i>Bertugas</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white">
                    <i class="bi bi-person-check-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Laki-laki</div>
                    <div class="stat-value text-primary" id="statMale">–</div>
                    <div class="stat-growth text-muted"><i class="bi bi-gender-male me-1"></i>Guru putra</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white">
                    <i class="bi bi-person-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #ec4899">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Perempuan</div>
                    <div class="stat-value" style="color:#ec4899" id="statFemale">–</div>
                    <div class="stat-growth text-muted"><i class="bi bi-gender-female me-1"></i>Guru putri</div>
                </div>
                <div class="stat-icon bg-danger-soft" style="color:white">
                    <i class="bi bi-person-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MAIN TABLE CARD --}}
<div class="dashboard-card fade-up">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h6 class="fw-bold mb-1" style="color:var(--text-primary)">
                <i class="bi bi-person-badge-fill text-success me-2"></i>Daftar Guru
            </h6>
            <p class="text-muted mb-0" style="font-size:12px">Kelola data guru dan pengajar seluruh cabang</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                <i class="bi bi-printer me-1"></i><span class="d-none d-md-inline">Print</span>
            </button>
            <button class="btn btn-success btn-sm" onclick="window.location.href='{{ route('admin.teachers.create') }}'">
                <i class="bi bi-plus-lg me-1"></i>Tambah Guru
            </button>
        </div>
    </div>

    {{-- Filter --}}
    <div class="row g-2 mb-4">
        <div class="col-12 col-md-5">
            <div class="input-group input-group-sm">
                <span class="input-group-text" style="background:var(--input-bg);border:1.5px solid var(--card-border);border-right:none;border-radius:10px 0 0 10px">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" id="searchInput" class="form-control"
                       placeholder="Cari nama atau NIG..."
                       style="border-left:none;border-radius:0 10px 10px 0">
            </div>
        </div>
        <div class="col-6 col-md-3">
            <select id="filterStatus" class="form-select form-select-sm" onchange="loadTeachers()">
                <option value="">Semua Status</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>
        <div class="col-6 col-md-4">
            <select id="filterBranch" class="form-select form-select-sm" onchange="loadTeachers()">
                <option value="">Semua Cabang</option>
                <option value="pusat">Pusat</option>
                @foreach($branches as $branch)
                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th class="small text-muted fw-semibold py-3" style="width:46px">#</th>
                    <th class="small text-muted fw-semibold py-3">GURU</th>
                    <th class="small text-muted fw-semibold py-3">NIG</th>
                    <th class="small text-muted fw-semibold py-3">GENDER</th>
                    <th class="small text-muted fw-semibold py-3">CABANG</th>
                    <th class="small text-muted fw-semibold py-3">MATA PELAJARAN</th>
                    <th class="small text-muted fw-semibold py-3">PENDIDIKAN</th>
                    <th class="small text-muted fw-semibold py-3">TGL LAHIR</th>
                    <th class="small text-muted fw-semibold py-3">KONTAK</th>
                    <th class="small text-muted fw-semibold py-3">CV</th>
                    <th class="small text-muted fw-semibold py-3">STATUS</th>
                    <th class="small text-muted fw-semibold py-3 text-center" style="width:100px">AKSI</th>
                </tr>
            </thead>
            <tbody id="teacherBody">
                <tr>
                    <td colspan="12" class="text-center py-5">
                        <div class="spinner-border text-success mb-2" style="width:1.8rem;height:1.8rem"></div>
                        <div class="text-muted small">Memuat data guru...</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 pt-3 gap-2"
         style="border-top:1px solid var(--card-border)">
        <small class="text-muted" id="paginationInfo">Memuat...</small>
        <div id="paginationLinks"></div>
    </div>

</div>

{{-- ===== MODAL TAMBAH / EDIT ===== --}}
<div class="modal fade" id="teacherModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:#fff">
                <div>
                    <h6 class="modal-title fw-bold mb-0" id="modalTitle">
                        <i class="bi bi-person-plus me-2"></i>Tambah Guru Baru
                    </h6>
                    <div style="font-size:12px;opacity:.75;margin-top:3px">Isi data lengkap guru di bawah ini</div>
                </div>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <form id="teacherForm" enctype="multipart/form-data" novalidate>
                    @csrf
                    <input type="hidden" id="teacherId">

                    {{-- PHOTO --}}
                    <div class="text-center mb-4 pb-4" style="border-bottom:1px solid var(--card-border)">
                        <div class="position-relative d-inline-block">
                            <img id="photoPreview"
                                 src="https://ui-avatars.com/api/?name=Guru&background=68117e&color=fff&size=120"
                                 class="rounded-circle" width="100" height="100"
                                 style="object-fit:cover;border:3px solid #c84ddf;box-shadow:0 8px 24px rgba(200,77,223,.3)">
                            <label class="position-absolute bottom-0 end-0 d-flex align-items-center justify-content-center"
                                   style="width:32px;height:32px;background:#68117e;border-radius:50%;cursor:pointer;border:2.5px solid white">
                                <i class="bi bi-camera-fill text-white" style="font-size:13px"></i>
                                <input type="file" name="photo" id="photoInput" class="d-none" accept="image/*">
                            </label>
                        </div>
                        <div class="text-muted mt-2" style="font-size:12px">Klik ikon kamera untuk upload foto (opsional)</div>
                    </div>

                    {{-- DATA PRIBADI --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:4px;height:20px;background:linear-gradient(135deg,#68117e,#c84ddf);border-radius:4px"></div>
                            <span class="fw-bold text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.08em">Data Pribadi</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control form-control-sm" placeholder="Nama lengkap guru" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">NIG <span class="text-danger">*</span></label>
                                <input type="text" name="nig" id="nig" class="form-control form-control-sm" placeholder="Nomor Induk Guru" required>
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
                                <label class="form-label small fw-semibold">Tanggal Lahir</label>
                                <input type="date" name="birth_date" id="birth_date" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Pendidikan</label>
                                <select name="education" id="education" class="form-select form-select-sm">
                                    <option value="">Pilih...</option>
                                    <option value="D3">D3</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Alamat</label>
                                <textarea name="address" id="address" class="form-control form-control-sm" rows="2" placeholder="Alamat lengkap"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- DATA AKADEMIK --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:4px;height:20px;background:linear-gradient(#c84ddf,#68117e);border-radius:4px"></div>
                            <span class="fw-bold text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.08em">Data Mengajar</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Cabang <span class="text-danger">*</span></label>
                                <select name="branch_id" id="branch_id" class="form-select form-select-sm" required>
                                    <option value="">Pilih Cabang</option>
                                    <option value="pusat">Pusat</option>
                                    @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">No. HP</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">+62</span>
                                    <input type="text" name="phone" id="phone" class="form-control" placeholder="8xxxxxxxxxx">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Upload CV</label>
                                <input type="file" name="cv" id="cv" class="form-control form-control-sm" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                <div class="text-muted mt-1" style="font-size:11px">PDF/DOC/DOCX maksimal 5 MB. <span id="cvCurrent"></span></div>
                            </div>
                        </div>
                    </div>

                    {{-- JENIS GURU --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:4px;height:20px;background:linear-gradient(#f6af23,#e09000);border-radius:4px"></div>
                            <span class="fw-bold text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.08em">Jenis Guru</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Jenis Guru <span class="text-danger">*</span></label>
                                <select name="jenis_guru" id="jenis_guru" class="form-select form-select-sm" required>
                                    <option value="">Pilih...</option>
                                    <option value="kontrak">Kontrak</option>
                                    <option value="freelance">Freelance</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- AKUN LOGIN --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:4px;height:20px;background:linear-gradient(#10b981,#68117e);border-radius:4px"></div>
                            <span class="fw-bold text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.08em">Akun Login Guru</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Email Akun <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control form-control-sm" placeholder="guru@domain.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Password <span class="text-danger" id="passwordRequiredMark">*</span></label>
                                <input type="password" name="password" id="password" class="form-control form-control-sm" placeholder="Min. 8 karakter" minlength="8" required>
                                <div class="text-muted mt-1" id="passwordHelp" style="font-size:11px">Dipakai guru untuk login ke portal guru.</div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer border-0 p-4" style="background:var(--input-bg)">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius:10px">
                    <i class="bi bi-x me-1"></i>Batal
                </button>
                <button type="button" class="btn btn-success px-5 fw-semibold" id="saveBtn" onclick="saveTeacher()" style="border-radius:10px">
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

// ---- LOAD TEACHERS (AJAX) ----
function loadTeachers(page) {
    page = page || 1;
    currentPage = page;

    document.getElementById('teacherBody').innerHTML = `
        <tr><td colspan="12" class="text-center py-5">
            <div class="spinner-border text-success mb-2" style="width:1.8rem;height:1.8rem"></div>
            <div class="text-muted small">Memuat data...</div>
        </td></tr>`;

    $.ajax({
        url: '{{ route("admin.teachers.index") }}',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        data: {
            search:    document.getElementById('searchInput').value,
            status:    document.getElementById('filterStatus').value,
            branch_id: document.getElementById('filterBranch').value,
            page
        },
        success(res) {
            // Update global stats
            if (res.stats) {
                countUpValue(document.getElementById('statTotal'),  res.stats.total);
                countUpValue(document.getElementById('statAktif'),  res.stats.aktif);
                countUpValue(document.getElementById('statMale'),   res.stats.male);
                countUpValue(document.getElementById('statFemale'), res.stats.female);
            }

            let html = '';
            const teachers = res.data || [];

            if (!teachers.length) {
                html = `<tr><td colspan="12" class="py-5">
                    <div class="text-center">
                        <div style="width:72px;height:72px;border-radius:50%;background:var(--input-bg);display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
                            <i class="bi bi-person-badge" style="font-size:2rem;opacity:.35"></i>
                        </div>
                        <p class="text-muted mb-3">Belum ada data guru${document.getElementById('searchInput').value ? ' yang cocok' : ''}</p>
                        <button class="btn btn-sm btn-success" onclick="window.location.href='{{ route('admin.teachers.create') }}'">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Guru
                        </button>
                    </div>
                </td></tr>`;
            } else {
                teachers.forEach((t, i) => {
                    const badgeBg  = t.status === 'aktif' ? '#dcfce7' : '#f3f4f6';
                    const badgeCol = t.status === 'aktif' ? '#15803d' : '#6b7280';
                    const badgeLbl = t.status === 'aktif' ? 'Aktif' : 'Nonaktif';
                    const avatar   = `https://ui-avatars.com/api/?name=${encodeURIComponent(t.name)}&background=${t.gender==='P'?'ec4899':'68117e'}&color=fff&size=80`;
                    const num      = (res.from || ((page - 1) * 10 + 1)) + i;
                    const courseNames = (t.courses || []).map(c => c.nama).join(', ') || t.subjects || '–';
                    const birthDate = t.birth_date ? new Date(t.birth_date).toLocaleDateString('id-ID') : '–';
                    html += `<tr>
                        <td class="text-muted small fw-semibold">${num}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="${t.photo ? '/storage/'+t.photo : avatar}"
                                     class="rounded-circle flex-shrink-0" width="40" height="40"
                                     style="object-fit:cover;border:2.5px solid ${t.gender==='P'?'#f9a8d4':'#e9d5ff'}" loading="lazy">
                                <div>
                                    <div class="fw-semibold" style="font-size:13.5px">${t.name}</div>
                                    <div class="text-muted" style="font-size:11px;max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${t.address ?? 'Alamat belum diisi'}</div>
                                </div>
                            </div>
                        </td>
                        <td><code style="background:var(--input-bg);padding:3px 8px;border-radius:6px;font-size:12px;color:var(--bs-primary)">${t.nig}</code></td>
                        <td><span class="text-muted" style="font-size:12px">${t.gender === 'L' ? 'Laki-laki' : 'Perempuan'}</span></td>
                        <td>
                            <span style="background:var(--input-bg);color:var(--text-muted);border:1px solid var(--card-border);padding:3px 10px;border-radius:6px;font-size:11px;white-space:nowrap">
                                <i class="bi bi-building me-1"></i>${t.branch?.name ?? 'Pusat'}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted" style="font-size:12.5px;max-width:220px;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                ${courseNames}
                            </span>
                        </td>
                        <td><span class="text-muted" style="font-size:12px">${t.education ?? '–'}</span></td>
                        <td><span class="text-muted" style="font-size:12px;white-space:nowrap">${birthDate}</span></td>
                        <td>
                            <div style="font-size:13px;white-space:nowrap">${t.phone ?? '–'}</div>
                            <div class="text-muted" style="font-size:11px;white-space:nowrap">${t.email ?? t.user?.email ?? ''}</div>
                        </td>
                        <td>${t.cv_path ? `<a href="/storage/${t.cv_path}" target="_blank" class="btn btn-sm btn-outline-info" style="border-radius:8px"><i class="bi bi-file-earmark-text"></i></a>` : '<span class="text-muted">–</span>'}</td>
                        <td>
                            <span style="background:${badgeBg};color:${badgeCol};padding:4px 10px;border-radius:8px;font-size:11px;font-weight:600">
                                <i class="bi bi-circle-fill me-1" style="font-size:7px"></i>${badgeLbl}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <button class="btn btn-sm btn-outline-warning" onclick="window.location.href='/admin/teachers/${t.id}/edit'"
                                        title="Edit" style="border-radius:8px;padding:5px 8px"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteTeacher(${t.id}, '${t.name.replace(/'/g, "\\\'")}')"
                                        title="Hapus" style="border-radius:8px;padding:5px 8px"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>`;
                });
            }

            document.getElementById('teacherBody').innerHTML = html;

            // Pagination info
            const from = res.from || 0, to = res.to || 0, total = res.total || 0;
            document.getElementById('paginationInfo').innerHTML =
                total ? `Menampilkan <strong>${from}</strong>–<strong>${to}</strong> dari <strong>${total}</strong> guru` : 'Tidak ada data';

            // Pagination links (simple prev/next)
            let links = '';
            if (res.current_page > 1)
                links += `<button class="btn btn-sm btn-outline-secondary me-1" style="border-radius:8px;display:inline-flex;align-items:center;justify-content:center" onclick="loadTeachers(${res.current_page-1})">‹</button>`;
            if (res.last_page > 1) {
                for (let p = Math.max(1,res.current_page-2); p <= Math.min(res.last_page,res.current_page+2); p++) {
                    links += `<button class="btn btn-sm me-1 ${p===res.current_page?'btn-primary':'btn-outline-secondary'}" style="border-radius:8px;min-width:36px" onclick="loadTeachers(${p})">${p}</button>`;
                }
            }
            if (res.current_page < res.last_page)
                links += `<button class="btn btn-sm btn-outline-secondary" style="border-radius:8px;display:inline-flex;align-items:center;justify-content:center" onclick="loadTeachers(${res.current_page+1})">›</button>`;
            document.getElementById('paginationLinks').innerHTML = links;
        },
        error() {
            document.getElementById('teacherBody').innerHTML = `
                <tr><td colspan="12" class="text-center py-5 text-danger">
                    <i class="bi bi-exclamation-triangle d-block mb-2" style="font-size:2rem"></i>
                    Gagal memuat data. <a href="javascript:loadTeachers()">Coba lagi</a>
                </td></tr>`;
        }
    });
}

// ---- OPEN CREATE PAGE (ADD) ----
function openModal() {
    window.location.href = '{{ route("admin.teachers.create") }}';
}

// ---- EDIT ----
function editTeacher(id) {
    $.get('/admin/teachers/' + id, function(res) {
        const t = res.data;
        document.getElementById('modalTitle').innerHTML = '<i class="bi bi-pencil me-2"></i>Edit Data Guru';
        document.getElementById('teacherId').value   = t.id;
        document.getElementById('name').value        = t.name        ?? '';
        document.getElementById('nig').value         = t.nig         ?? '';
        document.getElementById('gender').value      = t.gender      ?? '';
        document.getElementById('birth_date').value  = t.birth_date  ?? '';
        document.getElementById('branch_id').value   = t.branch_id   ?? 'pusat';
        document.getElementById('email').value       = t.email ?? t.user?.email ?? '';
        document.getElementById('phone').value       = t.phone       ?? '';
        document.getElementById('password').value    = '';
        document.getElementById('password').required = false;
        document.getElementById('passwordRequiredMark').classList.add('d-none');
        document.getElementById('passwordHelp').textContent = 'Kosongkan jika tidak ingin mengubah password akun guru.';
        document.getElementById('cvCurrent').innerHTML = t.cv_path
            ? `<a href="/storage/${t.cv_path}" target="_blank" class="text-decoration-none">Lihat CV saat ini</a>`
            : '';
        document.getElementById('education').value   = t.education   ?? '';
        document.getElementById('address').value     = t.address     ?? '';
        document.getElementById('jenis_guru').value  = t.jenis_guru  ?? '';
        const avatar = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(t.name) + '&background=68117e&color=fff&size=120';
        document.getElementById('photoPreview').src = t.photo ? '/storage/' + t.photo : avatar;
        new bootstrap.Modal('#teacherModal').show();
    }).fail(function() {
        showToast('Tidak dapat memuat data guru.', 'error');
    });
}

// ---- SAVE ----
function saveTeacher() {
    const form = document.getElementById('teacherForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    const id  = document.getElementById('teacherId').value;
    const url = id ? '/admin/teachers/' + id : '{{ route("admin.teachers.store") }}';
    const fd  = new FormData(form);
    if (id) fd.append('_method', 'PUT');

    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

    $.ajax({
        url, method:'POST', data:fd, processData:false, contentType:false,
        success(res) {
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('teacherModal'))?.hide();
                showToast(res.message, 'success');
                loadTeachers(currentPage);
            }
        },
        error(xhr) {
            const errors = xhr.responseJSON?.errors;
            const msg = errors
                ? Object.values(errors).flat().join('; ')
                : (xhr.responseJSON?.message ?? 'Terjadi kesalahan.');
            showToast(msg, 'error');
        },
        complete() {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Simpan Data';
        }
    });
}

// ---- DELETE ----
function deleteTeacher(id, name) {
    confirmAction(`Hapus guru "${name}"? Data tidak dapat dikembalikan.`, function() {
        $.post('/admin/teachers/' + id, {
            _method: 'DELETE',
            _token: document.querySelector('meta[name=csrf-token]').content
        }, function(res) {
            showToast(res.message, 'success');
            loadTeachers(currentPage);
        }).fail(function() {
            showToast('Tidak dapat menghapus data guru.', 'error');
        });
    }, null, {title:'Hapus Guru', okText:'Ya, Hapus'});
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
let searchTimer;
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => loadTeachers(), 400);
});

// ---- INIT ----
document.addEventListener('DOMContentLoaded', () => loadTeachers());
</script>
@endpush
