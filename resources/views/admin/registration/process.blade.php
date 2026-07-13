@extends('layouts.app')
@section('title', 'Proses Pendaftaran')
@section('page-title', 'Proses Pendaftaran Siswa')

@section('content')
<div class="fade-up">

{{-- PAGE HEADER --}}
<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-40px;top:-40px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div>
            <div style="font-size:11px;opacity:.6;text-transform:uppercase;letter-spacing:.08em;margin-bottom:5px">Manajemen Pendaftaran</div>
            <h4 class="fw-bold mb-1" style="color:white;font-size:clamp(16px,2vw,22px)">Proses Pendaftaran &mdash; {{ $registration->name }}</h4>
            <p style="opacity:.72;margin:0;font-size:13px">Atur mata pelajaran, guru, paket kelas &amp; pembayaran sebelum akun siswa dibuat dan dikirim ke WhatsApp.</p>
        </div>
        <a href="{{ route('admin.registration-list.index') }}" class="btn fw-semibold px-4"
           style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.3);border-radius:12px;backdrop-filter:blur(10px);white-space:nowrap">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<style>
    .pw-stepper { display:flex; gap:.5rem; padding:1rem; background:var(--card-bg); border:1px solid var(--card-border); border-radius:14px 14px 0 0; overflow-x:auto; }
    .pw-stepper-item { flex:1; min-width:0; display:flex; align-items:center; justify-content:center; gap:.4rem; padding:.6rem .5rem; border-radius:999px; background:var(--input-bg); color:var(--text-muted); font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.03em; white-space:nowrap; transition:all .2s ease; }
    .pw-stepper-item.active { background:linear-gradient(135deg,#fdf4ff,#f7e7ff); color:#461256; }
    .pw-stepper-item.completed { background:var(--soft-success-bg); color:var(--soft-success-text); }
    .pw-step-dot { width:20px; height:20px; display:inline-flex; align-items:center; justify-content:center; border-radius:50%; background:currentColor; color:#fff; font-size:.68rem; font-weight:800; flex-shrink:0; }
    .pw-panel { display:none; }
    .pw-panel.active { display:block; }
    .pw-card { background:var(--card-bg); border:1px solid var(--card-border); border-radius:0 0 14px 14px; padding:1.5rem; }
    .pw-course-row { border:1px solid var(--card-border); border-radius:12px; padding:1rem; margin-bottom:.75rem; background:var(--input-bg); }
    .pw-course-row.disabled { opacity:.45; }
    .pw-actions { display:flex; justify-content:space-between; gap:.75rem; margin-top:1.5rem; }
    @media (max-width:576px) {
        .pw-stepper-item span:last-child { display:none; }
        .pw-stepper-item { justify-content:center; }
    }
</style>

@if($registration->status === 'rejected')
<div class="alert alert-danger"><i class="bi bi-x-circle me-2"></i>Pendaftaran ini telah ditolak dan tidak dapat diproses.</div>
@else

<div class="dashboard-card p-0" style="overflow:hidden">
    <div class="pw-stepper">
        <div class="pw-stepper-item active" data-stepper="1"><span class="pw-step-dot">1</span><span>Informasi Siswa</span></div>
        <div class="pw-stepper-item" data-stepper="2"><span class="pw-step-dot">2</span><span>Paket Kelas</span></div>
        <div class="pw-stepper-item" data-stepper="3"><span class="pw-step-dot">3</span><span>Mapel &amp; Guru</span></div>
        <div class="pw-stepper-item" data-stepper="4"><span class="pw-step-dot">4</span><span>Pembayaran</span></div>
        <div class="pw-stepper-item" data-stepper="5"><span class="pw-step-dot">5</span><span>Preview</span></div>
    </div>

    <form id="processForm" class="pw-card">
        @csrf

        {{-- STEP 1: INFORMASI SISWA --}}
        <div class="pw-panel active" data-step="1">
            <h6 class="fw-bold mb-3"><i class="bi bi-person-vcard me-2 text-primary"></i>Informasi Siswa</h6>
            <p class="text-muted" style="font-size:.8rem">Data ini awalnya diisi siswa saat mendaftar &mdash; admin dapat mengoreksi/melengkapinya jika perlu.</p>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:.78rem">Nama <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" value="{{ $registration->name }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:.78rem">No. HP <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="phone" value="{{ $registration->phone }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:.78rem">Jenis Kelamin</label>
                    <select class="form-select" name="gender">
                        <option value="">Pilih…</option>
                        <option value="L" {{ $registration->gender==='L'?'selected':'' }}>Laki-laki</option>
                        <option value="P" {{ $registration->gender==='P'?'selected':'' }}>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Tempat Lahir</label>
                    <input type="text" class="form-control" name="birth_place" value="{{ $registration->birth_place }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Tgl Lahir</label>
                    <input type="date" class="form-control" name="birth_date" value="{{ $registration->birth_date?->format('Y-m-d') }}">
                </div>
                <div class="col-12">
                    <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Alamat</label>
                    <input type="text" class="form-control" name="address" value="{{ $registration->address }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Nama Orang Tua</label>
                    <input type="text" class="form-control" name="parent_name" value="{{ $registration->parent_name }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">No. HP Orang Tua</label>
                    <input type="text" class="form-control" name="parent_phone" value="{{ $registration->parent_phone }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Program</label>
                    <select class="form-select" name="program">
                        <option value="">Pilih…</option>
                        <option value="kelas" {{ $registration->program==='kelas'?'selected':'' }}>Kelas</option>
                        <option value="privat" {{ $registration->program==='privat'?'selected':'' }}>Privat</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Sistem</label>
                    <select class="form-select" name="system">
                        <option value="">Pilih…</option>
                        <option value="online" {{ $registration->system==='online'?'selected':'' }}>Online</option>
                        <option value="offline" {{ $registration->system==='offline'?'selected':'' }}>Offline</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:.78rem">Cabang <span class="text-danger">*</span></label>
                    <select name="branch_id" id="branchSelect" class="form-select" required>
                        <option value="">Pilih cabang…</option>
                        @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ $matchedBranch && $matchedBranch->id === $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                    <div class="form-text" style="font-size:.72rem">Cabang asal pendaftaran: <strong>{{ $registration->branch ?: '–' }}</strong></div>
                </div>
            </div>
            <div class="pw-actions"><span></span><button type="button" class="btn btn-primary" data-action="next">Lanjut<i class="bi bi-arrow-right ms-1"></i></button></div>
        </div>

        {{-- STEP 2: PAKET KELAS --}}
        <div class="pw-panel" data-step="2">
            <h6 class="fw-bold mb-3"><i class="bi bi-box-seam me-2 text-primary"></i>Paket Kelas</h6>

            {{-- TOGGLE PAKET STANDAR / CUSTOM --}}
            <input type="hidden" name="is_custom_package" id="isCustomPackage" value="0">
            <div class="d-flex gap-2 mb-3">
                <button type="button" id="btnStandard" onclick="switchPackage('standard')" class="btn btn-sm btn-primary flex-fill">
                    <i class="bi bi-box-seam me-1"></i>Paket Standar
                </button>
                <button type="button" id="btnCustom" onclick="switchPackage('custom')" class="btn btn-sm btn-outline-secondary flex-fill">
                    <i class="bi bi-pencil-square me-1"></i>Paket Custom
                </button>
            </div>

            {{-- PAKET STANDAR --}}
            <div id="standardPackage">
                <p class="text-muted" style="font-size:.83rem">Pilih paket belajar untuk siswa ini (opsional — bisa dilewati jika belum ada paket yang cocok).</p>
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:.78rem">Paket Kelas</label>
                    <select name="package_id" id="packageDropdown" class="form-select">
                        <option value="" data-harga="0">— Tanpa Paket (susun manual per mata pelajaran) —</option>
                        @foreach($packages as $pkg)
                        <option value="{{ $pkg->id }}" data-cabang="{{ $pkg->cabang_id }}" data-harga="{{ $pkg->harga }}">
                            {{ $pkg->nama }} — {{ $pkg->tipe_kelas ?? 'Reguler' }} · {{ $pkg->jumlah_pertemuan ?? '–' }} pertemuan · Rp{{ number_format($pkg->harga ?? 0,0,',','.') }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div id="packageInfoBox" class="p-3 rounded-3 d-none" style="background:rgba(200,77,223,.06);border:1.5px solid rgba(200,77,223,.25)">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <div class="fw-semibold" id="pkgInfoNama">–</div>
                            <div class="text-muted" style="font-size:.75rem" id="pkgInfoDetail">–</div>
                        </div>
                        <div class="fw-bold text-primary fs-6" id="pkgInfoHarga">–</div>
                    </div>
                </div>
            </div>

            {{-- PAKET CUSTOM --}}
            <div id="customPackage" style="display:none">
                <p class="text-muted" style="font-size:.83rem">Susun paket belajar khusus untuk siswa ini. Paket akan dibuat dan tersimpan di data master paket.</p>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:.78rem">Nama Paket <span class="text-danger">*</span></label>
                        <input type="text" name="custom_package_name" class="form-control" placeholder="cth. Intensif UTBK 12 SMA" maxlength="150">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.78rem">Jenis Paket <span class="text-danger">*</span></label>
                        <select name="custom_jenis" class="form-select">
                            <option value="">Pilih jenis…</option>
                            <option value="reguler">Reguler</option>
                            <option value="intensif">Intensif</option>
                            <option value="privat" selected>Privat (1 Siswa)</option>
                            <option value="online">Online</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.78rem">Jumlah Sesi <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah_pertemuan" class="form-control" value="8" min="1">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.78rem">Metode Absensi <span class="text-danger">*</span></label>
                        <select name="custom_metode_absensi" class="form-select">
                            <option value="manual" selected>Manual</option>
                            <option value="otomatis">Otomatis</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.78rem">Tipe Kelas <span class="text-danger">*</span></label>
                        <select name="custom_tipe_kelas" class="form-select">
                            <option value="offline" selected>Offline</option>
                            <option value="online">Online</option>
                            <option value="private">Private</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.78rem">Harga Dasar (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="custom_package_price" id="customPackagePrice" class="form-control" placeholder="0" value="0" min="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.78rem">Status</label>
                        <select name="custom_status" class="form-select">
                            <option value="aktif" selected>Aktif</option>
                            <option value="nonaktif">Non Aktif</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Deskripsi</label>
                        <textarea name="custom_deskripsi" class="form-control" rows="2" placeholder="Deskripsi paket belajar…"></textarea>
                    </div>
                </div>
            </div>

            <div class="pw-actions">
                <button type="button" class="btn btn-outline-secondary" data-action="prev"><i class="bi bi-arrow-left me-1"></i>Kembali</button>
                <button type="button" class="btn btn-primary" data-action="next">Lanjut<i class="bi bi-arrow-right ms-1"></i></button>
            </div>
        </div>

        {{-- STEP 3: MAPEL & GURU --}}
        <div class="pw-panel" data-step="3">
            <h6 class="fw-bold mb-3"><i class="bi bi-journal-bookmark me-2 text-primary"></i>Mata Pelajaran, Guru &amp; Jadwal</h6>
            @if($courses->isEmpty())
            <div class="alert alert-warning" style="font-size:.85rem"><i class="bi bi-exclamation-triangle me-2"></i>Tidak ditemukan mata pelajaran yang cocok dengan minat pendaftaran ini di data master. Hubungi bagian akademik untuk melengkapi data mata pelajaran.</div>
            @else
            <p class="text-muted mb-3" style="font-size:.83rem">Centang mata pelajaran yang akan diambil siswa, tentukan guru &amp; jumlah sesi, lalu isi jadwal kelas untuk pengecekan konflik guru.</p>
            @endif

            {{-- CARD A: MATA PELAJARAN & GURU --}}
            <div class="mb-3" style="border:1px solid var(--card-border);border-radius:14px;overflow:hidden">
                <div class="px-3 py-2 d-flex align-items-center gap-2" style="background:linear-gradient(90deg,rgba(70,18,86,.07),transparent);border-bottom:1px solid var(--card-border)">
                    <i class="bi bi-journal-bookmark text-primary"></i>
                    <span class="fw-bold" style="font-size:.85rem">Mata Pelajaran &amp; Guru</span>
                </div>
                <div class="p-3">
                    {{-- Column headers (md+) --}}
                    <div class="row g-2 mb-1 d-none d-md-flex px-1">
                        <div class="col-md-3"><span style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;letter-spacing:.05em">Mata Pelajaran</span></div>
                        <div class="col-md-3"><span style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;letter-spacing:.05em">Guru Pengajar</span></div>
                        <div class="col-md-2"><span style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;letter-spacing:.05em">Jml Sesi</span></div>
                        <div class="col-md-3"><span style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;letter-spacing:.05em">Biaya (Rp)</span></div>
                    </div>

                    <div id="courseRowsContainer">
                        @foreach($courses as $course)
                        @php $fee = $course->fee->amount ?? 0; @endphp
                        <div class="pw-course-row" data-course-row="{{ $course->id }}">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input course-check" type="checkbox" name="course_ids[]" value="{{ $course->id }}" id="course{{ $course->id }}" checked>
                                        <label class="form-check-label fw-semibold" for="course{{ $course->id }}">{{ $course->nama }}</label>
                                    </div>
                                    <div class="form-text" style="font-size:.68rem">Mapel pilihan siswa</div>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select form-select-sm guru-select" name="course_teacher[{{ $course->id }}]" data-course-id="{{ $course->id }}">
                                        <option value="">Pilih guru…</option>
                                        @foreach($course->guru as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" min="1" class="form-control form-control-sm" name="course_sessions[{{ $course->id }}]" placeholder="Jml sesi" value="{{ $registration->interest_sessions[$course->nama] ?? 8 }}">
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" min="0" class="form-control fee-input" name="course_fee[{{ $course->id }}]" value="{{ $fee }}">
                                    </div>
                                </div>
                                <div class="col-md-1 text-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCourseRow(this, {{ $course->id }})" title="Hapus mapel ini"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- TAMBAH MATA PELAJARAN LAIN --}}
                    <div class="mt-3 p-3 rounded-3" style="background:var(--input-bg);border:1px dashed var(--card-border)">
                        <div class="d-flex gap-2 align-items-end flex-wrap">
                            <div class="flex-grow-1" style="min-width:220px">
                                <label class="form-label fw-semibold" style="font-size:.78rem">Tambah Mata Pelajaran Lain</label>
                                <select id="extraCourseSelect" class="form-select form-select-sm"></select>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addExtraCourse()"><i class="bi bi-plus-circle me-1"></i>Tambah</button>
                        </div>
                        <div class="text-muted mt-2" id="extraCourseEmptyMsg" style="font-size:.72rem;display:none">Semua mata pelajaran di data master sudah ditambahkan.</div>
                    </div>

                    {{-- RINGKASAN TOTAL SESI & ESTIMASI BIAYA --}}
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 text-center" style="background:rgba(200,77,223,.08);border:1.5px solid rgba(200,77,223,.35)">
                                <div class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Total Sesi</div>
                                <div class="fw-bold fs-5 text-primary" id="summaryTotalSesi">0</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 text-center" style="background:rgba(200,77,223,.08);border:1.5px solid rgba(200,77,223,.35)">
                                <div class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Total Estimasi Biaya Guru</div>
                                <div class="fw-bold fs-5 text-primary" id="summaryTotalFee">Rp0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD B: JADWAL KELAS --}}
            <div class="mb-3" style="border:1px solid var(--card-border);border-radius:14px;overflow:hidden">
                <div class="px-3 py-2 d-flex align-items-center gap-2" style="background:linear-gradient(90deg,rgba(70,18,86,.07),transparent);border-bottom:1px solid var(--card-border)">
                    <i class="bi bi-calendar-week text-primary"></i>
                    <span class="fw-bold" style="font-size:.85rem">Jadwal Kelas</span>
                    <span class="ms-1 text-muted" style="font-size:.75rem">&mdash; opsional</span>
                </div>
                <div class="p-3">
                    <p class="text-muted mb-3" style="font-size:.82rem">Isi hari, jam, dan ruang/media untuk setiap mata pelajaran. Guru ditampilkan otomatis sesuai pilihan di tabel atas. Pengisian ini <strong>tidak wajib</strong> dan tidak memblokir submit.</p>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle mb-0" style="font-size:.83rem;min-width:700px">
                            <thead>
                                <tr style="background:var(--input-bg)">
                                    <th style="width:40px;font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700">No</th>
                                    <th style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700">Mata Pelajaran</th>
                                    <th style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700">Guru</th>
                                    <th style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700">Hari</th>
                                    <th style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700">Jam Mulai</th>
                                    <th style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700">Jam Berakhir</th>
                                    <th style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700">Ruang / Media</th>
                                </tr>
                            </thead>
                            <tbody id="scheduleRowsContainer">
                                @foreach($courses as $course)
                                <tr class="pw-schedule-row" data-schedule-row="{{ $course->id }}">
                                    <td class="text-center text-muted sched-no" style="font-size:.78rem">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="badge rounded-pill" style="background:rgba(200,77,223,.12);color:#461256;font-size:.75rem;font-weight:600;padding:.3em .75em">{{ $course->nama }}</span>
                                    </td>
                                    <td>
                                        <span class="sched-guru-name text-muted" data-course-id="{{ $course->id }}" style="font-size:.82rem">—</span>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm hari-select" data-course-id="{{ $course->id }}" style="min-width:88px">
                                            <option value="">Pilih…</option>
                                            <option value="1">Senin</option>
                                            <option value="2">Selasa</option>
                                            <option value="3">Rabu</option>
                                            <option value="4">Kamis</option>
                                            <option value="5">Jum'at</option>
                                            <option value="6">Sabtu</option>
                                            <option value="0">Minggu</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="time" class="form-control form-control-sm jam-mulai-input" data-course-id="{{ $course->id }}" style="min-width:100px">
                                    </td>
                                    <td>
                                        <input type="time" class="form-control form-control-sm jam-selesai-input" data-course-id="{{ $course->id }}" style="min-width:100px">
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm room-select" name="schedule_room[{{ $course->id }}]" data-course-id="{{ $course->id }}" style="min-width:140px">
                                            <option value="">— Pilih ruang —</option>
                                            @foreach($rooms as $room)
                                            <option value="{{ $room->id }}">{{ $room->nama_ruangan }}{{ $room->kapasitas ? ' ('.$room->kapasitas.' org)' : '' }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="scheduleEmptyMsg" class="text-muted mt-2" style="font-size:.8rem;display:none"><i class="bi bi-info-circle me-1"></i>Tidak ada mata pelajaran yang dipilih.</div>
                </div>
            </div>

            {{-- CARD C: CEK KONFLIK JADWAL GURU --}}
            <div class="mb-3" style="border:1px solid rgba(246,175,35,.4);border-radius:14px;overflow:hidden">
                <div class="px-3 py-2 d-flex align-items-center justify-content-between gap-2 flex-wrap" style="background:linear-gradient(90deg,rgba(246,175,35,.08),transparent);border-bottom:1px solid rgba(246,175,35,.3)">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-search" style="color:#e09000"></i>
                        <span class="fw-bold" style="font-size:.85rem">Cek Konflik Jadwal Guru</span>
                    </div>
                    <button type="button" class="btn btn-sm" style="background:rgba(246,175,35,.15);color:#8a5e00;border:1px solid rgba(246,175,35,.5);border-radius:10px" onclick="runAllConflictChecks()">
                        <i class="bi bi-search me-1"></i>Cek Semua Guru
                    </button>
                </div>
                <div class="p-3">
                    <p class="text-muted mb-3" style="font-size:.82rem">Klik <strong>Cek Semua Guru</strong> untuk memeriksa apakah guru yang dipilih memiliki jadwal lain yang bentrok. Status juga diperbarui otomatis saat guru, hari, atau jam diubah di tabel jadwal.</p>
                    <div id="conflictResultsPanel">
                        @foreach($courses as $course)
                        <div class="d-flex align-items-center gap-2 mb-2 p-2 rounded-3" id="conflict-result-{{ $course->id }}" style="background:var(--input-bg)">
                            <span class="badge rounded-pill flex-shrink-0" style="background:rgba(200,77,223,.12);color:#461256;font-size:.74rem;font-weight:600;padding:.3em .7em;min-width:80px;text-align:center">{{ $course->nama }}</span>
                            <div class="conflict-warning-box text-muted" data-course-id="{{ $course->id }}" style="font-size:.8rem">—</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="pw-actions">
                <button type="button" class="btn btn-outline-secondary" data-action="prev"><i class="bi bi-arrow-left me-1"></i>Kembali</button>
                <button type="button" class="btn btn-primary" data-action="next">Lanjut<i class="bi bi-arrow-right ms-1"></i></button>
            </div>
        </div>

        {{-- STEP 4: PEMBAYARAN --}}
        <div class="pw-panel" data-step="4">
            <h6 class="fw-bold mb-3"><i class="bi bi-cash-coin me-2 text-primary"></i>Pembayaran</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:.78rem">Total Biaya</label>
                    <div class="input-group"><span class="input-group-text">Rp</span><input type="number" min="0" class="form-control" id="totalBiaya" name="total_biaya" required></div>
                    <div class="form-text" style="font-size:.72rem">Dihitung otomatis dari mata pelajaran / paket yang dipilih — bisa disesuaikan.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Biaya per Sesi (opsional)</label>
                    <div class="input-group"><span class="input-group-text">Rp</span><input type="number" min="0" class="form-control" name="biaya_per_sesi"></div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold" style="font-size:.78rem">Status Pembayaran</label>
                    <div class="d-flex gap-3 flex-wrap">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_status" id="payBelum" value="belum_bayar" checked>
                            <label class="form-check-label" for="payBelum">Belum Dibayar &mdash; kirim invoice, siswa masuk status <em>Atur Jadwal</em></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_status" id="payLunas" value="lunas">
                            <label class="form-check-label" for="payLunas">Lunas &mdash; siswa langsung <em>Terjadwal</em></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pw-actions">
                <button type="button" class="btn btn-outline-secondary" data-action="prev"><i class="bi bi-arrow-left me-1"></i>Kembali</button>
                <button type="button" class="btn btn-primary" data-action="next">Lanjut<i class="bi bi-arrow-right ms-1"></i></button>
            </div>
        </div>

        {{-- STEP 5: PREVIEW --}}
        <div class="pw-panel" data-step="5">
            <h6 class="fw-bold mb-3"><i class="bi bi-clipboard2-check me-2 text-primary"></i>Preview &amp; Konfirmasi</h6>
            <div id="previewBox" class="p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border);font-size:.86rem"></div>
            <div class="alert alert-info mt-3" style="font-size:.82rem"><i class="bi bi-info-circle me-2"></i>Setelah disubmit, akun login siswa akan dibuat otomatis dan Anda dapat langsung mengirimkannya ke WhatsApp siswa.</div>
            <div class="pw-actions">
                <button type="button" class="btn btn-outline-secondary" data-action="prev"><i class="bi bi-arrow-left me-1"></i>Kembali</button>
                <button type="submit" class="btn btn-success" id="submitBtn">
                    <span id="submitText"><i class="bi bi-check-circle me-1"></i>Verifikasi &amp; Buat Akun</span>
                    <span id="submitLoading" class="d-none"><span class="spinner-border spinner-border-sm me-1"></span>Memproses...</span>
                </button>
            </div>
        </div>
    </form>

    {{-- SUCCESS PANEL --}}
    <div id="successPanel" class="pw-card d-none text-center">
        <i class="bi bi-check-circle-fill text-success" style="font-size:3rem"></i>
        <h5 class="fw-bold mt-3 mb-1">Akun Siswa Berhasil Dibuat</h5>
        <p class="text-muted" style="font-size:.85rem">Kirim informasi akun ini ke WhatsApp siswa agar bisa langsung login.</p>
        <div class="mx-auto text-start p-3 rounded-3 mt-3" style="max-width:420px;background:var(--input-bg);border:1px solid var(--card-border)">
            <div class="d-flex justify-content-between align-items-center mb-2"><span class="text-muted" style="font-size:.78rem">Nama</span><strong id="cred-name">–</strong></div>
            <div class="d-flex justify-content-between align-items-center mb-2"><span class="text-muted" style="font-size:.78rem">Email</span><code id="cred-email">–</code></div>
            <div class="d-flex justify-content-between align-items-center"><span class="text-muted" style="font-size:.78rem">Password</span><code id="cred-password">–</code></div>
        </div>
        <div class="d-flex justify-content-center gap-2 mt-4 flex-wrap">
            <button type="button" class="btn btn-success" onclick="sendToWA()"><i class="bi bi-whatsapp me-1"></i>Kirim ke WhatsApp Siswa</button>
            <a href="{{ route('admin.registration-list.index') }}" class="btn btn-outline-secondary"><i class="bi bi-list-check me-1"></i>Kembali ke Daftar</a>
        </div>
    </div>
</div>
@endif

</div>
@endsection

@push('scripts')
<script>
const _processUrl = "{{ route('admin.registration-list.process.store', $registration->id) }}";
const _csrf = "{{ csrf_token() }}";
let _credData = {};

function showStep(step) {
    document.querySelectorAll('.pw-panel').forEach((panel, i) => panel.classList.toggle('active', i === step - 1));
    document.querySelectorAll('.pw-stepper-item').forEach((item, i) => {
        const current = i + 1;
        item.classList.toggle('active', current === step);
        item.classList.toggle('completed', current < step);
    });
    if (step === 5) buildPreview();
}

document.querySelectorAll('[data-action="next"]').forEach(btn => {
    btn.addEventListener('click', () => {
        const current = document.querySelector('.pw-panel.active');
        const next = parseInt(current.dataset.step, 10) + 1;
        if (next <= 5) showStep(next);
    });
});
document.querySelectorAll('[data-action="prev"]').forEach(btn => {
    btn.addEventListener('click', () => {
        const current = document.querySelector('.pw-panel.active');
        const prev = parseInt(current.dataset.step, 10) - 1;
        if (prev >= 1) showStep(prev);
    });
});

// ── Sinkronisasi baris jadwal saat mapel dicentang/tidak ──────────────────────
function updateScheduleEmpty() {
    const visible = document.querySelectorAll('.pw-schedule-row:not([style*="display:none"]):not([style*="display: none"])');
    document.getElementById('scheduleEmptyMsg').style.display = visible.length === 0 ? '' : 'none';
}

function recalcTotal() {
    let total = 0;
    let totalSesi = 0;
    document.querySelectorAll('.course-check').forEach(chk => {
        const row = chk.closest('.pw-course-row');
        row.classList.toggle('disabled', !chk.checked);
        row.querySelectorAll('select, input').forEach(el => { if (el !== chk) el.disabled = !chk.checked; });
        // Sync matching schedule row visibility
        const schedRow = document.querySelector(`.pw-schedule-row[data-schedule-row="${chk.value}"]`);
        if (schedRow) schedRow.style.display = chk.checked ? '' : 'none';
        if (chk.checked) {
            const feeInput = row.querySelector('.fee-input');
            const sesiInput = row.querySelector('input[name^="course_sessions"]');
            total += parseFloat(feeInput?.value || 0);
            totalSesi += parseInt(sesiInput?.value || 0, 10);
        }
    });
    document.getElementById('totalBiaya').value = total || 0;
    const sesiEl = document.getElementById('summaryTotalSesi');
    const feeEl  = document.getElementById('summaryTotalFee');
    if (sesiEl) sesiEl.textContent = totalSesi || 0;
    if (feeEl)  feeEl.textContent  = 'Rp' + Number(total || 0).toLocaleString('id-ID');
    updateScheduleEmpty();
    renumberScheduleRows();
}

// ── Rooms list (for schedule dropdown) ────────────────────────────────────────
const _roomsList = @json($rooms->map(fn($r) => ['id' => $r->id, 'nama' => $r->nama_ruangan, 'kapasitas' => $r->kapasitas]));
const _roomOptions = '<option value="">— Pilih ruang —</option>' +
    _roomsList.map(r => `<option value="${r.id}">${r.nama}${r.kapasitas ? ' (' + r.kapasitas + ' org)' : ''}</option>`).join('');

// ── Guru name sync: Card A guru-select → Card B table display ──────────────────
function syncGuruName(courseId) {
    const sel = document.querySelector(`.guru-select[data-course-id="${courseId}"]`);
    const span = document.querySelector(`.sched-guru-name[data-course-id="${courseId}"]`);
    if (!sel || !span) return;
    const chosen = sel.selectedOptions[0];
    span.textContent = chosen && chosen.value ? chosen.text : '—';
}

// ── Renumber visible schedule rows ─────────────────────────────────────────────
function renumberScheduleRows() {
    let n = 1;
    document.querySelectorAll('#scheduleRowsContainer .pw-schedule-row').forEach(tr => {
        const noCell = tr.querySelector('.sched-no');
        if (tr.style.display === 'none') return;
        if (noCell) noCell.textContent = n++;
    });
}

// ── Guru conflict check — conflict panel below Card B ─────────────────────────
const guruConflictCheckUrl = @json(route('admin.registration-list.guru-conflict-check'));

function runConflictCheck(courseId) {
    const guruSelect = document.querySelector(`.guru-select[data-course-id="${courseId}"]`);
    const hariSelect = document.querySelector(`.hari-select[data-course-id="${courseId}"]`);
    const jamMulai   = document.querySelector(`.jam-mulai-input[data-course-id="${courseId}"]`);
    const jamSelesai = document.querySelector(`.jam-selesai-input[data-course-id="${courseId}"]`);
    const warningBox = document.querySelector(`.conflict-warning-box[data-course-id="${courseId}"]`);
    if (!warningBox) return;
    const guruId  = guruSelect?.value;
    const hari    = hariSelect?.value;
    const mulai   = jamMulai?.value;
    const selesai = jamSelesai?.value;
    if (!guruId || hari === '' || !mulai || !selesai) {
        warningBox.innerHTML = '<span class="text-muted" style="font-size:.78rem">Lengkapi guru, hari &amp; jam terlebih dahulu.</span>';
        return;
    }
    if (mulai >= selesai) {
        warningBox.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>Jam berakhir harus setelah jam mulai.</span>';
        return;
    }
    warningBox.innerHTML = '<span class="text-muted"><i class="bi bi-hourglass-split me-1"></i>Mengecek…</span>';
    fetch(guruConflictCheckUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '', 'Accept': 'application/json' },
        body: JSON.stringify({ guru_id: guruId, hari, jam_mulai: mulai, jam_selesai: selesai }),
    })
    .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
    .then(data => {
        warningBox.innerHTML = data.conflict
            ? `<span class="text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>${data.detail}</span>`
            : '<span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Guru tersedia.</span>';
    })
    .catch(() => { warningBox.innerHTML = '<span class="text-muted">Gagal mengecek.</span>'; });
}

