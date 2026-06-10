@extends('layouts.app')
@section('title', 'Data Siswa')
@section('page-title', 'Data Siswa')

@section('content')

{{-- HEADER BANNER --}}
<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Manajemen Data Siswa</h5>
                    <span style="font-size:12px;opacity:.8">Kelola data seluruh siswa di semua cabang Smart Center Indonesia</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <button onclick="openModal()" class="btn fw-semibold px-4"
                style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                <i class="bi bi-plus-lg me-2"></i>Tambah Siswa
            </button>
        </div>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
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
        <div class="stat-card" style="border-top:3px solid #10b981">
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
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Laki-laki</div>
                    <div class="stat-value text-primary">{{ $stats['male'] }}</div>
                    <div class="stat-growth text-muted">
                        <i class="bi bi-gender-male me-1"></i>Siswa putra
                    </div>
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
                    <div class="stat-value" style="color:#ec4899">{{ $stats['female'] }}</div>
                    <div class="stat-growth text-muted">
                        <i class="bi bi-gender-female me-1"></i>Siswa putri
                    </div>
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
                    <option value="pusat" {{ request('branch_id')==='pusat' ? 'selected':'' }}>Pusat</option>
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
        <span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);border:1px solid var(--soft-primary-border);font-size:12px;padding:6px 12px;border-radius:8px">
            <i class="bi bi-search me-1"></i>{{ request('search') }}
        </span>
        @endif
        @if(request('status'))
        <span class="badge" style="background:var(--soft-success-bg);color:var(--soft-success-text);border:1px solid var(--soft-success-border);font-size:12px;padding:6px 12px;border-radius:8px">
            <i class="bi bi-circle-fill me-1" style="font-size:8px"></i>{{ ucfirst(request('status')) }}
        </span>
        @endif
        @if(request('gender'))
        <span class="badge" style="background:var(--soft-danger-bg);color:var(--soft-danger-text);border:1px solid var(--soft-danger-border);font-size:12px;padding:6px 12px;border-radius:8px">
            <i class="bi bi-person me-1"></i>{{ request('gender')==='L' ? 'Laki-laki':'Perempuan' }}
        </span>
        @endif
        <small class="text-muted align-self-center">{{ $students->total() }} hasil ditemukan</small>
    </div>
    @endif

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="thead-modern">
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
                    $avatar = 'https://ui-avatars.com/api/?name='.urlencode($s->name).'&background='.($s->gender==='P'?'ec4899':'68117e').'&color=fff&size=80';
                    $statusMap = [
                        'aktif'    => ['bg'=>'var(--soft-success-bg)','color'=>'var(--soft-success-text)','label'=>'Aktif'],
                        'nonaktif' => ['bg'=>'var(--soft-muted-bg)','color'=>'var(--soft-muted-text)','label'=>'Nonaktif'],
                        'lulus'    => ['bg'=>'var(--soft-primary-bg)','color'=>'var(--soft-primary-text)','label'=>'Lulus'],
                    ];
                    $badge = $statusMap[$s->status] ?? ['bg'=>'var(--soft-muted-bg)','color'=>'var(--soft-muted-text)','label'=>ucfirst($s->status??'-')];
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
                                     style="object-fit:cover;border:2.5px solid {{ $s->gender==='P' ? '#f9a8d4':'#e8b4f5' }}"
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
                            <i class="bi bi-building me-1"></i>{{ $s->branch->name ?? 'Pusat' }}
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

            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#68117e,#c84ddf);color:#fff">
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
                                 src="https://ui-avatars.com/api/?name=Siswa&background=68117e&color=fff&size=120"
                                 class="rounded-circle"
                                 width="100" height="100"
                                 style="object-fit:cover;border:3px solid #c84ddf;box-shadow:0 8px 24px rgba(200,77,223,.3)">
                            <label class="position-absolute bottom-0 end-0 d-flex align-items-center justify-content-center"
                                   style="width:32px;height:32px;background:#c84ddf;border-radius:50%;cursor:pointer;border:2.5px solid white;box-shadow:0 2px 8px rgba(0,0,0,.2)">
                                <i class="bi bi-camera-fill text-white" style="font-size:13px"></i>
                                <input type="file" name="photo" id="photoInput" class="d-none" accept="image/*">
                            </label>
                        </div>
                        <div class="text-muted mt-2" style="font-size:12px">Klik ikon kamera untuk upload foto (opsional)</div>
                    </div>

                    {{-- SECTION: DATA PRIBADI --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:4px;height:20px;background:linear-gradient(#c84ddf,#c84ddf);border-radius:4px"></div>
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
                            <div style="width:4px;height:20px;background:linear-gradient(135deg,#68117e,#c84ddf);border-radius:4px"></div>
                            <span class="fw-bold text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.08em">Data Akademik</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Cabang <span class="text-danger">*</span></label>
                                    <select name="branch_id" id="branch_id" class="form-select form-select-sm" required>
                                        <option value="">Pilih Cabang</option>
                                        <option value="pusat">Pusat</option>
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
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Guru Pengajar</label>
                                <div class="p-2 rounded-3" style="background:var(--input-bg);border:1.5px solid var(--card-border);max-height:190px;overflow:auto">
                                    @foreach($teachers as $teacher)
                                        @if($teacher->courses && $teacher->courses->isNotEmpty())
                                            @foreach($teacher->courses as $course)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input student-teacher-course-checkbox" type="checkbox" name="teacher_pairs[]" value="{{ $teacher->id }}:{{ $course->id }}" id="tp{{ $teacher->id }}_{{ $course->id }}">
                                                <label class="form-check-label small" for="tp{{ $teacher->id }}_{{ $course->id }}">
                                                    <strong>{{ $teacher->name }}</strong> - {{ $course->nama }}
                                                </label>
                                            </div>
                                            @endforeach
                                        @else
                                            <div class="form-check mb-2">
                                                <input class="form-check-input student-teacher-course-checkbox" type="checkbox" name="teacher_pairs[]" value="{{ $teacher->id }}:0" id="tp{{ $teacher->id }}_0">
                                                <label class="form-check-label small" for="tp{{ $teacher->id }}_0">
                                                    <strong>{{ $teacher->name }}</strong> - <span class="text-muted">Belum ada mata pelajaran</span>
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="text-muted mt-1" style="font-size:11px">Bisa pilih lebih dari satu guru untuk siswa.</div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION: AKUN LOGIN --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:4px;height:20px;background:linear-gradient(#10b981,#68117e);border-radius:4px"></div>
                            <span class="fw-bold text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.08em">Akun Login Siswa</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Email Akun <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control form-control-sm" placeholder="siswa@domain.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Password <span class="text-danger" id="passwordRequiredMark">*</span></label>
                                <input type="password" name="password" id="password" class="form-control form-control-sm" placeholder="Min. 8 karakter" minlength="8" required>
                                <div class="text-muted mt-1" id="passwordHelp" style="font-size:11px">Dipakai siswa untuk login ke portal siswa.</div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION: DATA ORANG TUA --}}
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:4px;height:20px;background:linear-gradient(#f6af23,#e09000);border-radius:4px"></div>
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
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#68117e,#c84ddf);color:#fff">
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
    document.getElementById('photoPreview').src = 'https://ui-avatars.com/api/?name=Siswa&background=68117e&color=fff&size=120';
    document.getElementById('password').required = true;
    document.getElementById('passwordRequiredMark').classList.remove('d-none');
    document.getElementById('passwordHelp').textContent = 'Dipakai siswa untuk login ke portal siswa.';
    document.querySelectorAll('.student-teacher-course-checkbox').forEach(cb => cb.checked = false);
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
        document.getElementById('branch_id').value   = s.branch_id   ?? 'pusat';
        document.getElementById('school_name').value = s.school_name ?? '';
        document.getElementById('grade').value       = s.grade       ?? '';
        document.getElementById('phone').value       = s.phone       ?? '';
        document.getElementById('email').value       = s.user?.email ?? '';
        document.getElementById('password').value    = '';
        document.getElementById('password').required = false;
        document.getElementById('passwordRequiredMark').classList.add('d-none');
        document.getElementById('passwordHelp').textContent = 'Kosongkan jika tidak ingin mengubah password akun siswa.';
        const teacherIds = (s.teachers || []).map(t => String(t.id));
        document.querySelectorAll('.student-teacher-course-checkbox').forEach(cb => {
            const t = cb.value.split(':')[0];
            cb.checked = teacherIds.includes(t);
        });
        document.getElementById('parent_name').value  = s.parent_name  ?? '';
        document.getElementById('parent_phone').value = s.parent_phone ?? '';
        document.getElementById('address').value     = s.address     ?? '';
        const avatar = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(s.name) + '&background=68117e&color=fff&size=120';
        document.getElementById('photoPreview').src = s.photo ? '/storage/' + s.photo : avatar;
        new bootstrap.Modal('#studentModal').show();
    }).fail(function() {
        showToast('Tidak dapat memuat data siswa.', 'error');
    });
}

