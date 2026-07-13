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
    /* ── Step 1 option buttons ── */
    .pw-opt-btn { cursor:pointer; padding:.45rem 1rem; border-radius:8px; font-size:.82rem; font-weight:600;
        border:1.5px solid var(--card-border); color:var(--text-muted); background:var(--input-bg);
        transition:all .18s; user-select:none; }
    .pw-opt-btn.active { border-color:#c84ddf; background:rgba(200,77,223,.09); color:#461256; }
    .pw-opt-btn:hover:not(.active) { border-color:#c84ddf; color:#c84ddf; }
    /* ── Day pills ── */
    .pw-day-pill { cursor:pointer; display:inline-flex; align-items:center; justify-content:center;
        width:44px; height:36px; border-radius:8px; font-size:.78rem; font-weight:700;
        border:1.5px solid var(--card-border); color:var(--text-muted); background:var(--input-bg);
        transition:all .15s; user-select:none; }
    .pw-day-pill.selected { border-color:#c84ddf; background:rgba(200,77,223,.12); color:#461256; }
    .pw-day-pill:hover:not(.selected) { border-color:#c84ddf; color:#c84ddf; }
    /* ── Day schedule rows ── */
    .pw-schedule-row { display:flex; align-items:flex-start; gap:.5rem; padding:.5rem .75rem;
        border-radius:10px; background:var(--input-bg); border:1px solid var(--card-border); margin-bottom:.5rem; }
    .pw-day-label { min-width:60px; font-size:.78rem; font-weight:700; color:#461256;
        padding-top:.38rem; flex-shrink:0; }
    .pw-time-slot { display:flex; gap:.4rem; align-items:center; margin-bottom:.3rem; }
    .pw-time-slot:last-child { margin-bottom:0; }
    .pw-time-slot .btn-remove-slot { flex-shrink:0; }
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

            {{-- Branch fixed as hidden input (determined by admin's branch) --}}
            <input type="hidden" name="branch_id" id="branchSelect"
                   value="{{ $matchedBranch?->id ?? '' }}"
                   data-name="{{ $matchedBranch?->name ?? '–' }}">

            <div class="row g-3">
                {{-- Data Pribadi --}}
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
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:.78rem">Kategori Peserta Didik</label>
                    <select class="form-select" name="education_level">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach(['Pra Sekolah (PAUD/TK)','Sekolah Dasar (SD)','Sekolah Menengah Pertama (SMP)','Sekolah Menengah Atas/Kejuruan (SMA/SMK)','Mahasiswa','Umum'] as $lvl)
                        <option value="{{ $lvl }}" {{ $registration->education_level===$lvl?'selected':'' }}>{{ $lvl }}</option>
                        @endforeach
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

                {{-- TEMPAT BELAJAR --}}
                <div class="col-12">
                    <label class="form-label fw-semibold" style="font-size:.78rem">Tempat Belajar</label>
                    @php $curTempat = $registration->learning_place ?? 'kantor'; @endphp
                    <div class="d-flex gap-2 flex-wrap">
                        <div class="pw-opt-btn {{ $curTempat!=='rumah'?'active':'' }}" onclick="pickTempat('kantor',this)">
                            <i class="bi bi-building me-1"></i>Belajar di Kantor
                        </div>
                        <div class="pw-opt-btn {{ $curTempat==='rumah'?'active':'' }}" onclick="pickTempat('rumah',this)">
                            <i class="bi bi-house-door me-1"></i>Guru ke Rumah
                        </div>
                    </div>
                    <input type="hidden" name="tempat_belajar" id="tempatBelajarInput" value="{{ $curTempat }}">
                </div>

                {{-- JADWAL BELAJAR --}}
                <div class="col-12">
                    <label class="form-label fw-semibold" style="font-size:.78rem">Jadwal Belajar
                        <span class="fw-normal text-muted ms-1" style="font-size:.72rem">— pilih hari lalu isi jam</span>
                    </label>
                    {{-- Day pills --}}
                    @php
                        $dayShorts = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
                        $dayFulls  = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
                        $existDays = $registration->day_preferences ?? [];
                    @endphp
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        @foreach($dayShorts as $i => $short)
                        @php $dayVal = $dayFulls[$i]; $isChecked = in_array($dayVal, $existDays); @endphp
                        <label class="pw-day-pill {{ $isChecked?'selected':'' }}" id="dpill-{{ $dayVal }}"
                               onclick="pwToggleDay(this,'{{ $dayVal }}')">
                            <input type="checkbox" name="hari_belajar[]" value="{{ $dayVal }}"
                                   style="display:none" {{ $isChecked?'checked':'' }}>
                            {{ $short }}
                        </label>
                        @endforeach
                    </div>
                    {{-- Per-day time slots --}}
                    <div id="pwDayScheduleWrapper" style="{{ empty($existDays)?'display:none':'' }}">
                        <div id="pwDayScheduleContainer">
                            @foreach($dayFulls as $dayVal)
                            @if(in_array($dayVal, $existDays))
                            <div class="pw-schedule-row" id="pwsrow-{{ $dayVal }}">
                                <div class="pw-day-label">{{ $dayVal }}</div>
                                <div class="flex-fill" id="pwslots-{{ $dayVal }}">
                                    <div class="pw-time-slot">
                                        <input type="text" name="jam_detail[{{ $dayVal }}][]"
                                               class="form-control form-control-sm"
                                               placeholder="cth. 10:00 - 12:00" autocomplete="off">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="pwAddSlot('{{ $dayVal }}')" style="font-size:.72rem;white-space:nowrap">
                                    <i class="bi bi-plus"></i> Slot
                                </button>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    @if($registration->schedule_time)
                    <div class="form-text mt-1" style="font-size:.72rem">
                        <i class="bi bi-clock me-1"></i>Jadwal sebelumnya: <em>{{ $registration->schedule_time }}</em>
                    </div>
                    @endif
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
                        <div class="col-md-2"><span style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;letter-spacing:.05em">Mata Pelajaran</span></div>
                        <div class="col-md-2"><span style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;letter-spacing:.05em">Guru Pengajar</span></div>
                        <div class="col-md-1"><span style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;letter-spacing:.05em">Sesi</span></div>
                        <div class="col-md-2"><span style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;letter-spacing:.05em">Biaya Siswa (Rp)</span></div>
                        <div class="col-md-2"><span style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;letter-spacing:.05em">Honor Guru (Rp)</span></div>
                        <div class="col-md-2"><span style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;letter-spacing:.05em">Margin</span></div>
                    </div>

                    <div id="courseRowsContainer">
                        @foreach($courses as $course)
                        @php $fee = $course->fee->amount ?? 0; $honor = round($fee * 0.6); @endphp
                        <div class="pw-course-row" data-course-row="{{ $course->id }}">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input course-check" type="checkbox" name="course_ids[]" value="{{ $course->id }}" id="course{{ $course->id }}" checked>
                                        <label class="form-check-label fw-semibold" for="course{{ $course->id }}" style="font-size:.82rem">{{ $course->nama }}</label>
                                    </div>
                                    <div class="form-text" style="font-size:.67rem">Mapel pilihan siswa</div>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select form-select-sm guru-select" name="course_teacher[{{ $course->id }}]" data-course-id="{{ $course->id }}">
                                        <option value="">Pilih guru…</option>
                                        @foreach($course->guru as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <input type="number" min="1" class="form-control form-control-sm" name="course_sessions[{{ $course->id }}]" placeholder="Sesi" value="{{ $registration->interest_sessions[$course->nama] ?? 8 }}">
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" min="0" class="form-control fee-input" name="course_fee[{{ $course->id }}]" value="{{ $fee }}" oninput="updateRowMargin(this)">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" min="0" class="form-control honor-input" name="course_honor[{{ $course->id }}]" value="{{ $honor }}" oninput="updateRowMargin(this)">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="margin-display px-2 py-1 rounded-2 text-center" style="background:rgba(16,185,129,.07);border:1px solid rgba(16,185,129,.2);font-size:.78rem">
                                        <span class="margin-rp fw-semibold" style="color:#10b981">Rp{{ number_format($fee - $honor, 0, ',', '.') }}</span>
                                        <span class="margin-pct text-muted ms-1" style="font-size:.7rem">({{ $fee > 0 ? round((($fee-$honor)/$fee)*100) : 0 }}%)</span>
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

                    {{-- RINGKASAN QUOTATION --}}
                    <div class="row g-3 mt-3">
                        <div class="col-6 col-md-3">
                            <div class="p-2 rounded-3 text-center" style="background:rgba(200,77,223,.08);border:1.5px solid rgba(200,77,223,.35)">
                                <div class="text-muted" style="font-size:.68rem;text-transform:uppercase;letter-spacing:.04em">Total Sesi</div>
                                <div class="fw-bold fs-5 text-primary" id="summaryTotalSesi">0</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-2 rounded-3 text-center" style="background:rgba(200,77,223,.08);border:1.5px solid rgba(200,77,223,.35)">
                                <div class="text-muted" style="font-size:.68rem;text-transform:uppercase;letter-spacing:.04em">Total Biaya Siswa</div>
                                <div class="fw-bold fs-5 text-primary" id="summaryTotalFee">Rp0</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-2 rounded-3 text-center" style="background:rgba(14,165,233,.08);border:1.5px solid rgba(14,165,233,.3)">
                                <div class="text-muted" style="font-size:.68rem;text-transform:uppercase;letter-spacing:.04em">Total Honor Guru</div>
                                <div class="fw-bold fs-5" id="summaryTotalHonor" style="color:#0ea5e9">Rp0</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-2 rounded-3 text-center" style="background:rgba(16,185,129,.08);border:1.5px solid rgba(16,185,129,.3)">
                                <div class="text-muted" style="font-size:.68rem;text-transform:uppercase;letter-spacing:.04em">Est. Margin Bimbel</div>
                                <div class="fw-bold fs-5" id="summaryMargin" style="color:#10b981">Rp0 <span id="summaryMarginPct" class="fw-normal" style="font-size:.65rem">(0%)</span></div>
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
                    <button type="button" id="btnCekSemuaGuru" class="btn btn-sm" style="background:rgba(246,175,35,.15);color:#8a5e00;border:1px solid rgba(246,175,35,.5);border-radius:10px">
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

            {{-- Total biaya (auto from step 3) --}}
            <div class="mb-4 p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                <label class="form-label fw-semibold mb-1" style="font-size:.78rem">Total Biaya Program</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" min="0" class="form-control fw-bold" id="totalBiaya" name="total_biaya" required>
                </div>
                <div class="form-text" style="font-size:.72rem">Dihitung otomatis dari mapel/paket — bisa disesuaikan.</div>
            </div>

            {{-- Metode Pembayaran selector --}}
            <label class="form-label fw-semibold mb-2" style="font-size:.78rem">Metode Pembayaran <span class="text-danger">*</span></label>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="pm-card rounded-3 p-3" data-method="prabayar" onclick="selectPaymentMethod('prabayar')"
                         style="border:2px solid var(--card-border);cursor:pointer;transition:border-color .2s,background .2s">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <div style="width:32px;height:32px;border-radius:8px;background:rgba(200,77,223,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <i class="bi bi-wallet2" style="color:#461256"></i>
                            </div>
                            <span class="fw-semibold" style="font-size:.9rem">Prabayar (Prepaid)</span>
                        </div>
                        <p class="text-muted mb-0" style="font-size:.78rem">Siswa membayar sebelum kelas — bisa langsung lunas atau dicicil sesuai kesepakatan.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="pm-card rounded-3 p-3" data-method="pascabayar" onclick="selectPaymentMethod('pascabayar')"
                         style="border:2px solid var(--card-border);cursor:pointer;transition:border-color .2s,background .2s">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <div style="width:32px;height:32px;border-radius:8px;background:rgba(16,185,129,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <i class="bi bi-calendar-check" style="color:#10b981"></i>
                            </div>
                            <span class="fw-semibold" style="font-size:.9rem">Pascabayar (Per Sesi)</span>
                        </div>
                        <p class="text-muted mb-0" style="font-size:.78rem">Tagihan awal Rp 0. Invoice sesi dibuat otomatis setiap guru submit Jurnal Mengajar.</p>
                    </div>
                </div>
            </div>

            {{-- ── PRABAYAR PANEL ── --}}
            <div id="prabayarPanel" style="display:none">
                <div class="mb-3">
                    <label class="form-label fw-semibold mb-2" style="font-size:.78rem">Jenis Pembayaran</label>
                    <div class="d-flex gap-4 flex-wrap">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="prabayar_type" id="payLunas" value="lunas" checked onchange="onPrabayarTypeChange()">
                            <label class="form-check-label fw-semibold" for="payLunas"><i class="bi bi-check-circle-fill text-success me-1"></i>Langsung Lunas</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="prabayar_type" id="payCicilan" value="cicilan" onchange="onPrabayarTypeChange()">
                            <label class="form-check-label fw-semibold" for="payCicilan"><i class="bi bi-credit-card-2-front text-primary me-1"></i>Cicilan</label>
                        </div>
                    </div>
                </div>

                {{-- Langsung Lunas --}}
                <div id="lunasPanel" style="display:none">
                    <div class="p-3 rounded-3 mb-3" style="background:rgba(16,185,129,.07);border:1px solid rgba(16,185,129,.25)">
                        <p class="mb-2" style="font-size:.85rem;color:#10b981"><i class="bi bi-check-circle-fill me-2"></i>Invoice senilai <strong>Rp <span id="lunasTotalDisplay">0</span></strong> akan dibuat.</p>
                        <div class="d-flex gap-3 flex-wrap">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="prabayar_lunas_status" id="statusBelum" value="belum_bayar" checked onchange="document.getElementById('paymentStatusInput').value=this.value">
                                <label class="form-check-label" for="statusBelum" style="font-size:.83rem">Kirim invoice — tandai lunas setelah bayar diterima</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="prabayar_lunas_status" id="statusLunas" value="lunas" onchange="document.getElementById('paymentStatusInput').value=this.value">
                                <label class="form-check-label" for="statusLunas" style="font-size:.83rem">Langsung tandai <strong>Lunas</strong> sekarang</label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Cicilan --}}
                <div id="cicilanPanel" style="display:none">
                    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                        <span class="fw-semibold" style="font-size:.85rem">Rincian Cicilan</span>
                        <div class="d-flex align-items-center gap-2">
                            <label style="font-size:.8rem;color:var(--text-muted)">Jumlah cicilan:</label>
                            <input type="number" min="1" max="24" class="form-control form-control-sm" id="jumlahCicilan" value="2" style="width:72px" oninput="rebuildCicilanRows()">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle mb-0" style="font-size:.82rem">
                            <thead>
                                <tr style="background:var(--input-bg)">
                                    <th style="width:36px;font-size:.7rem;color:var(--text-muted)">No</th>
                                    <th style="font-size:.7rem;color:var(--text-muted)">Nominal (Rp)</th>
                                    <th style="font-size:.7rem;color:var(--text-muted)">Mulai Tagih</th>
                                    <th style="font-size:.7rem;color:var(--text-muted)">Jatuh Tempo</th>
                                </tr>
                            </thead>
                            <tbody id="cicilanRowsContainer"></tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2 p-2 rounded-3" style="background:var(--input-bg)">
                        <span style="font-size:.83rem">Total semua cicilan:</span>
                        <span id="cicilanTotalCheck" class="fw-bold" style="font-size:.88rem">Rp 0</span>
                    </div>
                    <div id="cicilanMismatchWarning" class="mt-1" style="font-size:.8rem;color:#b45309;display:none">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>Total cicilan belum sesuai total biaya (Rp <span id="cicilanBiayaRef">0</span>). Harap sesuaikan.
                    </div>
                </div>
            </div>

            {{-- ── PASCABAYAR PANEL ── --}}
            <div id="pascabayarPanel" style="display:none">
                <div class="p-3 rounded-3 mb-3" style="background:rgba(14,165,233,.06);border:1px solid rgba(14,165,233,.25)">
                    <div class="fw-semibold mb-2" style="font-size:.88rem;color:#0ea5e9"><i class="bi bi-info-circle-fill me-2"></i>Skema Pascabayar (Per Sesi)</div>
                    <ul class="mb-0 ps-3" style="font-size:.84rem;color:var(--text-muted)">
                        <li class="mb-1">Tagihan awal hari ini adalah <strong>Rp 0</strong> (hanya biaya admin jika ada)</li>
                        <li class="mb-1">Akun siswa akan <strong>otomatis aktif</strong> setelah proses selesai</li>
                        <li>Invoice sesi akan di-generate setiap guru submit <strong>Jurnal Mengajar</strong></li>
                    </ul>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.78rem">Biaya Admin (opsional)</label>
                        <div class="input-group input-group-sm"><span class="input-group-text">Rp</span>
                            <input type="number" min="0" name="biaya_admin" class="form-control" placeholder="0">
                        </div>
                        <div class="form-text" style="font-size:.7rem">Ditagihkan di awal, jika ada.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.78rem">Tarif per Sesi</label>
                        <div class="input-group input-group-sm"><span class="input-group-text">Rp</span>
                            <input type="number" min="0" name="biaya_per_sesi" class="form-control" placeholder="0">
                        </div>
                        <div class="form-text" style="font-size:.7rem">Dipakai untuk generate invoice per sesi dari jurnal mengajar.</div>
                    </div>
                </div>
            </div>

            {{-- Hidden state inputs --}}
            <input type="hidden" name="payment_method"  id="paymentMethodInput"  value="prabayar">
            <input type="hidden" name="payment_status"  id="paymentStatusInput"  value="belum_bayar">
            <input type="hidden" name="prabayar_type"   id="prabayarTypeInput"   value="lunas">

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
const _processUrl  = "{{ route('admin.registration-list.process.store', $registration->id) }}";
const _csrf        = "{{ csrf_token() }}";
const _branchName  = @json($matchedBranch ? $matchedBranch->name : '–');
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
    let total = 0, totalHonor = 0, totalSesi = 0;
    document.querySelectorAll('.course-check').forEach(chk => {
        const row = chk.closest('.pw-course-row');
        row.classList.toggle('disabled', !chk.checked);
        row.querySelectorAll('select, input').forEach(el => { if (el !== chk) el.disabled = !chk.checked; });
        // Sync matching schedule row visibility
        const schedRow = document.querySelector(`.pw-schedule-row[data-schedule-row="${chk.value}"]`);
        if (schedRow) schedRow.style.display = chk.checked ? '' : 'none';
        if (chk.checked) {
            const feeInput   = row.querySelector('.fee-input');
            const honorInput = row.querySelector('.honor-input');
            const sesiInput  = row.querySelector('input[name^="course_sessions"]');
            total      += parseFloat(feeInput?.value   || 0);
            totalHonor += parseFloat(honorInput?.value || 0);
            totalSesi  += parseInt(sesiInput?.value    || 0, 10);
        }
    });
    document.getElementById('totalBiaya').value = total || 0;
    const margin = total - totalHonor;
    const pct    = total > 0 ? Math.round((margin / total) * 100) : 0;
    const fmt    = v => 'Rp' + Number(v).toLocaleString('id-ID');
    if (document.getElementById('summaryTotalSesi'))  document.getElementById('summaryTotalSesi').textContent  = totalSesi || 0;
    if (document.getElementById('summaryTotalFee'))   document.getElementById('summaryTotalFee').textContent   = fmt(total);
    if (document.getElementById('summaryTotalHonor')) document.getElementById('summaryTotalHonor').textContent = fmt(totalHonor);
    if (document.getElementById('summaryMargin')) {
        document.getElementById('summaryMargin').childNodes[0].textContent = fmt(margin) + ' ';
        document.getElementById('summaryMarginPct').textContent = '(' + pct + '%)';
    }
    updateScheduleEmpty();
    renumberScheduleRows();
}

// Update a single row's margin display when fee or honor changes
function updateRowMargin(input) {
    const row    = input.closest('.pw-course-row');
    const fee    = parseFloat(row.querySelector('.fee-input')?.value   || 0);
    const honor  = parseFloat(row.querySelector('.honor-input')?.value || 0);
    const margin = fee - honor;
    const pct    = fee > 0 ? Math.round((margin / fee) * 100) : 0;
    const display = row.querySelector('.margin-display');
    if (display) {
        display.querySelector('.margin-rp').textContent  = 'Rp' + margin.toLocaleString('id-ID');
        display.querySelector('.margin-pct').textContent = '(' + pct + '%)';
        display.style.background = margin >= 0 ? 'rgba(16,185,129,.07)' : 'rgba(220,38,38,.07)';
        display.style.borderColor = margin >= 0 ? 'rgba(16,185,129,.2)' : 'rgba(220,38,38,.2)';
        display.querySelector('.margin-rp').style.color = margin >= 0 ? '#10b981' : '#dc2626';
    }
    recalcTotal();
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
    // Iterate ALL conflict-warning-boxes that belong to currently checked courses
    const checkedCourseIds = new Set(
        Array.from(document.querySelectorAll('.course-check:checked')).map(el => el.value)
    );
    const boxes = document.querySelectorAll('.conflict-warning-box[data-course-id]');
    let ran = 0;
    boxes.forEach(box => {
        const cid = box.dataset.courseId;
        if (checkedCourseIds.has(cid)) {
            runConflictCheck(cid);
            ran++;
        }
    });
    if (ran === 0) {
        showToast('Pilih setidaknya satu mata pelajaran dan lengkapi jadwal (guru, hari & jam) untuk mengecek konflik.', 'warning');
    }
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
    const fee   = parseFloat(course.fee) || 0;
    const honor = Math.round(fee * 0.6);
    const margin = fee - honor;
    const pct    = fee > 0 ? Math.round((margin / fee) * 100) : 0;
    row.innerHTML = `
        <div class="row g-2 align-items-center">
            <div class="col-md-2">
                <div class="form-check">
                    <input class="form-check-input course-check" type="checkbox" name="course_ids[]" value="${course.id}" id="rowCourse${course.id}" checked>
                    <label class="form-check-label fw-semibold" for="rowCourse${course.id}" style="font-size:.82rem">${course.nama}</label>
                </div>
                <div class="form-text" style="font-size:.67rem">${isAdmin ? 'Ditambahkan admin' : 'Mapel pilihan siswa'}</div>
            </div>
            <div class="col-md-2">
                <select class="form-select form-select-sm guru-select" name="course_teacher[${course.id}]" data-course-id="${course.id}">
                    <option value="">Pilih guru…</option>
                    ${guruOptions}
                </select>
            </div>
            <div class="col-md-1">
                <input type="number" min="1" class="form-control form-control-sm" name="course_sessions[${course.id}]" placeholder="Sesi" value="8">
            </div>
            <div class="col-md-2">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Rp</span>
                    <input type="number" min="0" class="form-control fee-input" name="course_fee[${course.id}]" value="${fee}" oninput="updateRowMargin(this)">
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Rp</span>
                    <input type="number" min="0" class="form-control honor-input" name="course_honor[${course.id}]" value="${honor}" oninput="updateRowMargin(this)">
                </div>
            </div>
            <div class="col-md-2">
                <div class="margin-display px-2 py-1 rounded-2 text-center" style="background:rgba(16,185,129,.07);border:1px solid rgba(16,185,129,.2);font-size:.78rem">
                    <span class="margin-rp fw-semibold" style="color:#10b981">Rp${margin.toLocaleString('id-ID')}</span>
                    <span class="margin-pct text-muted ms-1" style="font-size:.7rem">(${pct}%)</span>
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

// Branch is now a fixed hidden input — filter packages once on load using its value
(function filterPackagesByBranch() {
    const branchId = document.getElementById('branchSelect')?.value;
    if (!branchId) return;
    Array.from(packageDropdown.options).forEach(opt => {
        if (!opt.value) return;
        const cabang = opt.dataset.cabang || '';
        opt.hidden = cabang && cabang !== branchId;
    });
})();

function buildPreview() {
    const branchName = _branchName;
    let pkgName = 'Tanpa Paket';
    if (isCustomPkg) {
        const cpName = document.querySelector('[name="custom_package_name"]')?.value;
        pkgName = cpName ? (cpName + ' (Custom)') : '— (Custom, belum diisi)';
    } else {
        const sel = packageDropdown.selectedOptions[0];
        pkgName = packageDropdown.value ? sel.text.split(' — ')[0] : 'Tanpa Paket';
    }

    // Collect per-course data for both the summary table and quotation
    const rows = [];
    let totalFee = 0, totalHonor = 0;
    document.querySelectorAll('.course-check:checked').forEach(chk => {
        const row     = chk.closest('.pw-course-row');
        const teacher = row.querySelector('select').selectedOptions[0]?.text || '–';
        const sesi    = row.querySelector('input[name^="course_sessions"]').value || '–';
        const fee     = parseFloat(row.querySelector('.fee-input')?.value   || 0);
        const honor   = parseFloat(row.querySelector('.honor-input')?.value || 0);
        const margin  = fee - honor;
        const pct     = fee > 0 ? Math.round((margin / fee) * 100) : 0;
        const name    = row.querySelector('.form-check-label').textContent.trim();
        totalFee   += fee;
        totalHonor += honor;
        const marginColor  = margin >= 0 ? '#10b981' : '#dc2626';
        rows.push({ name, teacher, sesi, fee, honor, margin, pct, marginColor });
    });

    const totalMargin = totalFee - totalHonor;
    const totalPct    = totalFee > 0 ? Math.round((totalMargin / totalFee) * 100) : 0;
    const fmt = v => 'Rp' + Number(v).toLocaleString('id-ID');

    const method    = document.getElementById('paymentMethodInput').value;
    const payStatus = document.getElementById('paymentStatusInput').value;
    const prabType  = document.getElementById('prabayarTypeInput').value;
    let metodeTxt = '–';
    if (method === 'prabayar') {
        if (prabType === 'cicilan') {
            const n = document.getElementById('jumlahCicilan')?.value || '?';
            metodeTxt = `Prabayar — Cicilan (${n}x)`;
        } else {
            metodeTxt = 'Prabayar — ' + (payStatus === 'lunas' ? 'Lunas Sekarang' : 'Invoice Dikirim');
        }
    } else if (method === 'pascabayar') {
        metodeTxt = 'Pascabayar (Per Sesi)';
    }

    const rowsHtml = rows.length
        ? rows.map(r => `
            <tr>
                <td class="fw-semibold" style="font-size:.83rem">${r.name}</td>
                <td style="font-size:.82rem">${r.teacher}</td>
                <td class="text-center">${r.sesi}</td>
                <td class="text-end">${fmt(r.fee)}</td>
                <td class="text-end">${fmt(r.honor)}</td>
                <td class="text-end fw-semibold" style="color:${r.marginColor}">${fmt(r.margin)} <span class="text-muted fw-normal" style="font-size:.75rem">(${r.pct}%)</span></td>
            </tr>`).join('')
        : '<tr><td colspan="6" class="text-muted text-center">Tidak ada mapel dipilih</td></tr>';

    const totalMarginColor = totalMargin >= 0 ? '#10b981' : '#dc2626';

    // Hoist inline expressions so ${{ }} never appears inside the template literal (Blade parses {{ }} even in JS blocks)
    const _previewEduLevel  = document.querySelector('[name="education_level"]')?.value || '–';
    const _tempatMap        = {kantor:'Di Kantor', rumah:'Guru ke Rumah'};
    const _previewTempat    = _tempatMap[document.getElementById('tempatBelajarInput')?.value] || '–';
    const _previewJadwal    = (() => {
        const days = Array.from(document.querySelectorAll('input[name="hari_belajar[]"]:checked')).map(c => c.value);
        if (!days.length) return '–';
        return days.map(d => {
            const slots = Array.from(document.querySelectorAll('input[name="jam_detail[' + d + '][]"]'))
                              .map(i => i.value).filter(Boolean);
            return d + (slots.length ? ' (' + slots.join(', ') + ')' : '');
        }).join(' · ');
    })();

    document.getElementById('previewBox').innerHTML = `
        {{-- Header info --}}
        <div class="row g-2 mb-3">
            <div class="col-md-4"><span class="text-muted" style="font-size:.83rem">Cabang:</span> <strong>${branchName}</strong></div>
            <div class="col-md-4"><span class="text-muted" style="font-size:.83rem">Paket:</span> <strong>${pkgName}</strong></div>
            <div class="col-md-4"><span class="text-muted" style="font-size:.83rem">Kategori:</span> <strong>${_previewEduLevel}</strong></div>
            <div class="col-md-4"><span class="text-muted" style="font-size:.83rem">Tempat Belajar:</span> <strong>${_previewTempat}</strong></div>
            <div class="col-md-8"><span class="text-muted" style="font-size:.83rem">Jadwal:</span> <strong style="font-size:.82rem">${_previewJadwal}</strong></div>
        </div>

        {{-- Mapel + Guru table --}}
        <div class="fw-bold mb-2" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)"><i class="bi bi-journal-bookmark me-1"></i>Mata Pelajaran & Guru</div>
        <div class="table-responsive mb-3">
            <table class="table table-sm table-bordered align-middle mb-0" style="font-size:.82rem">
                <thead><tr style="background:var(--input-bg)">
                    <th>Mapel</th><th>Guru</th><th class="text-center">Sesi</th>
                    <th class="text-end">Biaya Siswa</th><th class="text-end">Honor Guru</th><th class="text-end">Margin</th>
                </tr></thead>
                <tbody>${rowsHtml}</tbody>
            </table>
        </div>

        {{-- LIVE QUOTATION --}}
        <div class="rounded-3 overflow-hidden mb-3" style="border:1.5px solid rgba(200,77,223,.35)">
            <div class="px-3 py-2 d-flex align-items-center gap-2" style="background:linear-gradient(90deg,rgba(70,18,86,.1),rgba(200,77,223,.08))">
                <i class="bi bi-graph-up-arrow" style="color:#c84ddf"></i>
                <span class="fw-bold" style="font-size:.82rem;color:#461256">📊 Live Quotation</span>
                <span class="ms-auto badge rounded-pill" style="background:rgba(200,77,223,.15);color:#461256;font-size:.7rem">${rows.length} mapel</span>
            </div>
            <div class="p-3" style="background:var(--input-bg)">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 text-center h-100" style="background:rgba(200,77,223,.07);border:1.5px solid rgba(200,77,223,.25)">
                            <div class="text-muted mb-1" style="font-size:.68rem;text-transform:uppercase;letter-spacing:.05em"><i class="bi bi-person me-1"></i>Total Tagihan Siswa</div>
                            <div class="fw-bold" style="font-size:1.15rem;color:#461256">${fmt(totalFee)}</div>
                            <div class="text-muted mt-1" style="font-size:.7rem">Tagihan yang diterbitkan ke siswa</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 text-center h-100" style="background:rgba(14,165,233,.07);border:1.5px solid rgba(14,165,233,.25)">
                            <div class="text-muted mb-1" style="font-size:.68rem;text-transform:uppercase;letter-spacing:.05em"><i class="bi bi-person-badge me-1"></i>Total Honor Guru</div>
                            <div class="fw-bold" style="font-size:1.15rem;color:#0ea5e9">${fmt(totalHonor)}</div>
                            <div class="text-muted mt-1" style="font-size:.7rem">Biaya yang dibayarkan ke guru</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 text-center h-100" style="background:rgba(16,185,129,.07);border:1.5px solid rgba(16,185,129,.25)">
                            <div class="text-muted mb-1" style="font-size:.68rem;text-transform:uppercase;letter-spacing:.05em"><i class="bi bi-building me-1"></i>Pendapatan Bimbel</div>
                            <div class="fw-bold" style="font-size:1.15rem;color:${totalMarginColor}">${fmt(totalMargin)}</div>
                            <div class="mt-1">
                                <span class="badge rounded-pill" style="background:${totalMargin>=0?'rgba(16,185,129,.15)':'rgba(220,38,38,.12)'};color:${totalMarginColor};font-size:.72rem">Margin ${totalPct}%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 pt-3" style="border-top:1px solid var(--card-border)">
                    <div class="row g-2" style="font-size:.84rem">
                        <div class="col-md-6 d-flex justify-content-between py-1" style="border-bottom:1px dashed var(--card-border)">
                            <span class="text-muted">Metode Pembayaran</span><strong>${metodeTxt}</strong>
                        </div>
                        <div class="col-md-6 d-flex justify-content-between py-1" style="border-bottom:1px dashed var(--card-border)">
                            <span class="text-muted">Total Biaya (Final)</span><strong class="text-primary">${fmt(totalFee)}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// ═══════════════════════════════════════════
// STEP 1 — Tempat Belajar & Jadwal JS
// ═══════════════════════════════════════════
function pickTempat(val, el) {
    el.closest('.d-flex').querySelectorAll('.pw-opt-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('tempatBelajarInput').value = val;
}

const PW_DAY_ORDER = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];

function pwToggleDay(pill, dayName) {
    const cb = pill.querySelector('input[type=checkbox]');
    setTimeout(() => {
        const checked = cb.checked;
        pill.classList.toggle('selected', checked);
        if (checked) pwAddDayRow(dayName);
        else pwRemoveDayRow(dayName);
        pwUpdateScheduleWrapper();
    }, 0);
}

function pwAddDayRow(dayName) {
    if (document.getElementById('pwsrow-' + dayName)) return;
    const container = document.getElementById('pwDayScheduleContainer');
    const row = document.createElement('div');
    row.className = 'pw-schedule-row';
    row.id = 'pwsrow-' + dayName;
    row.innerHTML = `
        <div class="pw-day-label">${dayName}</div>
        <div class="flex-fill" id="pwslots-${dayName}">
            <div class="pw-time-slot">
                <input type="text" name="jam_detail[${dayName}][]"
                       class="form-control form-control-sm" placeholder="cth. 10:00 - 12:00" autocomplete="off">
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="pwAddSlot('${dayName}')" style="font-size:.72rem;white-space:nowrap">
            <i class="bi bi-plus"></i> Slot
        </button>`;
    // Insert in day order
    let inserted = false;
    container.querySelectorAll('.pw-schedule-row').forEach(existRow => {
        if (inserted) return;
        const existDay = existRow.id.replace('pwsrow-', '');
        if (PW_DAY_ORDER.indexOf(dayName) < PW_DAY_ORDER.indexOf(existDay)) {
            container.insertBefore(row, existRow);
            inserted = true;
        }
    });
    if (!inserted) container.appendChild(row);
}

function pwRemoveDayRow(dayName) {
    document.getElementById('pwsrow-' + dayName)?.remove();
}

function pwUpdateScheduleWrapper() {
    const wrapper   = document.getElementById('pwDayScheduleWrapper');
    const container = document.getElementById('pwDayScheduleContainer');
    if (!wrapper || !container) return;
    wrapper.style.display = container.querySelectorAll('.pw-schedule-row').length > 0 ? '' : 'none';
}

function pwAddSlot(dayName) {
    const slotsDiv = document.getElementById('pwslots-' + dayName);
    if (!slotsDiv) return;
    const div = document.createElement('div');
    div.className = 'pw-time-slot mt-1';
    div.innerHTML = `
        <input type="text" name="jam_detail[${dayName}][]"
               class="form-control form-control-sm" placeholder="cth. 15:00 - 17:00" autocomplete="off">
        <button type="button" class="btn btn-sm btn-outline-danger" onclick="pwRemoveSlot(this)" title="Hapus">
            <i class="bi bi-x"></i>
        </button>`;
    slotsDiv.appendChild(div);
}

function pwRemoveSlot(btn) {
    btn.closest('.pw-time-slot')?.remove();
}

// ═══════════════════════════════════════════
// STEP 4 — Payment Method JS
// ═══════════════════════════════════════════
function selectPaymentMethod(method) {
    document.querySelectorAll('.pm-card').forEach(c => {
        const isActive = c.dataset.method === method;
        c.style.borderColor   = isActive ? 'var(--bs-primary)' : 'var(--card-border)';
        c.style.background    = isActive ? 'rgba(200,77,223,.06)' : '';
    });
    document.getElementById('paymentMethodInput').value = method;
    document.getElementById('prabayarPanel').style.display  = method === 'prabayar'  ? '' : 'none';
    document.getElementById('pascabayarPanel').style.display = method === 'pascabayar' ? '' : 'none';
    if (method === 'pascabayar') {
        document.getElementById('paymentStatusInput').value = 'belum_bayar';
        document.getElementById('prabayarTypeInput').value  = 'lunas';
    } else {
        onPrabayarTypeChange();
    }
}

function onPrabayarTypeChange() {
    const type = document.querySelector('input[name="prabayar_type"]:checked')?.value || '';
    document.getElementById('prabayarTypeInput').value   = type || 'lunas';
    document.getElementById('lunasPanel').style.display  = type === 'lunas'   ? '' : 'none';
    document.getElementById('cicilanPanel').style.display = type === 'cicilan' ? '' : 'none';
    if (type === 'lunas') {
        const checked = document.querySelector('input[name="prabayar_lunas_status"]:checked');
        document.getElementById('paymentStatusInput').value = checked ? checked.value : 'belum_bayar';
        updateLunasDisplay();
    } else if (type === 'cicilan') {
        document.getElementById('paymentStatusInput').value = 'belum_bayar';
        if (!document.querySelector('#cicilanRowsContainer tr')) rebuildCicilanRows();
    }
}

function updateLunasDisplay() {
    const total = parseFloat(document.getElementById('totalBiaya').value) || 0;
    const el = document.getElementById('lunasTotalDisplay');
    if (el) el.textContent = total.toLocaleString('id-ID');
}

function rebuildCicilanRows() {
    const n         = Math.max(1, parseInt(document.getElementById('jumlahCicilan').value) || 2);
    const totalBiaya = parseFloat(document.getElementById('totalBiaya').value) || 0;
    const container  = document.getElementById('cicilanRowsContainer');
    container.innerHTML = '';
    const base     = n > 0 ? Math.floor(totalBiaya / n) : 0;
    const today    = new Date();
    const pad = v => String(v).padStart(2,'0');
    const dateStr = (d) => `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;
    for (let i = 1; i <= n; i++) {
        const nominal  = i === n ? (totalBiaya - base * (n - 1)) : base;
        const mulaiDate = new Date(today); mulaiDate.setDate(today.getDate() + (i-1)*30);
        const tempoDate = new Date(today); tempoDate.setDate(today.getDate() + i*30);
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="text-center"><span class="badge bg-primary-soft text-primary" style="font-size:.75rem">${i}</span></td>
            <td>
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Rp</span>
                    <input type="number" min="0" class="form-control cicilan-nominal" name="cicilan_nominal[]"
                           value="${nominal}" oninput="updateCicilanTotal()">
                </div>
            </td>
            <td><input type="date" class="form-control form-control-sm" name="cicilan_mulai[]" value="${dateStr(mulaiDate)}"></td>
            <td><input type="date" class="form-control form-control-sm" name="cicilan_jatuh_tempo[]" value="${dateStr(tempoDate)}"></td>`;
        container.appendChild(tr);
    }
    updateCicilanTotal();
}

function updateCicilanTotal() {
    const nominals = document.querySelectorAll('.cicilan-nominal');
    const sum      = Array.from(nominals).reduce((s, el) => s + (parseFloat(el.value) || 0), 0);
    const biaya    = parseFloat(document.getElementById('totalBiaya').value) || 0;
    document.getElementById('cicilanTotalCheck').textContent = 'Rp ' + sum.toLocaleString('id-ID');
    const mismatch = Math.abs(sum - biaya) > 1;
    document.getElementById('cicilanMismatchWarning').style.display = mismatch ? '' : 'none';
    const refEl = document.getElementById('cicilanBiayaRef');
    if (refEl) refEl.textContent = biaya.toLocaleString('id-ID');
}

// Sync totalBiaya changes → lunas display + cicilan total
document.getElementById('totalBiaya').addEventListener('input', function() {
    updateLunasDisplay();
    if (document.getElementById('cicilanPanel').style.display !== 'none') {
        updateCicilanTotal();
    }
});

// Auto-initialise payment method to prabayar on page load
selectPaymentMethod('prabayar');

// Conflict check button — add loading state + toast summary
document.getElementById('btnCekSemuaGuru')?.addEventListener('click', function() {
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengecek…';
    runAllConflictChecks();
    setTimeout(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-shield-check me-1"></i>Cek Semua Guru';
        const conflicts = document.querySelectorAll('.conflict-warning-box .text-danger').length;
        if (conflicts > 0) {
            showToast(`Ditemukan ${conflicts} konflik jadwal guru. Periksa panel di bawah.`, 'error');
        } else {
            const checked = document.querySelectorAll('.conflict-warning-box .text-success').length;
            if (checked > 0) showToast('Semua guru tersedia — tidak ada konflik jadwal.', 'success');
        }
    }, 1800);
});

// ── end STEP 4 JS ──

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