function runAllConflictChecks() {
    document.querySelectorAll('#scheduleRowsContainer .pw-schedule-row').forEach(tr => {
        if (tr.style.display !== 'none') {
            const cid = tr.dataset.scheduleRow;
            if (cid) runConflictCheck(cid);
        }
    });
}

function bindGuruConflictEvents(courseId) {
    const guruSelect = document.querySelector(`.guru-select[data-course-id="${courseId}"]`);
    const hariSelect = document.querySelector(`.hari-select[data-course-id="${courseId}"]`);
    const jamMulai   = document.querySelector(`.jam-mulai-input[data-course-id="${courseId}"]`);
    const jamSelesai = document.querySelector(`.jam-selesai-input[data-course-id="${courseId}"]`);
    if (!guruSelect || !hariSelect || !jamMulai || !jamSelesai) return;
    const runCheck = () => runConflictCheck(courseId);
    [guruSelect, hariSelect, jamMulai, jamSelesai].forEach(el => el.addEventListener('change', runCheck));
    // Sync guru name to Card B on guru change
    guruSelect.addEventListener('change', () => syncGuruName(courseId));
}

function bindCourseRowEvents(row) {
    row.querySelectorAll('.course-check, .fee-input, input[name^="course_sessions"]').forEach(el => el.addEventListener('input', recalcTotal));
    row.querySelectorAll('.course-check').forEach(el => el.addEventListener('change', recalcTotal));
    const courseId = row.dataset.courseRow;
    bindGuruConflictEvents(courseId);
}
document.querySelectorAll('.pw-course-row').forEach(bindCourseRowEvents);
recalcTotal();