// ---- DETAIL ----
function showDetail(id) {
    document.getElementById('detailBody').innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';
    new bootstrap.Modal('#detailModal').show();
    $.get('/admin/students/' + id, function(res) {
        const s = res.data;
        const avatar = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(s.name) + '&background=' + (s.gender==='P'?'ec4899':'68117e') + '&color=fff&size=120';
        const statusMap = {aktif:'var(--soft-success-bg):var(--soft-success-text):Aktif', nonaktif:'var(--soft-muted-bg):var(--soft-muted-text):Nonaktif', lulus:'var(--soft-primary-bg):var(--soft-primary-text):Lulus'};
        const [sbg,scol,slbl] = (statusMap[s.status]||'var(--soft-muted-bg):var(--soft-muted-text):'+s.status).split(':');
        document.getElementById('detailBody').innerHTML = `
            <div style="text-align:center;padding:24px 24px 16px">
                <img src="${s.photo ? '/storage/'+s.photo : avatar}" class="rounded-circle mb-3"
                     width="90" height="90" style="object-fit:cover;border:3px solid #c84ddf;box-shadow:0 8px 24px rgba(200,77,223,.25)">
                <h6 class="fw-bold mb-1" style="font-size:16px">${s.name}</h6>
                <code style="background:var(--soft-primary-bg);color:var(--soft-primary-text);padding:3px 10px;border-radius:6px;font-size:12px">${s.nis}</code>
                <div style="margin-top:10px">
                    <span style="background:${sbg};color:${scol};padding:4px 12px;border-radius:8px;font-size:12px;font-weight:600">
                        ● ${slbl}
                    </span>
                </div>
            </div>
            <div style="padding:0 24px 24px">
                <table style="width:100%;border-collapse:collapse">
                    ${row('Cabang', s.branch?.name ?? 'Pusat')}
                    ${row('Kelas', s.grade ?? '–')}
                    ${row('Gender', s.gender==='L'?'👦 Laki-laki':'👧 Perempuan')}
                    ${row('Tgl Lahir', s.birth_date ?? '–')}
                    ${row('Tempat Lahir', s.birth_place ?? '–')}
                    ${row('Sekolah', s.school_name ?? '–')}
                    ${row('No. HP', s.phone ?? '–')}
                    ${row('Orang Tua', (s.parent_name??'–') + (s.parent_phone?' · '+s.parent_phone:''))}
                    ${row('Guru', (s.teachers || []).map(t => t.name + (t.courses?.length ? ' (' + t.courses.map(c => c.nama).join(', ') + ')' : '')).join('<br>') || '–')}
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
    return `<tr style="border-bottom:1px solid var(--card-border)">
        <td style="padding:9px 4px 9px 0;color:var(--text-muted);font-size:12.5px;width:38%">${label}</td>
        <td style="padding:9px 0;font-size:13px;font-weight:600;color:var(--text-primary)">${val}</td>
    </tr>`;
}

// ---- SAVE ----
function saveStudent() {
    const form = document.getElementById('studentForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const id  = document.getElementById('studentId').value;
    const url = id ? '/admin/students/' + id : '{{ route("admin.students.store") }}';
    const fd  = new FormData(form);
    // Collect teacher_pairs and dedupe into teacher_ids for backend compatibility
    const checkedPairs = Array.from(document.querySelectorAll('.student-teacher-course-checkbox:checked')).map(cb => cb.value);
    // append pairs
    checkedPairs.forEach(p => fd.append('teacher_pairs[]', p));
    // derive unique teacher ids
    const teacherIds = [...new Set(checkedPairs.map(p => p.split(':')[0]))];
    teacherIds.forEach(tid => fd.append('teacher_ids[]', tid));
    if (id) fd.append('_method', 'PUT');

    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

    $.ajax({
        url, method: 'POST', data: fd, processData: false, contentType: false,
        success(res) {
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('studentModal'))?.hide();
                showToast(res.message, 'success');
                setTimeout(() => location.reload(), 1200);
            }
        },
        error(xhr) {
            const errors = xhr.responseJSON?.errors;
            const msg = errors
                ? Object.values(errors).flat().join('; ')
                : (xhr.responseJSON?.message ?? 'Terjadi kesalahan. Coba lagi.');
            showToast(msg, 'error');
        },
        complete() {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Simpan Data';
        }
    });
}

// ---- DELETE ----
function deleteStudent(id, name) {
    confirmAction(`Hapus siswa "${name}"? Data tidak dapat dikembalikan.`, function() {
        $.post('/admin/students/' + id, {
            _method: 'DELETE',
            _token: document.querySelector('meta[name=csrf-token]').content
        }, function(res) {
            if (res.success) {
                showToast(res.message, 'success');
                setTimeout(() => location.reload(), 1200);
            }
        }).fail(function() {
            showToast('Tidak dapat menghapus data siswa.', 'error');
        });
    }, null, {title:'Hapus Siswa', okText:'Ya, Hapus'});
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