// ── CRUD Mata Pelajaran (semua mapel bisa ditambah/dihapus admin) ─────────────
const courseMetaList = @json($courseMeta);
const courseMetaMap  = {};
courseMetaList.forEach(c => courseMetaMap[c.id] = c);
const usedCourseIds = new Set(@json($courses->pluck('id')->values()));

function refreshExtraCourseSelect() {
    const sel = document.getElementById('extraCourseSelect');
    const available = courseMetaList.filter(c => !usedCourseIds.has(c.id));
    sel.innerHTML = '<option value="">— Pilih mata pelajaran —</option>' +
        available.map(c => `<option value="${c.id}">${c.nama}</option>`).join('');
    document.getElementById('extraCourseEmptyMsg').style.display = available.length === 0 ? '' : 'none';
}

// Builds Card A (mapel + guru) row
function buildCourseRow(course, isAdmin) {
    const row = document.createElement('div');
    row.className = 'pw-course-row';
    row.dataset.courseRow = course.id;
    const guruOptions = course.guru.map(t => `<option value="${t.id}">${t.name}</option>`).join('');
    row.innerHTML = `
        <div class="row g-2 align-items-center">
            <div class="col-md-3">
                <div class="form-check">
                    <input class="form-check-input course-check" type="checkbox" name="course_ids[]" value="${course.id}" id="rowCourse${course.id}" checked>
                    <label class="form-check-label fw-semibold" for="rowCourse${course.id}">${course.nama}</label>
                </div>
                <div class="form-text" style="font-size:.68rem">${isAdmin ? 'Ditambahkan admin' : 'Mapel pilihan siswa'}</div>
            </div>
            <div class="col-md-3">
                <select class="form-select form-select-sm guru-select" name="course_teacher[${course.id}]" data-course-id="${course.id}">
                    <option value="">Pilih guru…</option>
                    ${guruOptions}
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" min="1" class="form-control form-control-sm" name="course_sessions[${course.id}]" placeholder="Jml sesi" value="8">
            </div>
            <div class="col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Rp</span>
                    <input type="number" min="0" class="form-control fee-input" name="course_fee[${course.id}]" value="${course.fee}">
                </div>
            </div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCourseRow(this, ${course.id})" title="Hapus mapel ini"><i class="bi bi-trash"></i></button>
            </div>
        </div>`;
    return row;
}

// Builds Card B (jadwal kelas) table row — now a <tr> with 7 cols
function buildScheduleRow(course) {
    const tr = document.createElement('tr');
    tr.className = 'pw-schedule-row';
    tr.dataset.scheduleRow = course.id;
    tr.innerHTML = `
        <td class="text-center text-muted sched-no" style="font-size:.78rem">—</td>
        <td><span class="badge rounded-pill" style="background:rgba(200,77,223,.12);color:#461256;font-size:.75rem;font-weight:600;padding:.3em .75em">${course.nama}</span></td>
        <td><span class="sched-guru-name text-muted" data-course-id="${course.id}" style="font-size:.82rem">—</span></td>
        <td>
            <select class="form-select form-select-sm hari-select" data-course-id="${course.id}" style="min-width:88px">
                <option value="">Pilih…</option>
                <option value="1">Senin</option><option value="2">Selasa</option><option value="3">Rabu</option>
                <option value="4">Kamis</option><option value="5">Jum'at</option><option value="6">Sabtu</option><option value="0">Minggu</option>
            </select>
        </td>
        <td><input type="time" class="form-control form-control-sm jam-mulai-input" data-course-id="${course.id}" style="min-width:100px"></td>
        <td><input type="time" class="form-control form-control-sm jam-selesai-input" data-course-id="${course.id}" style="min-width:100px"></td>
        <td><select class="form-select form-select-sm room-select" name="schedule_room[${course.id}]" data-course-id="${course.id}" style="min-width:140px">${_roomOptions}</select></td>`;
    return tr;
}

// Builds a conflict result row for Card C panel
function buildConflictResult(course) {
    const div = document.createElement('div');
    div.className = 'd-flex align-items-center gap-2 mb-2 p-2 rounded-3';
    div.id = `conflict-result-${course.id}`;
    div.style.background = 'var(--input-bg)';
    div.innerHTML = `
        <span class="badge rounded-pill flex-shrink-0" style="background:rgba(200,77,223,.12);color:#461256;font-size:.74rem;font-weight:600;padding:.3em .7em;min-width:80px;text-align:center">${course.nama}</span>
        <div class="conflict-warning-box text-muted" data-course-id="${course.id}" style="font-size:.8rem">—</div>`;
    return div;
}

function addExtraCourse() {
    const sel = document.getElementById('extraCourseSelect');
    const id = parseInt(sel.value, 10);
    if (!id) return;
    const course = courseMetaMap[id];
    if (!course) return;
    usedCourseIds.add(id);
    const courseRow = buildCourseRow(course, true);
    document.getElementById('courseRowsContainer').appendChild(courseRow);
    const schedRow = buildScheduleRow(course);
    document.getElementById('scheduleRowsContainer').appendChild(schedRow);
    const conflictRow = buildConflictResult(course);
    document.getElementById('conflictResultsPanel').appendChild(conflictRow);
    bindCourseRowEvents(courseRow);
    refreshExtraCourseSelect();
    recalcTotal();
}

function removeCourseRow(btn, id) {
    btn.closest('.pw-course-row').remove();
    const schedRow = document.querySelector(`#scheduleRowsContainer .pw-schedule-row[data-schedule-row="${id}"]`);
    if (schedRow) schedRow.remove();
    const conflictRow = document.getElementById(`conflict-result-${id}`);
    if (conflictRow) conflictRow.remove();
    usedCourseIds.delete(id);
    refreshExtraCourseSelect();
    recalcTotal();
}

refreshExtraCourseSelect();

// --- Package dropdown ---
const packageDropdown = document.getElementById('packageDropdown');
const packageInfoBox  = document.getElementById('packageInfoBox');

function onPackageDropdownChange() {
    const sel = packageDropdown.selectedOptions[0];
    const val = packageDropdown.value;
    if (val) {
        const text = sel.text;
        const harga = parseFloat(sel.dataset.harga || 0);
        // Parse detail from option text: "Nama — tipe · N pertemuan · RpXXX"
        const parts = text.split(' — ');
        document.getElementById('pkgInfoNama').textContent = parts[0] || text;
        document.getElementById('pkgInfoDetail').textContent = parts.slice(1).join(' — ').replace(/·\s*Rp[\d.,]+/, '').trim();
        document.getElementById('pkgInfoHarga').textContent = 'Rp' + Number(harga).toLocaleString('id-ID');
        packageInfoBox.classList.remove('d-none');
        document.getElementById('totalBiaya').value = harga;
    } else {
        packageInfoBox.classList.add('d-none');
        recalcTotal();
    }
}
packageDropdown.addEventListener('change', onPackageDropdownChange);

let isCustomPkg = false;
function switchPackage(type) {
    isCustomPkg = (type === 'custom');
    document.getElementById('isCustomPackage').value = isCustomPkg ? '1' : '0';
    document.getElementById('standardPackage').style.display = isCustomPkg ? 'none' : '';
    document.getElementById('customPackage').style.display = isCustomPkg ? '' : 'none';
    document.getElementById('btnStandard').className = 'btn btn-sm flex-fill ' + (!isCustomPkg ? 'btn-primary' : 'btn-outline-secondary');
    document.getElementById('btnCustom').className = 'btn btn-sm flex-fill ' + (isCustomPkg ? 'btn-primary' : 'btn-outline-secondary');
    if (isCustomPkg) {
        document.getElementById('totalBiaya').value = document.getElementById('customPackagePrice').value || 0;
    } else {
        onPackageDropdownChange();
    }
}
document.getElementById('customPackagePrice').addEventListener('input', function() {
    if (isCustomPkg) document.getElementById('totalBiaya').value = this.value || 0;
});

document.getElementById('branchSelect').addEventListener('change', function() {
    const branchId = this.value;
    Array.from(packageDropdown.options).forEach(opt => {
        if (!opt.value) return; // keep "Tanpa Paket" always visible
        const cabang = opt.dataset.cabang || '';
        const hide = branchId && cabang && cabang !== branchId;
        opt.hidden = hide;
        if (hide && packageDropdown.value === opt.value) {
            packageDropdown.value = '';
            onPackageDropdownChange();
        }
    });
});

function buildPreview() {
    const branchName = document.getElementById('branchSelect').selectedOptions[0]?.text || '–';
    let pkgName = 'Tanpa Paket';
    if (isCustomPkg) {
        const cpName = document.querySelector('[name="custom_package_name"]')?.value;
        pkgName = cpName ? (cpName + ' (Custom)') : '— (Custom, belum diisi)';
    } else {
        const sel = packageDropdown.selectedOptions[0];
        pkgName = packageDropdown.value ? sel.text.split(' — ')[0] : 'Tanpa Paket';
    }
    const rows = [];
    document.querySelectorAll('.course-check:checked').forEach(chk => {
        const row = chk.closest('.pw-course-row');
        const teacher = row.querySelector('select').selectedOptions[0]?.text || '–';
        const sesi = row.querySelector('input[name^="course_sessions"]').value || '–';
        const fee = row.querySelector('.fee-input').value || 0;
        const courseName = row.querySelector('.form-check-label').textContent;
        rows.push(`<tr><td>${courseName}</td><td>${teacher}</td><td>${sesi}</td><td>Rp${Number(fee).toLocaleString('id-ID')}</td></tr>`);
    });
    const total = document.getElementById('totalBiaya').value || 0;
    const payStatus = document.querySelector('input[name="payment_status"]:checked').value === 'lunas' ? 'Lunas' : 'Belum Dibayar';

    document.getElementById('previewBox').innerHTML = `
        <div class="row g-2 mb-3">
            <div class="col-md-6"><span class="text-muted">Cabang:</span> <strong>${branchName}</strong></div>
            <div class="col-md-6"><span class="text-muted">Paket:</span> <strong>${pkgName}</strong></div>
        </div>
        <table class="table table-sm"><thead><tr><th>Mapel</th><th>Guru</th><th>Sesi</th><th>Biaya</th></tr></thead>
        <tbody>${rows.join('') || '<tr><td colspan="4" class="text-muted text-center">Tidak ada mapel dipilih</td></tr>'}</tbody></table>
        <div class="d-flex justify-content-between mt-2"><span class="text-muted">Status Pembayaran:</span><strong>${payStatus}</strong></div>
        <div class="d-flex justify-content-between"><span class="text-muted">Total Biaya:</span><strong class="text-primary">Rp${Number(total).toLocaleString('id-ID')}</strong></div>
    `;
}

document.getElementById('processForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const checkedCourses = document.querySelectorAll('.course-check:checked').length;
    if (checkedCourses === 0) {
        showToast('Pilih minimal satu mata pelajaran sebelum melanjutkan.', 'error');
        showStep(3);
        return;
    }
    if (isCustomPkg) {
        const cpName = document.querySelector('[name="custom_package_name"]').value.trim();
        const cpJenis = document.querySelector('[name="custom_jenis"]').value;
        if (!cpName || !cpJenis) {
            showToast('Lengkapi Nama Paket & Jenis Paket pada Paket Custom.', 'error');
            showStep(2);
            return;
        }
    }

    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    document.getElementById('submitText').classList.add('d-none');
    document.getElementById('submitLoading').classList.remove('d-none');

    const formData = new FormData(this);
    fetch(_processUrl, {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': _csrf },
        body: formData,
    })
    .then(r => r.json().then(d => ({ ok: r.ok, d })))
    .then(({ ok, d }) => {
        if (ok && d.success) {
            _credData = d;
            document.getElementById('cred-name').textContent = d.name || '–';
            document.getElementById('cred-email').textContent = d.email || '–';
            document.getElementById('cred-password').textContent = d.password || '–';
            document.getElementById('processForm').classList.add('d-none');
            document.getElementById('successPanel').classList.remove('d-none');
        } else {
            showToast(d.message || 'Gagal memproses pendaftaran.', 'error');
            submitBtn.disabled = false;
            document.getElementById('submitText').classList.remove('d-none');
            document.getElementById('submitLoading').classList.add('d-none');
        }
    })
    .catch(() => {
        showToast('Terjadi kesalahan. Coba lagi.', 'error');
        submitBtn.disabled = false;
        document.getElementById('submitText').classList.remove('d-none');
        document.getElementById('submitLoading').classList.add('d-none');
    });
});

function sendToWA() {
    const phone = (_credData.phone || '').replace(/\D/g, '');
    if (!phone) { showToast('Nomor HP siswa tidak tersedia.', 'error'); return; }
    const wa = phone.startsWith('0') ? '62' + phone.slice(1) : phone;
    const loginUrl = '{{ url("/login") }}';
    const msg = encodeURIComponent(
        'Halo ' + (_credData.name || 'Siswa') + ',\n\n' +
        'Selamat datang di Smart Center Indonesia!\n\n' +
        'Pendaftaran Anda telah *diverifikasi*. Berikut data akun login Anda:\n\n' +
        '*Email:* ' + (_credData.email || '-') + '\n' +
        '*Password:* ' + (_credData.password || '-') + '\n' +
        '*No. Registrasi:* ' + (_credData.no_reg || '-') + '\n\n' +
        '*Link Login:*\n' + loginUrl + '\n\n' +
        'Segera login dan lengkapi profil Anda. Jangan bagikan password kepada siapapun.\n\n' +
        'Terima kasih & selamat belajar!'
    );
    window.open('https://wa.me/' + wa + '?text=' + msg, '_blank');
}
</script>
@endpush
