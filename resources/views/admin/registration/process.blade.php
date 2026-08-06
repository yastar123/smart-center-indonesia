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
    .table-responsive {
        overflow-x:auto;
        overflow-y:hidden;
        -webkit-overflow-scrolling: touch;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .table-responsive::-webkit-scrollbar {
        height: 0;
        width: 0;
        display: none;
    }
    .pw-card { background:var(--card-bg); border:1px solid var(--card-border); border-radius:0 0 14px 14px; padding:1.5rem; }
    .pw-course-row { border:1px solid var(--card-border); border-radius:12px; padding:1rem; margin-bottom:.75rem; background:var(--input-bg); }
    .pw-course-row.disabled { opacity:.45; }
    .pw-course-row .course-class-picker { min-width:0; }
    .pw-course-row .teacher-contract-info,
    .pw-course-row .existing-class-detail,
    .pw-course-row .form-text { font-size:.75rem; }
    .pw-course-row .input-group-sm .form-control,
    .pw-course-row .form-select-sm { min-width:0; }
    .pw-course-row .margin-display { min-height:48px; white-space:normal; overflow-wrap:anywhere; word-break:break-word; }
    .pw-course-row .form-control,
    .pw-course-row .form-select,
    .pw-course-row .input-group .form-control,
    .pw-course-row .input-group .input-group-text {
        min-width:0;
        width:100%;
        box-sizing:border-box;
    }
    .pw-course-row .col-md-2,
    .pw-course-row .col-md-1 { min-width:0; }
    .pw-course-row .d-flex.align-items-center.gap-2.flex-wrap > * { min-width:0; }
    .field-label-mobile { display:none; }
    .pw-actions { display:flex; justify-content:space-between; gap:.75rem; margin-top:1.5rem; }
    #packageInfoBox .d-flex { flex-wrap:wrap; gap:.75rem; align-items:flex-start; }
    #packageInfoBox .d-flex > div { min-width:0; }
    #packageInfoBox .d-flex > div:first-child { flex:1 1 280px; }
    #packageInfoBox #pkgInfoNama,
    #packageInfoBox #pkgInfoDetail,
    #packageInfoBox #pkgInfoExtra,
    #packageInfoBox #pkgInfoHarga { min-width:0; }
    #packageInfoBox #pkgInfoDetail,
    #packageInfoBox #pkgInfoExtra { white-space:normal; }
    @media (max-width:767.98px) {
        .pw-course-row .row { align-items:flex-start; }
        .pw-course-row .btn-outline-danger { width:100%; }
        .pw-course-row .d-flex.align-items-center.gap-2.flex-wrap { gap:.65rem; }
        .pw-course-row .form-check { margin-bottom:0; }
        .field-label-mobile { display:block; }
        #packageInfoBox .d-flex { justify-content:flex-start; }
        #packageInfoBox #pkgInfoHarga { width:100%; text-align:left; }
    }
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
        <div class="pw-stepper-item" data-stepper="2"><span class="pw-step-dot">2</span><span>Mapel &amp; Guru</span></div>
        <div class="pw-stepper-item" data-stepper="3"><span class="pw-step-dot">3</span><span>Pembayaran</span></div>
        <div class="pw-stepper-item" data-stepper="4"><span class="pw-step-dot">4</span><span>Preview</span></div>
    </div>

    <form id="processForm" class="pw-card">
        @csrf

        {{-- STEP 1: INFORMASI SISWA + PAKET KELAS --}}
        <div class="pw-panel active" data-step="1">
            <h6 class="fw-bold mb-3"><i class="bi bi-person-vcard me-2 text-primary"></i>Informasi Siswa</h6>
            <p class="text-muted" style="font-size:.8rem">Data ini awalnya diisi siswa saat mendaftar &mdash; admin dapat mengoreksi/melengkapinya jika perlu.</p>

            <div class="row g-3">
                {{-- Tipe Pendaftaran: Siswa Baru vs Siswa Lama --}}
                <div class="col-12">
                    <label class="form-label fw-semibold" style="font-size:.78rem">Tipe Pendaftaran</label>
                    <div class="d-flex gap-2 flex-wrap">
                        <div class="pw-opt-btn active" id="regTypeBaruBtn" onclick="pwSetRegType('baru')">
                            <i class="bi bi-person-plus me-1"></i>Daftar Siswa Baru
                        </div>
                        <div class="pw-opt-btn" id="regTypeLamaBtn" onclick="pwSetRegType('lama')">
                            <i class="bi bi-person-check me-1"></i>Siswa Lama
                        </div>
                    </div>
                    <input type="hidden" name="registration_type" id="registrationTypeInput" value="baru">
                    <div class="form-text" style="font-size:.72rem">Pilih "Siswa Lama" jika siswa sudah punya akun dan hanya mendaftar program/kelas tambahan &mdash; ini mencegah akun & data ganda.</div>
                </div>

                {{-- Pencarian Siswa Lama --}}
                <div class="col-12" id="existingStudentWrapper" style="display:none">
                    <label class="form-label fw-semibold" style="font-size:.78rem">Cari Siswa Lama <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <div class="input-group">
                            <span class="input-group-text" style="background:var(--input-bg);border-color:var(--card-border)"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" class="form-control" id="existingStudentSearch" placeholder="Ketik nama, no HP, atau NIS siswa…" autocomplete="off">
                        </div>
                        <div id="existingStudentResults" class="list-group shadow-sm" style="position:absolute;z-index:20;max-height:220px;overflow:auto;display:none;width:100%;top:100%;left:0"></div>
                    </div>
                    <input type="hidden" name="existing_student_id" id="existingStudentIdInput">

                    {{-- Chip siswa terpilih — muncul setelah memilih dari dropdown --}}
                    <div id="existingStudentChip" style="display:none" class="mt-2">
                        <div class="d-flex align-items-center justify-content-between px-3 py-2"
                             style="background:rgba(200,77,223,.08);border:1.5px solid rgba(200,77,223,.3);border-radius:10px">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-person-check-fill" style="color:#c84ddf;font-size:1rem"></i>
                                <div>
                                    <span class="fw-semibold" id="espChipName" style="font-size:.83rem;color:#461256"></span>
                                    <span class="text-muted ms-1" id="espChipMeta" style="font-size:.72rem"></span>
                                </div>
                            </div>
                            <button type="button" onclick="pwClearExistingStudent()"
                                class="btn btn-sm" style="font-size:.75rem;color:var(--text-muted);border:1px solid var(--card-border);background:var(--input-bg);border-radius:7px;padding:.2rem .6rem">
                                <i class="bi bi-x me-1"></i>Ganti
                            </button>
                        </div>
                        <div class="mt-1" style="font-size:.71rem;color:var(--text-muted)">
                            <i class="bi bi-pencil-square me-1"></i>Data di bawah sudah terisi dari data siswa ini &mdash; edit jika perlu, lalu lanjutkan ke langkah berikutnya.
                        </div>
                    </div>
                </div>

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
                    <select class="form-select" name="program" id="programSelect" onchange="pwToggleLearningLogistics()">
                        <option value="">Pilih…</option>
                        <option value="kelas" {{ $registration->program==='kelas'?'selected':'' }}>Kelas</option>
                        <option value="privat" {{ $registration->program==='privat'?'selected':'' }}>Privat</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Sistem</label>
                    <select class="form-select" name="system" id="systemSelect">
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

                <div class="col-12" id="schoolDataSection">
                    <div class="border rounded-3 p-3" style="background:rgba(200,77,223,.04);border-color:rgba(200,77,223,.16)!important">
                        <div class="fw-semibold mb-2" style="font-size:.82rem;color:#461256"><i class="bi bi-mortarboard me-2"></i>Data Sekolah</div>
                        <div class="row g-3">
                            <div class="col-md-6 school-data-schoolname">
                                <label class="form-label" id="schoolNameLabel" style="font-size:.78rem;color:var(--text-muted)">Nama Sekolah</label>
                                <input type="text" class="form-control" id="schoolNameInput" name="school_name" value="{{ $registration->school_name }}" placeholder="Nama sekolah">
                            </div>
                            <div class="col-md-6 school-data-grade" id="schoolDataGrade">
                                <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Kelas</label>
                                <input type="text" class="form-control" name="grade" value="{{ $registration->grade }}" placeholder="Contoh: Kelas 10 / XI IPA">
                            </div>
                            <div class="col-md-6 school-data-semester d-none" id="schoolDataSemester">
                                <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Semester</label>
                                <input type="text" class="form-control" name="semester" value="{{ $registration->semester ?? '' }}" placeholder="Contoh: Semester 3">
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $interestNames = collect($registration->interests ?? [])->filter(fn ($item) => !empty($item))->values();
                @endphp
                @if($interestNames->isNotEmpty())
                <div class="col-12">
                    <div class="border rounded-3 p-3" style="background:rgba(14,165,233,.04);border-color:rgba(14,165,233,.16)!important">
                        <div class="fw-semibold mb-2" style="font-size:.82rem;color:#0f4c81"><i class="bi bi-bookmark-heart me-2"></i>Program yang Diminati</div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($interestNames as $interest)
                                <span class="badge rounded-pill" style="background:rgba(14,165,233,.12);color:#0f4c81;border:1px solid rgba(14,165,233,.25);padding:.45rem .7rem;font-size:.72rem">
                                    {{ $interest }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- TEMPAT BELAJAR & JADWAL BELAJAR — hanya relevan untuk program Privat --}}
                <div id="learningLogisticsWrapper" class="row g-3">
                    {{-- TEMPAT BELAJAR --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:.78rem">Tempat Belajar</label>
                        @php $curTempat = $registration->learning_place ?? 'kantor'; @endphp
                        <div class="d-flex gap-2 flex-wrap">
                            <div class="pw-opt-btn {{ $curTempat!=='rumah' && $curTempat!=='online' ? 'active' : '' }}" onclick="pickTempat('kantor',this)">
                                <i class="bi bi-building me-1"></i>Belajar di Kantor
                            </div>
                            <div class="pw-opt-btn {{ $curTempat==='rumah' ? 'active' : '' }}" onclick="pickTempat('rumah',this)">
                                <i class="bi bi-house-door me-1"></i>Guru ke Rumah
                            </div>
                            <div class="pw-opt-btn {{ $curTempat==='online' ? 'active' : '' }}" id="onlinePlaceBtn" onclick="pickTempat('online',this)">
                                <i class="bi bi-wifi me-1"></i>Belajar Online
                            </div>
                        </div>
                        <input type="hidden" name="tempat_belajar" id="tempatBelajarInput" value="{{ $curTempat }}">
                    </div>
                    <div class="col-12" id="privateAddressWrapper" style="display:none">
                        <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Alamat Belajar</label>
                        <input type="text" class="form-control" name="private_address" id="privateAddressInput" placeholder="Alamat lengkap tempat guru datang / belajar">
                        <div class="form-text" style="font-size:.72rem">Isi alamat jika tempat belajar dipilih Guru ke Rumah.</div>
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

            </div>
            <div class="mb-4 mt-4 p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                <h6 class="fw-bold mb-3"><i class="bi bi-box-seam me-2 text-primary"></i>Paket Kelas</h6>

                {{-- TOGGLE PAKET STANDAR / REQUEST --}}
                <input type="hidden" name="is_custom_package" id="isCustomPackage" value="0">
                <input type="hidden" name="package_mode" id="packageModeInput" value="standard">
                <div class="d-flex gap-2 mb-3 flex-wrap">
                    <button type="button" id="btnStandard" onclick="switchPackage('standard')" class="btn btn-sm btn-primary flex-fill">
                        <i class="bi bi-box-seam me-1"></i>Paket Standar
                    </button>
                    <button type="button" id="btnRequest" onclick="switchPackage('request')" class="btn btn-sm btn-outline-secondary flex-fill">
                        <i class="bi bi-chat-square-text me-1"></i>Paket Request
                    </button>
                </div>
                <div class="mb-3">
                    <div id="packagePanelTitle" class="fw-semibold" style="font-size:.92rem">Pilih Paket Belajar</div>
                    <div id="packagePanelHint" class="text-muted" style="font-size:.8rem">Pilih paket standar atau ajukan request paket baru jika tidak ada paket yang cocok.</div>
                </div>

                {{-- PAKET STANDAR --}}
                <div id="standardPackage">
                    <p class="text-muted" style="font-size:.83rem">Pilih paket belajar untuk siswa ini (opsional — bisa dilewati jika belum ada paket yang cocok).</p>
                    <div id="freePackageNote" class="alert alert-info d-none" style="font-size:.82rem;">
                        <i class="bi bi-info-circle me-1"></i>Pilih Paket Bebas jika ingin menyusun tarif dan sesi secara manual tanpa paket master.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.78rem">Paket Kelas</label>
                        <select name="package_id" id="packageDropdown" class="form-select">
                            <option value="" data-harga="0">— Tanpa Paket (susun manual per mata pelajaran) —</option>
                            @foreach($packages as $pkg)
                            <option value="{{ $pkg->id }}" data-cabang="{{ $pkg->cabang_id }}" data-harga="{{ $pkg->harga }}" data-jumlah="{{ $pkg->jumlah_pertemuan ?? 0 }}">
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
                                <div class="text-muted" style="font-size:.75rem" id="pkgInfoExtra">–</div>
                            </div>
                            <div class="fw-bold text-primary fs-6" id="pkgInfoHarga">–</div>
                        </div>
                    </div>
                </div>

                <div id="customPackage" style="display:none" class="border rounded-3 p-3 mt-3" >
                    <p class="text-muted" style="font-size:.82rem">Isi detail Paket Request untuk dibuat sebagai paket kelas baru di data master.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.78rem">Nama Paket</label>
                            <input type="text" class="form-control" name="custom_package_name" placeholder="Nama paket untuk request">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.78rem">Jenis Paket</label>
                            <select class="form-select" name="custom_jenis">
                                <option value="">Pilih…</option>
                                <option value="reguler">Reguler</option>
                                <option value="intensif">Intensif</option>
                                <option value="privat">Privat</option>
                                <option value="online">Online</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.78rem">Jumlah Pertemuan</label>
                            <input type="number" min="1" class="form-control" name="jumlah_pertemuan" value="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.78rem">Harga Paket (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" min="0" class="form-control" name="custom_package_price" id="customPackagePrice" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.78rem">Status Paket</label>
                            <input type="text" class="form-control" name="custom_status" value="aktif" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size:.78rem">Keterangan Paket</label>
                            <textarea class="form-control" name="custom_deskripsi" rows="3" placeholder="Catatan tambahan untuk paket request"></textarea>
                        </div>
                        <input type="hidden" name="custom_metode_absensi" value="manual">
                        <input type="hidden" name="custom_tipe_kelas" value="offline">
                    </div>
                </div>
            </div>
            <div class="pw-actions"><span></span><button type="button" class="btn btn-primary" data-action="next">Lanjut<i class="bi bi-arrow-right ms-1"></i></button></div>
        </div>

        {{-- STEP 2: KELOLA KELAS --}}
        <div class="pw-panel" data-step="2">
            <h6 class="fw-bold mb-3"><i class="bi bi-journal-bookmark me-2 text-primary"></i>Kelola Kelas, Guru &amp; Jadwal</h6>
            @if($courses->isEmpty())
            <div class="alert alert-warning" style="font-size:.85rem"><i class="bi bi-exclamation-triangle me-2"></i>Tidak ditemukan mata pelajaran yang cocok dengan minat pendaftaran ini di data master. Hubungi bagian akademik untuk melengkapi data mata pelajaran.</div>
            @else
            <p class="text-muted mb-3" style="font-size:.83rem">Centang mata pelajaran yang akan diambil siswa, tentukan guru &amp; jumlah sesi, lalu isi jadwal kelas untuk pengecekan konflik guru.</p>
            @endif

            {{-- CARD A: KELOLA KELAS --}}
            <div class="mb-3" style="border:1px solid var(--card-border);border-radius:14px;overflow:hidden">
                <div class="px-3 py-2 d-flex align-items-center gap-2" style="background:linear-gradient(90deg,rgba(70,18,86,.07),transparent);border-bottom:1px solid var(--card-border)">
                    <i class="bi bi-journal-bookmark text-primary"></i>
                    <span class="fw-bold" style="font-size:.85rem">Kelola Kelas</span>
                </div>
                <div class="p-3">
                    <div class="d-flex gap-2 flex-wrap mb-3">
                        <button type="button" id="kelolaNewBtn" class="btn btn-sm btn-primary active" onclick="setKelolaKelasMode('new')">Buat Kelas Baru</button>
                        <button type="button" id="kelolaJoinBtn" class="btn btn-sm btn-outline-secondary" onclick="setKelolaKelasMode('join')">Gabung Kelas</button>
                    </div>

                    <div id="kelolaNewPanel">
                        <div id="courseRowsContainer" class="d-grid gap-3">
                            @foreach($courses as $course)
                            @php
                                $fee     = $course->fee->amount ?? 0;
                                $sesiDef = $registration->interest_sessions[$course->nama] ?? 8;
                                $honor   = round(($fee * 0.6) / max($sesiDef, 1));
                            @endphp
                            <div class="pw-course-row" data-course-row="{{ $course->id }}" data-default-fee="{{ $fee }}" data-default-honor="{{ $honor }}">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input course-check" type="checkbox" name="course_ids[]" value="{{ $course->id }}" id="course{{ $course->id }}" checked>
                                            <label class="form-check-label fw-semibold" for="course{{ $course->id }}" style="font-size:.92rem">{{ $course->nama }}</label>
                                        </div>
                                        <div class="form-text" style="font-size:.78rem">Mapel pilihan siswa</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" style="font-size:.78rem">Guru Pengajar</label>
                                        <select class="form-select" name="course_teacher[{{ $course->id }}]" data-course-id="{{ $course->id }}">
                                            <option value="">Pilih guru…</option>
                                            @foreach($course->guru as $t)
                                            <option value="{{ $t->id }}" data-jenis-guru="{{ $t->jenis_guru ?? '' }}" data-salary-base="{{ (float)($t->salary_base ?? 0) }}">
                                                {{ $t->name }}@if($t->jenis_guru)
                                                    • {{ ucfirst($t->jenis_guru) }}@if($t->jenis_guru === 'kontrak' && (float)($t->salary_base ?? 0) > 0)
                                                        • Gaji: Rp {{ number_format((float)($t->salary_base ?? 0), 0, ',', '.') }}
                                                    @endif
                                                @endif
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" style="font-size:.78rem">Sesi</label>
                                        <input type="number" min="1" class="form-control" name="course_sessions[{{ $course->id }}]" placeholder="Sesi" value="{{ $registration->interest_sessions[$course->nama] ?? 8 }}" oninput="updateRowMargin(this)">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" style="font-size:.78rem">Modul</label>
                                        <select class="form-select" name="course_module[{{ $course->id }}][]" multiple style="min-height:120px">
                                            @foreach($course->modul as $modul)
                                            <option value="{{ $modul->id }}">{{ $modul->judul }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text" style="font-size:.74rem">Pilih satu atau lebih modul.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" style="font-size:.78rem">Kelas Aktif</label>
                                        @php $klassOptions = $activeClassesByCourse[$course->id] ?? collect(); @endphp
                                        <select class="form-select" name="course_class[{{ $course->id }}]" data-course-id="{{ $course->id }}">
                                            <option value="">Pilih kelas aktif yang sedang berlangsung…</option>
                                            @foreach($klassOptions as $klass)
                                            <option value="{{ $klass->id }}" data-course-id="{{ $course->id }}" data-class-name="{{ $klass->nama_kelas }}" data-guru-name="{{ $klass->guru?->name ?? '—' }}" data-student-count="{{ $klass->siswa->count() }}" data-student-names="{{ implode(' | ', $klass->siswa->pluck('name')->filter()->all()) }}" data-schedule-count="{{ $klass->jadwal->count() }}" data-class-type="{{ $klass->jenis ?? '-' }}">
                                                {{ $klass->nama_kelas }} ({{ $klass->siswa->count() }} siswa)
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="existing-class-detail mt-1 text-muted" style="font-size:.75rem">Pilih kelas aktif atau biarkan kosong untuk buat kelas baru.</div>
                                    </div>

                                    <div class="col-12 text-end">
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCourseRow(this, {{ $course->id }})" title="Hapus mapel ini"><i class="bi bi-trash"></i> Hapus</button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div id="kelolaJoinPanel" style="display:none">
                        <div class="mb-3">
                            <p class="text-muted mb-2" style="font-size:.83rem">Pilih kelas aktif yang sedang berlangsung untuk memasukkan siswa ke kelas tersebut. Tabel ini menampilkan semua kelas aktif di cabang yang sesuai.</p>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered align-middle mb-0" style="font-size:.83rem;min-width:880px">
                                    <thead>
                                        <tr style="background:var(--input-bg)">
                                            <th style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700">Nama Paket</th>
                                            <th style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700">Guru</th>
                                            <th style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700">Mapel</th>
                                            <th class="text-center" style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700">Jumlah Siswa</th>
                                            <th style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700">Jadwal</th>
                                            <th class="text-center" style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($activeClasses as $klass)
                                        @php
                                            $scheduleDetails = $klass->jadwal->map(function ($jadwal) {
                                                $dateLabel = optional($jadwal->tanggal)->format('d M Y');
                                                $timeLabel = trim(($jadwal->jam_mulai ?? '') . ' - ' . ($jadwal->jam_selesai ?? ''));
                                                return trim(($dateLabel ? $dateLabel . ' ' : '') . $timeLabel);
                                            })->filter()->values()->all();
                                        @endphp
                                        <tr>
                                            <td>{{ $klass->nama_kelas ?? '—' }} @if($klass->jenis) <span class="text-muted" style="font-size:.75rem">({{ ucfirst($klass->jenis) }})</span>@endif</td>
                                            <td>{{ $klass->guru?->name ?? '—' }}</td>
                                            <td>{{ $klass->mataPelajaran?->nama ?? '—' }}</td>
                                            <td class="text-center">{{ $klass->siswa->count() }}</td>
                                            <td style="min-width:220px">{{ $scheduleDetails ? implode(' | ', $scheduleDetails) : '—' }}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-primary">Gabung</button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted" style="font-size:.82rem">Tidak ada kelas aktif yang tersedia.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
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

                </div>
            </div>

            {{-- CARD B: JADWAL KELAS --}}
            <div id="scheduleCard" class="mb-3" style="border:1px solid var(--card-border);border-radius:14px;overflow:hidden">
                <div class="px-3 py-2 d-flex align-items-center gap-2" style="background:linear-gradient(90deg,rgba(70,18,86,.07),transparent);border-bottom:1px solid var(--card-border)">
                    <i class="bi bi-calendar-week text-primary"></i>
                    <span class="fw-bold" style="font-size:.85rem">Jadwal Kelas</span>
                    <span class="ms-1 text-muted" style="font-size:.75rem">&mdash; opsional</span>
                </div>
                <div class="p-3">
                    <p class="text-muted mb-3" style="font-size:.82rem">Isi hari, jam, dan ruang/media untuk setiap mata pelajaran. Guru ditampilkan otomatis sesuai pilihan di tabel atas. Pengisian ini <strong>tidak wajib</strong> dan tidak memblokir submit.</p>
                    <div id="scheduleTableWrapper" class="table-responsive">
                        <table class="table table-sm table-bordered align-middle mb-0" style="font-size:.83rem;min-width:860px">
                            <thead>
                                <tr style="background:var(--input-bg)">
                                    <th style="width:40px;font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700">No</th>
                                    <th style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700">Mata Pelajaran</th>
                                    <th style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700">Hari</th>
                                    <th style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700">Jam Mulai</th>
                                    <th style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700">Jam Berakhir</th>
                                    <th class="room-column-header" style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700">Ruang / Media</th>
                                    <th style="width:70px;font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="scheduleRowsContainer">
                                @foreach($courses as $course)
                                <tr class="pw-sched-tr" data-schedule-row="{{ $course->id }}">
                                    <td class="text-center text-muted sched-no" style="font-size:.78rem">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="badge rounded-pill" style="background:rgba(200,77,223,.12);color:#461256;font-size:.75rem;font-weight:600;padding:.3em .75em">{{ $course->nama }}</span>
                                    </td>
                                    <td colspan="5" class="p-0">
                                        <div class="schedule-slot-list" data-course-id="{{ $course->id }}">
                                            <div class="schedule-slot-row border-bottom p-2" data-slot-index="0">
                                                <div class="row g-2 align-items-end">
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-semibold" style="font-size:.72rem">Hari</label>
                                                        <select class="form-select form-select-sm hari-select" name="schedule_hari[{{ $course->id }}][0]" data-course-id="{{ $course->id }}" style="min-width:88px">
                                                            <option value="">Pilih…</option>
                                                            <option value="1">Senin</option>
                                                            <option value="2">Selasa</option>
                                                            <option value="3">Rabu</option>
                                                            <option value="4">Kamis</option>
                                                            <option value="5">Jum'at</option>
                                                            <option value="6">Sabtu</option>
                                                            <option value="0">Minggu</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label fw-semibold" style="font-size:.72rem">Mulai</label>
                                                        <input type="time" class="form-control form-control-sm jam-mulai-input" name="schedule_jam_mulai[{{ $course->id }}][0]" data-course-id="{{ $course->id }}" value="08:00" style="min-width:100px">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label fw-semibold" style="font-size:.72rem">Selesai</label>
                                                        <input type="time" class="form-control form-control-sm jam-selesai-input" name="schedule_jam_selesai[{{ $course->id }}][0]" data-course-id="{{ $course->id }}" value="10:00" style="min-width:100px">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-semibold" style="font-size:.72rem">Ruang / Media</label>
                                                        <div class="room-field">
                                                            <select class="form-select form-select-sm room-select" name="schedule_room[{{ $course->id }}][0]" data-course-id="{{ $course->id }}" style="min-width:140px">
                                                                <option value="">— Pilih ruang —</option>
                                                                @foreach($rooms as $room)
                                                                <option value="{{ $room->id }}">{{ $room->nama_ruangan }}{{ $room->kapasitas ? ' ('.$room->kapasitas.' org)' : '' }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="meeting-field" style="display:none">
                                                            <input type="url" class="form-control form-control-sm meeting-input" name="schedule_link_meeting[{{ $course->id }}][0]" placeholder="Link meeting" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 text-end">
                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeScheduleSlot(this)"><i class="bi bi-trash"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addScheduleSlot(this, {{ $course->id }})"><i class="bi bi-plus-circle me-1"></i>Tambah slot jadwal</button>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCourseScheduleRow(this, {{ $course->id }})" title="Hapus semua slot jadwal mapel ini"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="scheduleEmptyMsg" class="text-muted mt-2" style="font-size:.8rem;display:none"><i class="bi bi-info-circle me-1"></i>Tidak ada mata pelajaran yang dipilih.</div>
                </div>
            </div>

            <div id="conflictCardWrapper" class="mb-3" style="border:1px solid rgba(246,175,35,.4);border-radius:14px;overflow:hidden">
                <div class="px-3 py-2 d-flex align-items-center justify-content-between gap-2 flex-wrap" style="background:linear-gradient(90deg,rgba(246,175,35,.08),transparent);border-bottom:1px solid rgba(246,175,35,.3)">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-search" style="color:#e09000"></i>
                        <span class="fw-bold" style="font-size:.85rem">Cek Konflik Jadwal Guru</span>
                    </div>
                    <button type="button" id="btnCekSemuaGuru" class="btn btn-sm" style="background:rgba(246,175,35,.15);color:#8a5e00;border:1px solid rgba(246,175,35,.5);border-radius:10px" onclick="handleCheckAllGuruClick(event)">
                        <i class="bi bi-search me-1"></i>Cek Semua Guru
                    </button>
                </div>
                <div class="p-3">
                    <p class="text-muted mb-3" style="font-size:.82rem">Klik <strong>Cek Semua Guru</strong> untuk memeriksa apakah guru yang dipilih memiliki jadwal lain yang bentrok. Panel ini juga menampilkan kelas aktif yang bisa dipakai siswa, lengkap dengan guru, jumlah siswa, dan sisa sesi kelas.</p>
                    <div id="conflictResultsPanel">
                        @foreach($courses as $course)
                        @php
                            $activeClassSummaries = ($activeClassesByCourse[$course->id] ?? collect())->map(function ($klass) {
                                return [
                                    'id' => $klass->id,
                                    'nama_kelas' => $klass->nama_kelas,
                                    'guru_name' => $klass->guru?->name ?? '—',
                                    'siswa_count' => $klass->siswa->count(),
                                    'student_names' => $klass->siswa->pluck('name')->filter()->values()->all(),
                                    'total_sessions' => (int) ($klass->jumlah_pertemuan ?? 0),
                                    'scheduled_sessions' => $klass->jadwal->count(),
                                    'jenis' => $klass->jenis ?? '—',
                                ];
                            })->values();
                        @endphp
                        <div class="conflict-card mb-2 p-2 rounded-3" id="conflict-result-{{ $course->id }}" data-course-id="{{ $course->id }}" data-active-classes='@json($activeClassSummaries)' style="background:var(--input-bg)">
                            <div class="d-flex align-items-start gap-2">
                                <span class="badge rounded-pill flex-shrink-0" style="background:rgba(200,77,223,.12);color:#461256;font-size:.74rem;font-weight:600;padding:.3em .7em;min-width:80px;text-align:center">{{ $course->nama }}</span>
                                <div class="flex-grow-1">
                                    <div class="conflict-warning-box text-muted" data-course-id="{{ $course->id }}" style="font-size:.8rem">—</div>
                                <div class="teacher-schedule-list mt-2 text-muted" data-course-id="{{ $course->id }}" style="font-size:.78rem">Pilih guru pengajar untuk menampilkan jadwalnya.</div>
                                <div class="active-class-summary mt-2"></div>
                                </div>
                            </div>
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

        {{-- STEP 3: PEMBAYARAN --}}
        <div class="pw-panel" data-step="3">
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

            <div class="mb-4 p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <div>
                        <label class="form-label fw-semibold mb-1" style="font-size:.78rem">Rincian Keuangan Per Mapel</label>
                        <div class="form-text" style="font-size:.72rem">Isi biaya siswa dan honor guru untuk setiap mapel terpilih. Nilai ini akan ikut ke preview dan invoice.</div>
                    </div>
                    <span class="badge rounded-pill" style="background:rgba(200,77,223,.12);color:#461256" id="financeSelectedCount">0 mapel</span>
                </div>
                <div id="financeCourseRows" class="d-grid gap-2"></div>
                <div class="row g-3 mt-2">
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

        {{-- STEP 4: PREVIEW --}}
        <div class="pw-panel" data-step="4">
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
        <h5 class="fw-bold mt-3 mb-1" id="successTitle">Akun Siswa Berhasil Dibuat</h5>
        <p class="text-muted" style="font-size:.85rem" id="successSubtitle">Kirim informasi akun ini ke WhatsApp siswa agar bisa langsung login.</p>
        <div class="mx-auto text-start p-3 rounded-3 mt-3" style="max-width:420px;background:var(--input-bg);border:1px solid var(--card-border)">
            <div class="d-flex justify-content-between align-items-center mb-2"><span class="text-muted" style="font-size:.78rem">Nama</span><strong id="cred-name">–</strong></div>
            <div class="d-flex justify-content-between align-items-center mb-2"><span class="text-muted" style="font-size:.78rem">Email</span><code id="cred-email">–</code></div>
            <div class="d-flex justify-content-between align-items-center" id="cred-password-row"><span class="text-muted" style="font-size:.78rem">Password</span><code id="cred-password">–</code></div>
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
const _processUrl         = "{{ route('admin.registration-list.process.store', $registration->id) }}";
const _csrf               = "{{ csrf_token() }}";
const _studentSearchUrl   = "{{ route('admin.registration-list.student-search') }}";
const _studentDetailBase  = "{{ url('admin/registration-list/student-detail') }}";
const _studentUpdateBase  = "{{ url('admin/registration-list/student-update') }}";
let _credData = {};

function showStep(step) {
    const stepNum = Number(step);
    document.querySelectorAll('.pw-panel').forEach(panel => {
        panel.classList.toggle('active', Number(panel.dataset.step) === stepNum);
    });
    document.querySelectorAll('.pw-stepper-item').forEach((item, i) => {
        const current = i + 1;
        item.classList.toggle('active', current === stepNum);
        item.classList.toggle('completed', current < stepNum);
    });
    if (stepNum === 2) populateMapelGuruFromPreviousSteps();
    if (stepNum === 3) {
        recalcTotal();
        selectPaymentMethod(document.getElementById('paymentMethodInput')?.value || 'prabayar');
    }
    if (stepNum === 4) {
        buildPreview();
    }
}

document.querySelectorAll('[data-action="next"]').forEach(btn => {
    btn.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();
        const current = document.querySelector('.pw-panel.active');
        const next = parseInt(current?.dataset.step || '0', 10) + 1;
        if (current && current.dataset.step === '1' && next === 2) {
            try { populatePackageFieldsFromStudentInfo(); } catch (e) {}
        }
        if (current && current.dataset.step === '2' && !validateStep2()) {
            return;
        }
        if (next <= 4) {
            const activeForm = document.getElementById('processForm');
            if (activeForm) {
                activeForm.querySelectorAll('input, select, textarea').forEach(el => {
                    if (el.hasAttribute('required')) {
                        el.removeAttribute('required');
                    }
                });
            }
            try { showStep(next); } catch (e) {}
        }
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
    const visible = document.querySelectorAll('.pw-sched-tr:not([style*="display:none"]):not([style*="display: none"])');
    const scheduleWrapper = document.getElementById('scheduleTableWrapper');
    const emptyMsg = document.getElementById('scheduleEmptyMsg');
    if (visible.length === 0) {
        if (scheduleWrapper) scheduleWrapper.style.display = 'none';
        if (emptyMsg) emptyMsg.style.display = '';
    } else {
        if (scheduleWrapper) scheduleWrapper.style.display = '';
        if (emptyMsg) emptyMsg.style.display = 'none';
    }
}

function recalcTotal() {
    let total = 0, totalHonor = 0, totalSesi = 0;
    document.querySelectorAll('.pw-course-row').forEach(row => {
        const chk = row.querySelector('.course-check');
        if (!chk) return;
        row.classList.toggle('disabled', !chk.checked);
        row.querySelectorAll('select, input').forEach(el => { if (el !== chk) el.disabled = !chk.checked; });
        const schedRow = document.querySelector(`.pw-sched-tr[data-schedule-row="${row.dataset.courseRow}"]`);
        if (schedRow) schedRow.style.display = chk.checked ? '' : 'none';
        if (chk.checked) {
            const courseId = row.dataset.courseRow;
            const financeRow = document.querySelector(`#financeCourseRows .finance-row[data-course-id="${courseId}"]`);
            const feeInput   = financeRow?.querySelector('.fee-input');
            const honorInput = financeRow?.querySelector('.honor-input');
            const honorToggle = financeRow?.querySelector('.course-use-honor');
            const sesiInput  = row.querySelector('input[name^="course_sessions"]');
            const sesi       = parseInt(sesiInput?.value || 0, 10);
            const fee        = parseFloat(feeInput?.value || 0);
            const honorPerSesi = parseFloat(honorInput?.value || 0);
            total += fee;
            const useHonor = !!(honorToggle && honorToggle.checked);
            totalHonor += useHonor ? (honorPerSesi * sesi) : 0;
            totalSesi += sesi;
        }
    });
    const totalBiayaEl = document.getElementById('totalBiaya');
    if (totalBiayaEl) totalBiayaEl.value = total || 0;
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
    if (document.getElementById('financeSelectedCount')) {
        const selected = document.querySelectorAll('#financeCourseRows .finance-row').length;
        document.getElementById('financeSelectedCount').textContent = selected + ' mapel';
    }
    updateScheduleEmpty();
    renumberScheduleRows();
}

function updateRowMargin(input) {
    const financeRow = input.closest('.finance-row');
    const courseRow = input.closest('.pw-course-row');
    const host = financeRow || courseRow;
    if (!host) return;
    const courseId = host.dataset.courseId || courseRow?.dataset.courseRow;
    const financeRowTarget = courseId ? document.querySelector(`#financeCourseRows .finance-row[data-course-id="${courseId}"]`) : null;
    const activeHost = financeRowTarget || host;
    const fee = parseFloat(activeHost.querySelector('.fee-input')?.value || 0);
    const honorInput = activeHost.querySelector('.honor-input');
    const honorToggle = activeHost.querySelector('.course-use-honor');
    const courseRowTarget = courseId ? document.querySelector(`.pw-course-row[data-course-row="${courseId}"]`) : courseRow;
    const sesi = parseInt(courseRowTarget?.querySelector('input[name^="course_sessions"]')?.value || 0, 10);
    const useHonor = !!(honorToggle && honorToggle.checked);
    const margin = fee - (useHonor ? (parseFloat(honorInput?.value || 0) * sesi) : 0);
    const pct    = fee > 0 ? Math.round((margin / fee) * 100) : 0;
    const display = activeHost.querySelector('.margin-display');
    if (display) {
        display.innerHTML = '<div class="fw-semibold" style="color:' + (margin >= 0 ? '#10b981' : '#dc2626') + '">Rp' + margin.toLocaleString('id-ID') + '</div>' +
            '<div class="text-muted" style="font-size:.78rem">(' + pct + '%)</div>';
        display.style.background = margin >= 0 ? 'rgba(16,185,129,.07)' : 'rgba(220,38,38,.07)';
        display.style.borderColor = margin >= 0 ? 'rgba(16,185,129,.2)' : 'rgba(220,38,38,.2)';
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
    if (!sel) return;
    const chosen = sel.selectedOptions?.[0] || sel.options[sel.selectedIndex];
    const guruText = chosen && chosen.value ? chosen.textContent.trim() : '—';
    const spans = document.querySelectorAll(`.sched-guru-name[data-course-id="${courseId}"]`);
    if (!spans.length) return;
    spans.forEach(span => span.textContent = guruText || '—');
}

function syncAllGuruNames() {
    document.querySelectorAll('.guru-select[data-course-id]').forEach(el => {
        const courseId = el.dataset.courseId;
        if (courseId) syncGuruName(courseId);
    });
}

function renderTeacherSchedule(courseId, schedules, guruText) {
    const card = document.getElementById('conflict-result-' + courseId);
    if (!card) return;
    const work = card.querySelector('.teacher-schedule-list');
    if (!work) return;
    const teacherLabel = guruText ? guruText : 'Guru belum dipilih';
    if (!schedules || !schedules.length) {
        work.innerHTML = '<div><strong>Jadwal guru:</strong> ' + teacherLabel + '</div>' +
            '<div class="text-muted" style="font-size:.78rem">Belum ada jadwal terdaftar untuk guru ini.</div>';
        return;
    }
    work.innerHTML = '<div><strong>Jadwal guru:</strong> ' + teacherLabel + '</div>' +
        schedules.map(item => {
            const tanggal = item.tanggal ? item.tanggal + ' • ' + item.hari : item.hari || 'Tanpa tanggal';
            const subjek = item.mata_pelajaran || 'Mata pelajaran tidak tersedia';
            const kelas = item.kelas ? ' • ' + item.kelas : '';
            const topik = item.topik ? ' • ' + item.topik : '';
            return `<div class="border rounded-2 p-2 mt-2" style="background:rgba(248,250,252,.9);font-size:.78rem">` +
                `<div><strong>${tanggal}</strong></div>` +
                `<div>${item.jam_mulai}–${item.jam_selesai}${kelas}</div>` +
                `<div>${subjek}${topik}</div>` +
                `</div>`;
        }).join('');
}

// ── Renumber visible schedule rows ─────────────────────────────────────────────
function renumberScheduleRows() {
    let n = 1;
    document.querySelectorAll('#scheduleRowsContainer .pw-sched-tr').forEach(tr => {
        const noCell = tr.querySelector('.sched-no');
        if (tr.style.display === 'none') return;
        if (noCell) noCell.textContent = n++;
    });
}

function addScheduleSlot(btn, courseId) {
    const list = btn.closest('.pw-sched-tr')?.querySelector('.schedule-slot-list');
    if (!list) return;
    const index = list.querySelectorAll('.schedule-slot-row').length;
    const row = document.createElement('div');
    row.className = 'schedule-slot-row border-top p-2';
    row.dataset.slotIndex = index;
    row.innerHTML = `
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold" style="font-size:.72rem">Hari</label>
                <select class="form-select form-select-sm hari-select" name="schedule_hari[${courseId}][${index}]" data-course-id="${courseId}" style="min-width:88px">
                    <option value="">Pilih…</option>
                    <option value="1">Senin</option><option value="2">Selasa</option><option value="3">Rabu</option>
                    <option value="4">Kamis</option><option value="5">Jum'at</option><option value="6">Sabtu</option><option value="0">Minggu</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold" style="font-size:.72rem">Mulai</label>
                <input type="time" class="form-control form-control-sm jam-mulai-input" name="schedule_jam_mulai[${courseId}][${index}]" data-course-id="${courseId}" value="08:00" style="min-width:100px">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold" style="font-size:.72rem">Selesai</label>
                <input type="time" class="form-control form-control-sm jam-selesai-input" name="schedule_jam_selesai[${courseId}][${index}]" data-course-id="${courseId}" value="10:00" style="min-width:100px">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold" style="font-size:.72rem">Ruang / Media</label>
                <div class="room-field">
                    <select class="form-select form-select-sm room-select" name="schedule_room[${courseId}][${index}]" data-course-id="${courseId}" style="min-width:140px">${_roomOptions}</select>
                </div>
                <div class="meeting-field" style="display:none">
                    <input type="url" class="form-control form-control-sm meeting-input" name="schedule_link_meeting[${courseId}][${index}]" placeholder="Link meeting" autocomplete="off">
                </div>
            </div>
            <div class="col-md-2 text-end">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeScheduleSlot(this)"><i class="bi bi-trash"></i></button>
            </div>
        </div>`;
    list.appendChild(row);
    bindScheduleSlotEvents(row);
}

function removeScheduleSlot(btn) {
    const row = btn.closest('.schedule-slot-row');
    if (!row) return;
    row.remove();
}

function removeCourseScheduleRow(btn, courseId) {
    const row = btn.closest('.pw-sched-tr');
    if (!row) return;
    row.remove();
    renumberScheduleRows();
}

function bindScheduleSlotEvents(slotRow) {
    const courseId = slotRow.closest('.pw-sched-tr')?.dataset.scheduleRow;
    const guruSelect = document.querySelector(`.guru-select[data-course-id="${courseId}"]`);
    const slotInputs = slotRow.querySelectorAll('.hari-select, .jam-mulai-input, .jam-selesai-input');
    slotInputs.forEach(el => {
        el.addEventListener('change', () => {
            if (guruSelect) runConflictCheck(courseId);
        });
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
    const guruText = guruSelect?.selectedOptions?.[0]?.textContent.trim() || '—';
    if (!guruId) {
        warningBox.innerHTML = '<span class="text-muted" style="font-size:.78rem">Pilih guru terlebih dahulu untuk melihat jadwal.</span>';
        renderTeacherSchedule(courseId, [], guruText);
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
        if (data.conflict) {
            warningBox.innerHTML = `<span class="text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>${data.detail}</span>`;
        } else if (hari === '' || !mulai || !selesai) {
            warningBox.innerHTML = '<span class="text-muted" style="font-size:.78rem">Pilih hari & jam untuk memeriksa bentrok. Jadwal guru tetap ditampilkan di bawah.</span>';
        } else {
            warningBox.innerHTML = '<span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Guru tersedia.</span>';
        }
        renderTeacherSchedule(courseId, data.schedules || [], guruText);
    })
    .catch(() => {
        warningBox.innerHTML = '<span class="text-muted">Gagal mengecek.</span>';
        renderTeacherSchedule(courseId, [], guruText);
    });
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

function applyTeacherContractMeta(selectEl) {
    const row = selectEl.closest('.pw-course-row');
    if (!row) return;
    const infoBox = row.querySelector('.teacher-contract-info');
    const honorInput = row.querySelector('.honor-input');
    const honorToggle = row.querySelector('.course-use-honor');
    const honorHelp = row.querySelector('.honor-help');
    const sesiInput = row.querySelector('input[name^="course_sessions"]');
    const selected = selectEl.selectedOptions[0];
    const jenisGuru = (selected?.dataset.jenisGuru || '').toLowerCase();
    const salaryBase = parseFloat(selected?.dataset.salaryBase || 0);
    const sesi = Math.max(parseInt(sesiInput?.value || 8, 10), 1);
    const isContract = jenisGuru === 'kontrak' && salaryBase > 0;

    if (infoBox) {
        if (isContract) {
            infoBox.innerHTML = '<span class="badge rounded-pill me-1" style="background:rgba(14,165,233,.10);color:#0369a1;border:1px solid rgba(14,165,233,.35);font-size:.66rem;padding:.25em .6em">Jenis: Kontrak</span>' +
                '<span class="badge rounded-pill" style="background:rgba(16,185,129,.10);color:#047857;border:1px solid rgba(16,185,129,.35);font-size:.66rem;padding:.25em .6em">Gaji Bulanan: Rp ' + Number(salaryBase).toLocaleString('id-ID') + '</span>';
        } else if (jenisGuru === 'freelance') {
            infoBox.innerHTML = '<span class="badge rounded-pill" style="background:rgba(246,175,35,.12);color:#8a5e00;border:1px solid rgba(246,175,35,.35);font-size:.66rem;padding:.25em .6em">Jenis: Freelance</span>';
        } else {
            infoBox.textContent = '';
        }
    }

    if (honorToggle) {
        honorToggle.disabled = !isContract;
        if (!isContract) honorToggle.checked = false;
    }

    if (honorInput) {
        if (isContract) {
            honorInput.disabled = !honorToggle?.checked;
            if (!honorInput.value || parseFloat(honorInput.value) <= 0) {
                honorInput.value = Math.round(salaryBase / sesi);
            }
        } else {
            honorInput.disabled = true;
        }
    }

    if (honorHelp) {
        honorHelp.style.display = isContract ? '' : 'none';
    }

    updateContractAddition(row);
    recalcTotal();
}

function updateContractAddition(row) {
    if (!row) return;
    const sel = row.querySelector('.guru-select');
    const honorInput = row.querySelector('.honor-input');
    const honorToggle = row.querySelector('.course-use-honor');
    const sesiInput = row.querySelector('input[name^="course_sessions"]');
    const addBoxClass = 'contract-salary-add';
    let addBox = row.querySelector('.' + addBoxClass);
    const selected = sel && sel.selectedOptions ? sel.selectedOptions[0] : null;
    const jenisGuru = (selected?.dataset.jenisGuru || '').toLowerCase();
    const salaryBase = parseFloat(selected?.dataset.salaryBase || 0);
    const honorPerSesi = parseFloat(honorInput?.value || 0);
    const sesi = Math.max(parseInt(sesiInput?.value || 0, 10), 0);
    const useHonor = !!(honorToggle && honorToggle.checked);
    const tambahan = useHonor ? (honorPerSesi * sesi) : 0;

    if (jenisGuru === 'kontrak' && salaryBase > 0 && useHonor && tambahan > 0) {
        if (!addBox) {
            addBox = document.createElement('div');
            addBox.className = addBoxClass + ' mt-2 text-muted';
            addBox.style.fontSize = '.68rem';
            const infoContainer = row.querySelector('.teacher-contract-info');
            if (infoContainer) infoContainer.insertAdjacentElement('afterend', addBox);
        }
        addBox.innerHTML = '<strong>Tambahan ke gaji bulanan:</strong> Rp ' + Number(tambahan).toLocaleString('id-ID');
    } else if (addBox) {
        addBox.remove();
    }
}

function syncExistingClassDetail(selectEl) {
    const row = selectEl.closest('.pw-course-row');
    const detailBox = row?.querySelector('.existing-class-detail');
    if (!detailBox) return;
    const selected = selectEl.selectedOptions[0];
    if (!selected || !selected.value) {
        detailBox.innerHTML = '<span class="text-muted">Tidak ada kelas aktif dipilih. Sistem akan membuat kelas baru untuk mata pelajaran ini.</span>';
        return;
    }

    const className = selected.dataset.className || 'Kelas Aktif';
    const guruName = selected.dataset.guruName || '—';
    const studentCount = selected.dataset.studentCount || '0';
    const studentNames = (selected.dataset.studentNames || '').trim();
    const scheduleCount = selected.dataset.scheduleCount || '0';
    const classType = selected.dataset.classType || '—';
    detailBox.innerHTML = '<span class="badge rounded-pill" style="background:rgba(16,185,129,.10);color:#047857;border:1px solid rgba(16,185,129,.35);font-size:.66rem;padding:.25em .6em">' + className + '</span>' +
        '<div class="mt-1">Guru: ' + guruName + ' • Siswa: ' + studentCount + ' • Jenis: ' + classType + '</div>' +
        '<div class="mt-1 text-muted">Jadwal terdaftar: ' + scheduleCount + ' • Siswa yang sudah ada: ' + (studentNames || 'Belum ada') + '</div>' +
        '<div class="mt-1"><span class="badge rounded-pill" style="background:rgba(37,99,235,.10);color:#1d4ed8;border:1px solid rgba(37,99,235,.24);font-size:.64rem;padding:.25em .55em">Pakai kelas ini</span></div>';
}

function renderActiveClassInsights() {
    document.querySelectorAll('#conflictResultsPanel .conflict-card').forEach(card => {
        const summary = card.querySelector('.active-class-summary');
        if (!summary) return;
        let items = [];
        try {
            items = JSON.parse(card.dataset.activeClasses || '[]');
        } catch (e) {
            items = [];
        }

        if (!items.length) {
            summary.innerHTML = '<div class="text-muted" style="font-size:.74rem">Belum ada kelas aktif untuk mata pelajaran ini. Sistem akan membuat kelas baru saat pendaftaran selesai.</div>';
            return;
        }

        summary.innerHTML = items.map(item => {
            const remaining = Math.max(0, (Number(item.total_sessions || 0) - Number(item.scheduled_sessions || 0)));
            const studentNames = Array.isArray(item.student_names) ? item.student_names.filter(Boolean) : [];
            const studentText = studentNames.length
                ? studentNames.join(', ')
                : 'Belum ada siswa terdaftar.';
            return '<div class="border rounded-3 p-2 mb-2" style="background:rgba(16,185,129,.05);border-color:rgba(16,185,129,.2)">' +
                '<div class="fw-semibold" style="font-size:.76rem">' + (item.nama_kelas || 'Kelas Aktif') + '</div>' +
                '<div class="mt-1" style="font-size:.72rem">Guru: ' + (item.guru_name || '—') + ' • Jenis: ' + (item.jenis || '—') + '</div>' +
                '<div class="mt-1" style="font-size:.72rem">Siswa (' + (item.siswa_count || 0) + '): ' + studentText + '</div>' +
                '<div class="mt-1 text-muted" style="font-size:.72rem">Sesi total: ' + (item.total_sessions || 0) + ' • Sisa sesi: ' + remaining + '</div>' +
                '<div class="mt-2 d-flex gap-2 flex-wrap">' +
                '<button type="button" class="btn btn-sm btn-outline-primary" onclick="selectExistingClass(' + card.dataset.courseId + ', ' + item.id + ')"><i class="bi bi-check2-circle me-1"></i>Pakai kelas ini</button>' +
                '<button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearExistingClassSelection(' + card.dataset.courseId + ')"><i class="bi bi-plus-circle me-1"></i>Buat kelas baru</button>' +
                '</div></div>';
        }).join('');
    });
}

function selectExistingClass(courseId, classId) {
    const select = document.querySelector('.existing-class-select[data-course-id="' + courseId + '"]');
    if (!select) return;
    select.value = classId;
    select.dispatchEvent(new Event('change'));
    syncExistingClassDetail(select);
    showToast('Kelas aktif dipilih untuk mata pelajaran ini.', 'success');
}

function clearExistingClassSelection(courseId) {
    const select = document.querySelector('.existing-class-select[data-course-id="' + courseId + '"]');
    if (!select) return;
    select.value = '';
    select.dispatchEvent(new Event('change'));
    syncExistingClassDetail(select);
    showToast('Sistem akan membuat kelas baru untuk mata pelajaran ini.', 'info');
}

function bindCourseRowEvents(row) {
    row.querySelectorAll('.course-check, input[name^="course_sessions"]').forEach(el => {
        el.addEventListener('input', () => { recalcTotal(); });
        el.addEventListener('change', () => { recalcTotal(); });
    });
    row.querySelectorAll('.course-check').forEach(el => el.addEventListener('change', () => { recalcTotal(); renderFinanceRows(); }));
    const courseId = row.dataset.courseRow;
    const guruSelect = row.querySelector('.guru-select');
    const classSelect = row.querySelector('.existing-class-select');
    const honorToggle = row.querySelector('.course-use-honor');
    if (guruSelect) {
        guruSelect.addEventListener('change', () => {
            applyTeacherContractMeta(guruSelect);
            syncGuruName(courseId);
        });
        applyTeacherContractMeta(guruSelect);
        syncGuruName(courseId);
    }
    if (classSelect) {
        classSelect.addEventListener('change', () => syncExistingClassDetail(classSelect));
    }
    if (honorToggle) {
        honorToggle.addEventListener('change', () => {
            const honorInput = row.querySelector('.honor-input');
            if (honorInput) honorInput.disabled = !honorToggle.checked;
            updateContractAddition(row);
            recalcTotal();
        });
    }
    bindGuruConflictEvents(courseId);
}
document.querySelectorAll('.pw-course-row').forEach(bindCourseRowEvents);
function renderFinanceRows() {
    const container = document.getElementById('financeCourseRows');
    if (!container) return;
    const existingState = {};
    document.querySelectorAll('#financeCourseRows .finance-row').forEach(row => {
        const courseId = row.dataset.courseId;
        if (!courseId) return;
        existingState[courseId] = {
            fee: row.querySelector('.fee-input')?.value || '',
            honor: row.querySelector('.honor-input')?.value || '',
            useHonor: !!row.querySelector('.course-use-honor')?.checked,
        };
    });

    const courseRows = Array.from(document.querySelectorAll('.pw-course-row'));
    const selectedRows = courseRows.filter(row => {
        const chk = row.querySelector('.course-check');
        return !chk || chk.checked;
    });

    container.innerHTML = '';
    if (!selectedRows.length) {
        container.innerHTML = '<div class="text-muted" style="font-size:.8rem">Pilih setidaknya satu mapel di langkah sebelumnya untuk mengisi rincian keuangan.</div>';
        recalcTotal();
        return;
    }

    selectedRows.forEach(row => {
        const courseId = row.dataset.courseRow;
        const label = row.querySelector('.form-check-label')?.textContent.trim() || `Mapel ${courseId}`;
        const state = existingState[courseId] || {};
        const fee = state.fee ?? row.dataset.defaultFee ?? 0;
        const honor = state.honor ?? row.dataset.defaultHonor ?? 0;
        const useHonor = !!state.useHonor;
        const financeRow = document.createElement('div');
        financeRow.className = 'finance-row p-3 rounded-3 border';
        financeRow.dataset.courseId = courseId;
        financeRow.innerHTML = `
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold" style="font-size:.78rem">Mapel</label>
                    <div class="fw-semibold" style="font-size:.9rem">${label}</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold" style="font-size:.78rem">Biaya Siswa (Rp)</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp</span>
                        <input type="number" min="0" class="form-control fee-input" name="course_fee[${courseId}]" value="${fee}" oninput="updateRowMargin(this)">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold" style="font-size:.78rem">Honor Guru / Sesi</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp</span>
                        <input type="number" min="0" class="form-control honor-input" name="course_honor[${courseId}]" value="${honor}" oninput="updateRowMargin(this)" ${useHonor ? '' : 'disabled'}>
                    </div>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input course-use-honor" type="checkbox" name="course_use_honor[${courseId}]" value="1" ${useHonor ? 'checked' : ''}>
                        <label class="form-check-label" style="font-size:.78rem">Tambah honor/sesi</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="margin-display p-2 rounded-2" style="background:rgba(16,185,129,.07);border:1px solid rgba(16,185,129,.2);font-size:.82rem">
                        <div class="fw-semibold" style="color:#10b981">Rp0</div>
                        <div class="text-muted" style="font-size:.76rem">(0%)</div>
                    </div>
                </div>
            </div>`;
        container.appendChild(financeRow);

        const feeInput = financeRow.querySelector('.fee-input');
        const honorInput = financeRow.querySelector('.honor-input');
        const honorToggle = financeRow.querySelector('.course-use-honor');
        if (feeInput) feeInput.addEventListener('input', () => updateRowMargin(feeInput));
        if (honorInput) honorInput.addEventListener('input', () => updateRowMargin(honorInput));
        if (honorToggle) honorToggle.addEventListener('change', () => {
            if (honorInput) honorInput.disabled = !honorToggle.checked;
            updateRowMargin(honorInput || feeInput);
        });
        updateRowMargin(feeInput || honorInput);
    });

    recalcTotal();
}

renderFinanceRows();
// Ensure all teacher selections sync to schedule row labels immediately.
document.querySelectorAll('.guru-select').forEach(el => {
    const courseId = el.dataset.courseId;
    if (!courseId) return;
    el.addEventListener('change', () => syncGuruName(courseId));
    syncGuruName(courseId);
});
    syncAllGuruNames();
const courseMetaList = @json($courseMeta ?? []);
const courseMetaMap = {};
courseMetaList.forEach(c => courseMetaMap[c.id] = c);
const usedCourseIds = new Set(@json($courses->pluck('id')->values()));

const packageList = @json($packageList);
const packageMetaMap = {};
packageList.forEach(p => packageMetaMap[p.id] = p);

function formatTeacherOptionLabel(t) {
    const jenis = (t && t.jenis_guru) ? t.jenis_guru.toString().toLowerCase() : '';
    const salary = Number(t && t.salary_base ? t.salary_base : 0);
    const jenisLabel = jenis ? ' • ' + jenis.charAt(0).toUpperCase() + jenis.slice(1) : '';
    const salaryLabel = jenis === 'kontrak' && salary > 0 ? ' • Gaji: Rp ' + Number(salary).toLocaleString('id-ID') : '';
    return `${t.name || ''}${jenisLabel}${salaryLabel}`;
}

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
    row.dataset.defaultFee = parseFloat(course.fee) || 0;
    row.dataset.defaultHonor = Math.round(((parseFloat(course.fee) || 0) * 0.6) / 8);
    const guruOptions = course.guru.map(t => `<option value="${t.id}" data-jenis-guru="${t.jenis_guru || ''}" data-salary-base="${Number(t.salary_base || 0)}">${formatTeacherOptionLabel(t)}</option>`).join('');
    const moduleOptions = (course.modules || []).map(m => `<option value="${m.id}">${m.judul}</option>`).join('');
    const fee    = parseFloat(course.fee) || 0;
    const sesiDef = 8;
    const honor  = Math.round((fee * 0.6) / sesiDef); // honor per sesi
    row.innerHTML = `
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-2">
                <div class="field-label-mobile fw-semibold text-muted mb-1">Mata Pelajaran</div>
                <div class="form-check">
                    <input class="form-check-input course-check" type="checkbox" name="course_ids[]" value="${course.id}" id="rowCourse${course.id}" checked>
                    <label class="form-check-label fw-semibold" for="rowCourse${course.id}" style="font-size:.82rem">${course.nama}</label>
                </div>
                <div class="form-text" style="font-size:.67rem">${isAdmin ? 'Ditambahkan admin' : 'Mapel pilihan siswa'}</div>
            </div>
            <div class="col-12 col-md-2">
                <div class="field-label-mobile fw-semibold text-muted mb-1">Guru Pengajar</div>
                <select class="form-select form-select-sm guru-select" name="course_teacher[${course.id}]" data-course-id="${course.id}">
                    <option value="">Pilih guru…</option>
                    ${guruOptions}
                </select>
                <div class="teacher-contract-info text-muted mt-1" style="font-size:.68rem"></div>
                <div class="mt-2">
                    <label class="form-label fw-semibold" style="font-size:.74rem">Modul</label>
                    <select class="form-select form-select-sm" name="course_module[${course.id}][]" multiple style="min-height:120px">
                        ${moduleOptions}
                    </select>
                    <div class="form-text" style="font-size:.7rem">Pilih satu atau lebih modul.</div>
                </div>
            </div>
            <div class="col-12 col-md-1">
                <div class="field-label-mobile fw-semibold text-muted mb-1">Sesi</div>
                <input type="number" min="1" class="form-control form-control-sm" name="course_sessions[${course.id}]" placeholder="Sesi" value="8">
            </div>
            <div class="col-12 col-md-1 text-end">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCourseRow(this, ${course.id})" title="Hapus mapel ini"><i class="bi bi-trash"></i></button>
            </div>
        </div>`;
    return row;
}

// Builds Card B (jadwal kelas) table row — now a <tr> with multiple schedule slots
function buildScheduleRow(course) {
    const tr = document.createElement('tr');
    tr.className = 'pw-sched-tr';
    tr.dataset.scheduleRow = course.id;
    tr.innerHTML = `
        <td class="text-center text-muted sched-no" style="font-size:.78rem">—</td>
        <td><span class="badge rounded-pill" style="background:rgba(200,77,223,.12);color:#461256;font-size:.75rem;font-weight:600;padding:.3em .75em">${course.nama}</span></td>
        <td colspan="5" class="p-0">
            <div class="schedule-slot-list" data-course-id="${course.id}">
                <div class="schedule-slot-row border-bottom p-2" data-slot-index="0">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" style="font-size:.72rem">Hari</label>
                            <select class="form-select form-select-sm hari-select" name="schedule_hari[${course.id}][0]" data-course-id="${course.id}" style="min-width:88px">
                                <option value="">Pilih…</option>
                                <option value="1">Senin</option><option value="2">Selasa</option><option value="3">Rabu</option>
                                <option value="4">Kamis</option><option value="5">Jum'at</option><option value="6">Sabtu</option><option value="0">Minggu</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold" style="font-size:.72rem">Mulai</label>
                            <input type="time" class="form-control form-control-sm jam-mulai-input" name="schedule_jam_mulai[${course.id}][0]" data-course-id="${course.id}" value="08:00" style="min-width:100px">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold" style="font-size:.72rem">Selesai</label>
                            <input type="time" class="form-control form-control-sm jam-selesai-input" name="schedule_jam_selesai[${course.id}][0]" data-course-id="${course.id}" value="10:00" style="min-width:100px">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" style="font-size:.72rem">Ruang / Media</label>
                            <div class="room-field"><select class="form-select form-select-sm room-select" name="schedule_room[${course.id}][0]" data-course-id="${course.id}" style="min-width:140px">${_roomOptions}</select></div>
                            <div class="meeting-field" style="display:none"><input type="url" class="form-control form-control-sm meeting-input" name="schedule_link_meeting[${course.id}][0]" placeholder="Link meeting" autocomplete="off"></div>
                        </div>
                        <div class="col-md-2 text-end">
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeScheduleSlot(this)"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-2 px-2 pb-2">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addScheduleSlot(this, ${course.id})"><i class="bi bi-plus-circle me-1"></i>Tambah slot jadwal</button>
            </div>
        </td>
        <td class="text-center align-middle"><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCourseScheduleRow(this, ${course.id})" title="Hapus semua slot jadwal mapel ini"><i class="bi bi-trash"></i></button></td>`;
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
    renderFinanceRows();
    recalcTotal();
}

function setKelolaKelasMode(mode) {
    const newBtn = document.getElementById('kelolaNewBtn');
    const joinBtn = document.getElementById('kelolaJoinBtn');
    const newPanel = document.getElementById('kelolaNewPanel');
    const joinPanel = document.getElementById('kelolaJoinPanel');
    const conflictWrapper = document.getElementById('conflictCardWrapper');
    if (!newBtn || !joinBtn || !newPanel || !joinPanel) return;

    if (mode === 'join') {
        newBtn.classList.remove('btn-primary');
        newBtn.classList.add('btn-outline-secondary');
        joinBtn.classList.remove('btn-outline-secondary');
        joinBtn.classList.add('btn-primary');
        newPanel.style.display = 'none';
        joinPanel.style.display = '';
        if (conflictWrapper) conflictWrapper.style.display = 'none';
        const scheduleCard = document.getElementById('scheduleCard');
        if (scheduleCard) scheduleCard.style.display = 'none';
    } else {
        newBtn.classList.add('btn-primary');
        newBtn.classList.remove('btn-outline-secondary');
        joinBtn.classList.remove('btn-primary');
        joinBtn.classList.add('btn-outline-secondary');
        newPanel.style.display = '';
        joinPanel.style.display = 'none';
        if (conflictWrapper) conflictWrapper.style.display = '';
        const scheduleCard = document.getElementById('scheduleCard');
        if (scheduleCard) scheduleCard.style.display = '';
    }
}

function removeCourseRow(btn, id) {
    btn.closest('.pw-course-row').remove();
    const schedRow = document.querySelector(`#scheduleRowsContainer .pw-sched-tr[data-schedule-row="${id}"]`);
    if (schedRow) schedRow.remove();
    const conflictRow = document.getElementById(`conflict-result-${id}`);
    if (conflictRow) conflictRow.remove();
    usedCourseIds.delete(id);
    refreshExtraCourseSelect();
    renderFinanceRows();
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
        const price = parseFloat(sel.dataset.harga || 0);
        const pkg = packageMetaMap[val] || packageMetaMap[Number(val)];
        if (pkg) {
            const detailParts = [];
            if (pkg.tipe_kelas) {
                detailParts.push(pkg.tipe_kelas.charAt(0).toUpperCase() + pkg.tipe_kelas.slice(1));
            }
            if (pkg.jumlah_pertemuan) {
                detailParts.push(pkg.jumlah_pertemuan + ' pertemuan');
            }
            const mapelNames = Array.isArray(pkg.mata_pelajaran)
                ? pkg.mata_pelajaran.map(c => c.nama).filter(Boolean)
                : [];
            const mapelText = mapelNames.length
                ? 'Mata Pelajaran: ' + mapelNames.join(', ')
                : 'Mata Pelajaran: —';

            document.getElementById('pkgInfoNama').textContent = pkg.nama;
            document.getElementById('pkgInfoDetail').textContent = detailParts.join(' · ') || 'Detail paket tidak tersedia';
            document.getElementById('pkgInfoExtra').textContent = mapelText;
            document.getElementById('pkgInfoHarga').textContent = 'Rp' + Number(price).toLocaleString('id-ID');
            packageInfoBox.classList.remove('d-none');
            document.getElementById('totalBiaya').value = price;
        } else {
            const text = sel.text;
            const harga = parseFloat(sel.dataset.harga || 0);
            const parts = text.split(' — ');
            document.getElementById('pkgInfoNama').textContent = parts[0] || text;
            document.getElementById('pkgInfoDetail').textContent = parts.slice(1).join(' — ').replace(/·\s*Rp[\d.,]+/, '').trim();
            document.getElementById('pkgInfoExtra').textContent = 'Mata Pelajaran: —';
            document.getElementById('pkgInfoHarga').textContent = 'Rp' + Number(harga).toLocaleString('id-ID');
            packageInfoBox.classList.remove('d-none');
            document.getElementById('totalBiaya').value = harga;
        }
    } else {
        packageInfoBox.classList.add('d-none');
        recalcTotal();
    }
}
packageDropdown.addEventListener('change', onPackageDropdownChange);

function populatePackageFieldsFromStudentInfo() {
    const get = selector => document.querySelector(selector)?.value || '';
    const studentName    = get('[name="name"]').trim();
    const phone          = get('[name="phone"]').trim();
    const program        = get('[name="program"]').trim();
    const system         = get('[name="system"]').trim();
    const educationLevel = get('[name="education_level"]').trim();
    const tempatBelajar  = get('[name="tempat_belajar"]').trim();
    const address        = get('[name="address"]').trim();
    const parentName     = get('[name="parent_name"]').trim();
    const parentPhone    = get('[name="parent_phone"]').trim();
    const birthPlace     = get('[name="birth_place"]').trim();
    const birthDate      = get('[name="birth_date"]').trim();
    const dayInputs      = Array.from(document.querySelectorAll('input[name="hari_belajar[]"]:checked'));
    const days           = dayInputs.map(el => el.value).filter(Boolean);
    const scheduleText   = days.map(day => {
        const slots = Array.from(document.querySelectorAll('input[name="jam_detail[' + day + '][]"]'))
            .map(el => el.value.trim()).filter(Boolean);
        return slots.length ? day + ': ' + slots.join(', ') : day;
    }).filter(Boolean).join('; ');

    const pkgNameEl = document.querySelector('[name="custom_package_name"]');
    const jenisEl   = document.querySelector('[name="custom_jenis"]');
    const sesiEl    = document.querySelector('[name="jumlah_pertemuan"]');
    const metodeEl  = document.querySelector('[name="custom_metode_absensi"]');
    const tipeEl    = document.querySelector('[name="custom_tipe_kelas"]');
    const priceEl   = document.querySelector('[name="custom_package_price"]');
    const statusEl  = document.querySelector('[name="custom_status"]');
    const descEl    = document.querySelector('[name="custom_deskripsi"]');

    if (pkgNameEl && !pkgNameEl.value.trim()) {
        const baseName = studentName ? studentName + ' - ' : '';
        const programLabel = program ? program.charAt(0).toUpperCase() + program.slice(1) + ' ' : '';
        const levelLabel = educationLevel ? educationLevel.replace(/\s+\(.*\)/, '') + ' ' : '';
        pkgNameEl.value = `${baseName}${programLabel}${levelLabel}Paket`.trim();
    }

    if (jenisEl && !jenisEl.value) {
        jenisEl.value = program === 'privat' ? 'privat'
            : system === 'online' ? 'online'
            : 'reguler';
    }
    if (sesiEl && (!sesiEl.value || Number(sesiEl.value) <= 0)) {
        sesiEl.value = 8;
    }
    if (metodeEl && !metodeEl.value) {
        metodeEl.value = system === 'online' ? 'otomatis' : 'manual';
    }
    if (tipeEl && !tipeEl.value) {
        tipeEl.value = system === 'online' ? 'online' : (program === 'privat' ? 'private' : 'offline');
    }
    if (priceEl && (!priceEl.value || Number(priceEl.value) <= 0)) {
        priceEl.value = 0;
    }
    if (statusEl && !statusEl.value) {
        statusEl.value = 'aktif';
    }
    if (descEl && !descEl.value.trim()) {
        const details = [program ? `Program ${program}` : null,
            system ? `Sistem ${system}` : null,
            educationLevel ? educationLevel : null,
            tempatBelajar ? `Tempat ${tempatBelajar}` : null,
            scheduleText ? `Jadwal: ${scheduleText}` : null].filter(Boolean);
        descEl.value = `Paket khusus untuk ${studentName || 'siswa ini'}. ` + details.join(' • ') + (parentName ? ` • Orang tua: ${parentName}${parentPhone ? ' (' + parentPhone + ')' : ''}` : '');
    }
}

function parsePackageSessions() {
    const selectedOption = packageDropdown?.selectedOptions[0];
    if (!selectedOption) return 0;
    const match = selectedOption.text.match(/(\d+)\s*pertemuan/i);
    return match ? parseInt(match[1], 10) : 0;
}

function populateMapelGuruFromPreviousSteps() {
    const rows = Array.from(document.querySelectorAll('.pw-course-row'));
    if (!rows.length) return;

    const selectedRows = rows.filter(row => row.querySelector('.course-check')?.checked);
    const targetRows = selectedRows.length ? selectedRows : rows;
    const courseCount = targetRows.length || 1;

    const packageSessions = parseInt(document.querySelector('[name="jumlah_pertemuan"]')?.value || 0, 10) || parsePackageSessions() || 8;
    const customPrice = parseFloat(document.querySelector('[name="custom_package_price"]')?.value || 0);
    const standardPrice = parseFloat(packageDropdown?.selectedOptions[0]?.dataset.harga || 0);
    const packageFee = (customPrice > 0 && (packageMode === 'custom' || packageMode === 'request'))
        ? Math.round(customPrice / courseCount)
        : Math.round(standardPrice / courseCount);

    const scheduleDays = Array.from(document.querySelectorAll('input[name="hari_belajar[]"]:checked'))
        .map(el => el.value.trim()).filter(Boolean);
    const firstSlot = document.querySelector('input[name^="jam_detail"][name*="[]"]')?.value || '';
    const [firstStart, firstEnd] = firstSlot.split('-').map(t => t.trim());

    targetRows.forEach(row => {
        const sesiInput = row.querySelector('input[name^="course_sessions"]');
        if (sesiInput) sesiInput.value = packageSessions;

        const feeInput = row.querySelector('.fee-input');
        if (feeInput && packageFee > 0) feeInput.value = packageFee;

        const hariSelect = row.querySelector('.hari-select');
        const jamMulai = row.querySelector('.jam-mulai-input');
        const jamSelesai = row.querySelector('.jam-selesai-input');
        if (hariSelect && scheduleDays.length === 1) {
            const option = Array.from(hariSelect.options).find(o => o.text.toLowerCase().includes(scheduleDays[0].toLowerCase()));
            if (option) hariSelect.value = option.value;
        }
        if (jamMulai && firstStart) jamMulai.value = firstStart;
        if (jamSelesai && firstEnd) jamSelesai.value = firstEnd;

        const honorToggle = row.querySelector('.course-use-honor');
        const honorInput = row.querySelector('.honor-input');
        if (honorToggle && honorInput) {
            honorToggle.checked = false;
            honorInput.disabled = true;
        }
        updateRowMargin(feeInput || sesiInput);
    });
    recalcTotal();
}

let isCustomPkg = false;
let packageMode = 'standard';

function switchPackage(type) {
    packageMode = type;
    isCustomPkg = (type === 'custom' || type === 'request');

    const packageModeInput = document.getElementById('packageModeInput');
    const isCustomPackageInput = document.getElementById('isCustomPackage');
    const standardPackageEl = document.getElementById('standardPackage');
    const customPackageEl = document.getElementById('customPackage');
    const freePackageNote = document.getElementById('freePackageNote');
    const totalBiayaEl = document.getElementById('totalBiaya');
    const customPackagePriceEl = document.getElementById('customPackagePrice');

    if (packageModeInput) packageModeInput.value = packageMode;
    if (isCustomPackageInput) isCustomPackageInput.value = isCustomPkg ? '1' : '0';
    if (standardPackageEl) standardPackageEl.style.display = packageMode === 'standard' ? '' : 'none';
    if (customPackageEl) customPackageEl.style.display = packageMode === 'request' ? '' : 'none';
    if (freePackageNote) freePackageNote.classList.toggle('d-none', packageMode !== 'free');

    if (packageMode === 'request') {
        try { populatePackageFieldsFromStudentInfo(); } catch (e) {}
    }

    const btnStandard = document.getElementById('btnStandard');
    const btnRequest  = document.getElementById('btnRequest');
    const btnFree     = document.getElementById('btnFree');
    if (btnStandard) {
        btnStandard.className = 'btn btn-sm flex-fill ' + (type === 'standard' ? 'btn-primary' : 'btn-outline-secondary');
    }
    if (btnRequest) {
        btnRequest.className  = 'btn btn-sm flex-fill ' + (type === 'request' ? 'btn-primary' : 'btn-outline-secondary');
    }
    if (btnFree) {
        btnFree.className  = 'btn btn-sm flex-fill ' + (type === 'free' ? 'btn-primary' : 'btn-outline-secondary');
    }

    const titleEl = document.getElementById('packagePanelTitle');
    const hintEl  = document.getElementById('packagePanelHint');
    if (titleEl && hintEl) {
        if (type === 'standard') {
            titleEl.textContent = 'Pilih Paket Standar';
            hintEl.textContent = 'Pilih paket standar untuk siswa ini atau lanjutkan tanpa paket jika belum cocok.';
        } else if (type === 'request') {
            titleEl.textContent = 'Request Paket Kelas Baru';
            hintEl.textContent = 'Admin bisa mengajukan paket belajar khusus untuk siswa ini, lengkap dengan mata pelajaran, sesi, nominal, dan detail lainnya.';
        } else if (type === 'free') {
            titleEl.textContent = 'Paket Bebas';
            hintEl.textContent = 'Paket Bebas memungkinkan penyusunan tarif per mapel dan sesi secara manual tanpa memakai paket master.';
        }
    }

    if (isCustomPkg && totalBiayaEl && customPackagePriceEl) {
        totalBiayaEl.value = customPackagePriceEl.value || 0;
    } else if (packageMode === 'free') {
        recalcTotal();
    } else if (totalBiayaEl) {
        try { onPackageDropdownChange(); } catch (e) {}
    }
}

const customPackagePriceEl = document.getElementById('customPackagePrice');
if (customPackagePriceEl) {
    customPackagePriceEl.addEventListener('input', function() {
        const totalBiayaEl = document.getElementById('totalBiaya');
        if (isCustomPkg && totalBiayaEl) totalBiayaEl.value = this.value || 0;
    });
}

function buildPreview() {
    const data = getPreviewData();
    const fmt = v => 'Rp' + Number(v).toLocaleString('id-ID');
    const rowsHtml = data.courses.length
        ? data.courses.map(r => `
            <tr>
                <td class="fw-semibold" style="font-size:.83rem">${r.name}</td>
                <td style="font-size:.82rem">${r.teacher}</td>
                <td class="text-center">${r.sesi}</td>
                <td class="text-end">${fmt(r.fee)}</td>
                <td class="text-end">${fmt(r.honor)}</td>
                <td class="text-end fw-semibold" style="color:${r.marginColor}">${fmt(r.margin)} <span class="text-muted fw-normal" style="font-size:.75rem">(${r.pct}%)</span></td>
            </tr>`).join('')
        : '<tr><td colspan="6" class="text-muted text-center">Tidak ada mapel dipilih</td></tr>';

    const scheduleSummaryHtml = data.student.program.toLowerCase() === 'privat'
        ? `
            <div class="col-md-4"><span class="text-muted" style="font-size:.83rem">Tempat Belajar:</span> <strong>${data.student.learningPlace}</strong></div>
            <div class="col-md-8"><span class="text-muted" style="font-size:.83rem">Jadwal:</span> <strong style="font-size:.82rem">${data.student.generalSchedule}</strong></div>
            <div class="col-12 mt-2">${renderScheduleSummary(data)}</div>`
        : '';

    document.getElementById('previewBox').innerHTML = `
        <div class="row g-2 mb-3">
            <div class="col-md-3"><span class="text-muted" style="font-size:.83rem">Nama:</span> <strong>${data.student.name}</strong></div>
            <div class="col-md-3"><span class="text-muted" style="font-size:.83rem">No. HP:</span> <strong>${data.student.phone}</strong></div>
            <div class="col-md-3"><span class="text-muted" style="font-size:.83rem">Kategori:</span> <strong>${data.student.education_level}</strong></div>
            <div class="col-md-3"><span class="text-muted" style="font-size:.83rem">Program:</span> <strong>${data.student.program}</strong></div>
        </div>
        <div class="row g-2 mb-3">
            <div class="col-md-4"><span class="text-muted" style="font-size:.83rem">Paket:</span> <strong>${data.packageName}</strong></div>
            <div class="col-md-4"><span class="text-muted" style="font-size:.83rem">Metode Pembayaran:</span> <strong>${data.payment.methodText}</strong></div>
            <div class="col-md-4"><span class="text-muted" style="font-size:.83rem">Sekolah:</span> <strong>${data.student.schoolDetail}</strong></div>
            <div class="col-md-4"><span class="text-muted" style="font-size:.83rem">Kelas/Semester:</span> <strong>${data.student.classLabel}</strong></div>
        </div>
        ${scheduleSummaryHtml}

        <div class="d-flex flex-wrap gap-2 mb-3">
            <button type="button" class="btn btn-sm btn-success" onclick="sendInvoiceWA()"><i class="bi bi-whatsapp me-1"></i>Kirim Invoice WA</button>
            <button type="button" class="btn btn-sm btn-outline-success" onclick="sendLoginPinWA()"><i class="bi bi-whatsapp me-1"></i>Kirim Login PIN WA</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="printFormulir()"><i class="bi bi-printer me-1"></i>Cetak Formulir</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="printInvoice()"><i class="bi bi-printer me-1"></i>Cetak Invoice</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="printKartuSiswa()"><i class="bi bi-credit-card me-1"></i>Cetak Kartu Siswa</button>
        </div>

        <div class="fw-bold mb-2" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)"><i class="bi bi-journal-bookmark me-1"></i>Kelola Kelas</div>
        <div class="table-responsive mb-3">
            <table class="table table-sm table-bordered align-middle mb-0" style="font-size:.82rem">
                <thead><tr style="background:var(--input-bg)">
                    <th>Mapel</th><th>Guru</th><th class="text-center">Sesi</th>
                    <th class="text-end">Biaya Siswa</th><th class="text-end">Honor Guru</th><th class="text-end">Margin</th>
                </tr></thead>
                <tbody>${rowsHtml}</tbody>
            </table>
        </div>

        <div class="rounded-3 overflow-hidden mb-3" style="border:1.5px solid rgba(200,77,223,.35)">
            <div class="px-3 py-2 d-flex align-items-center gap-2" style="background:linear-gradient(90deg,rgba(70,18,86,.1),rgba(200,77,223,.08))">
                <i class="bi bi-graph-up-arrow" style="color:#c84ddf"></i>
                <span class="fw-bold" style="font-size:.82rem;color:#461256">?? Live Quotation</span>
                <span class="ms-auto badge rounded-pill" style="background:rgba(200,77,223,.15);color:#461256;font-size:.7rem">${data.courses.length} mapel</span>
            </div>
            <div class="p-3" style="background:var(--input-bg)">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 text-center h-100" style="background:rgba(200,77,223,.07);border:1.5px solid rgba(200,77,223,.25)">
                            <div class="text-muted mb-1" style="font-size:.68rem;text-transform:uppercase;letter-spacing:.05em"><i class="bi bi-person me-1"></i>Total Tagihan Siswa</div>
                            <div class="fw-bold" style="font-size:1.15rem;color:#461256">${fmt(data.totals.totalFee)}</div>
                            <div class="text-muted mt-1" style="font-size:.7rem">Tagihan yang diterbitkan ke siswa</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 text-center h-100" style="background:rgba(14,165,233,.07);border:1.5px solid rgba(14,165,233,.25)">
                            <div class="text-muted mb-1" style="font-size:.68rem;text-transform:uppercase;letter-spacing:.05em"><i class="bi bi-person-badge me-1"></i>Total Honor Guru</div>
                            <div class="fw-bold" style="font-size:1.15rem;color:#0ea5e9">${fmt(data.totals.totalHonor)}</div>
                            <div class="text-muted mt-1" style="font-size:.7rem">Biaya yang dibayarkan ke guru</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 text-center h-100" style="background:rgba(16,185,129,.07);border:1.5px solid rgba(16,185,129,.25)">
                            <div class="text-muted mb-1" style="font-size:.68rem;text-transform:uppercase;letter-spacing:.05em"><i class="bi bi-building me-1"></i>Pendapatan Bimbel</div>
                            <div class="fw-bold" style="font-size:1.15rem;color:${data.totals.totalMarginColor}">${fmt(data.totals.totalMargin)}</div>
                            <div class="mt-1">
                                <span class="badge rounded-pill" style="background:${data.totals.totalMargin >= 0 ? 'rgba(16,185,129,.15)' : 'rgba(220,38,38,.12)'};color:${data.totals.totalMarginColor};font-size:.72rem">Margin ${data.totals.totalPct}%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 pt-3" style="border-top:1px solid var(--card-border)">
                    <div class="row g-2" style="font-size:.84rem">
                        <div class="col-md-6 d-flex justify-content-between py-1" style="border-bottom:1px dashed var(--card-border)">
                            <span class="text-muted">Metode Pembayaran</span><strong>${data.payment.methodText}</strong>
                        </div>
                        <div class="col-md-6 d-flex justify-content-between py-1" style="border-bottom:1px dashed var(--card-border)">
                            <span class="text-muted">Total Biaya (Final)</span><strong class="text-primary">${fmt(data.totals.totalFee)}</strong></div>
                    </div>
                </div>
            </div>
        </div>
    `;
}
// ═══════════════════════════════════════════
// STEP 1 — Tipe Pendaftaran: Siswa Baru vs Siswa Lama
// ═══════════════════════════════════════════
function pwSetRegType(type) {
    document.getElementById('registrationTypeInput').value = type;
    document.getElementById('regTypeBaruBtn').classList.toggle('active', type === 'baru');
    document.getElementById('regTypeLamaBtn').classList.toggle('active', type === 'lama');
    document.getElementById('existingStudentWrapper').style.display = type === 'lama' ? '' : 'none';
    if (type === 'baru') {
        document.getElementById('existingStudentIdInput').value = '';
        document.getElementById('existingStudentSearch').value = '';
        document.getElementById('existingStudentChip').style.display = 'none';
    }
}

let _existingStudentSearchTimer = null;
document.getElementById('existingStudentSearch')?.addEventListener('input', function () {
    clearTimeout(_existingStudentSearchTimer);
    const q = this.value.trim();
    const resultsBox = document.getElementById('existingStudentResults');
    document.getElementById('existingStudentIdInput').value = '';
    if (q.length < 2) { resultsBox.style.display = 'none'; return; }
    _existingStudentSearchTimer = setTimeout(() => {
        fetch(_studentSearchUrl + '?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } })
            .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
            .then(data => {
                resultsBox.innerHTML = '';
                const students = data.students || [];
                if (!students.length) {
                    resultsBox.innerHTML = '<div class="list-group-item text-muted" style="font-size:.8rem">Tidak ada siswa ditemukan</div>';
                } else {
                    students.forEach(s => {
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'list-group-item list-group-item-action';
                        item.style.fontSize = '.82rem';
                        item.innerHTML = '<strong>' + s.name + '</strong><br>' +
                            '<span class="text-muted" style="font-size:.72rem">' +
                            (s.nis || '-') + ' &middot; ' + (s.phone || '-') + ' &middot; ' + (s.branch || '-') + '</span>';
                        item.onclick = () => pwSelectExistingStudent(s);
                        resultsBox.appendChild(item);
                    });
                }
                resultsBox.style.display = '';
            })
            .catch(() => {
                resultsBox.innerHTML = '<div class="list-group-item text-danger" style="font-size:.8rem">Gagal memuat data siswa.</div>';
                resultsBox.style.display = '';
            });
    }, 300);
});

function pwSelectExistingStudent(s) {
    document.getElementById('existingStudentIdInput').value = s.id;
    document.getElementById('existingStudentSearch').value = s.name;
    document.getElementById('existingStudentResults').style.display = 'none';

    // Tampilkan chip siswa terpilih
    document.getElementById('espChipName').textContent = s.name;
    document.getElementById('espChipMeta').textContent = (s.nis ? 'NIS: ' + s.nis : '') + (s.phone ? ' · ' + s.phone : '') + (s.branch ? ' · ' + s.branch : '');
    document.getElementById('existingStudentChip').style.display = '';

    // Tampilkan loading di field form
    const formFields = document.querySelectorAll(
        '[name="name"],[name="phone"],[name="gender"],[name="education_level"],' +
        '[name="birth_place"],[name="birth_date"],[name="address"],[name="parent_name"],[name="parent_phone"],[name="school_name"],[name="grade"]'
    );
    formFields.forEach(el => { el.style.opacity = '.4'; el.disabled = true; });

    // Ambil semua data siswa lalu isi ke field form yang sudah ada
    fetch(_studentDetailBase + '/' + s.id, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(data => {
            const d = data.student;
            const set = (name, val) => {
                const el = document.querySelector('[name="' + name + '"]');
                if (el) el.value = val || '';
            };
            set('name',            d.name);
            set('phone',           d.phone);
            set('gender',          d.gender);
            set('education_level', d.kategori_peserta_didik);
            set('birth_place',     d.birth_place);
            set('birth_date',      d.birth_date);
            set('address',         d.address);
            set('parent_name',     d.parent_name);
            set('parent_phone',    d.parent_phone);
            set('school_name',     d.school_name);
            set('grade',           d.grade);
            set('semester',        d.semester);
            // Perbarui chip meta dengan data lengkap
            document.getElementById('espChipName').textContent = d.name || s.name;
            document.getElementById('espChipMeta').textContent =
                (d.nis ? 'NIS: ' + d.nis : '') + (d.phone ? ' · ' + d.phone : '') + (d.branch_name ? ' · ' + d.branch_name : '');
        })
        .catch(() => showToast('Gagal memuat data siswa.', 'error'))
        .finally(() => {
            formFields.forEach(el => { el.style.opacity = '1'; el.disabled = false; });
        });
}

function pwClearExistingStudent() {
    document.getElementById('existingStudentIdInput').value = '';
    document.getElementById('existingStudentSearch').value = '';
    document.getElementById('existingStudentChip').style.display = 'none';
    // Reset field form ke nilai kosong / default
    ['name','phone','gender','education_level','birth_place','birth_date','address','parent_name','parent_phone','school_name','grade','semester']
        .forEach(name => {
            const el = document.querySelector('[name="' + name + '"]');
            if (el) el.value = '';
        });
}

document.addEventListener('click', function (e) {
    const wrapper = document.getElementById('existingStudentWrapper');
    const resultsBox = document.getElementById('existingStudentResults');
    if (wrapper && resultsBox && !wrapper.contains(e.target)) resultsBox.style.display = 'none';
});

// ═══════════════════════════════════════════
// STEP 1 — Tempat Belajar & Jadwal JS
// ═══════════════════════════════════════════
function pickTempat(val, el) {
    el.closest('.d-flex').querySelectorAll('.pw-opt-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('tempatBelajarInput').value = val;
    pwToggleRoomColumn();
}

// Tempat Belajar & Jadwal Belajar hanya relevan untuk program Privat —
// kelas reguler sudah punya jadwal/lokasi sendiri lewat paket/kelas yang dipilih.
function pwToggleLearningLogistics() {
    const program = document.getElementById('programSelect')?.value;
    const wrapper = document.getElementById('learningLogisticsWrapper');
    if (!wrapper) return;
    const isPrivat = program === 'privat';
    wrapper.style.display = isPrivat ? '' : 'none';
    wrapper.querySelectorAll('input, select, textarea').forEach(el => el.disabled = !isPrivat);
}

function pwToggleCourseClassSelectors() {
    const program = document.getElementById('programSelect')?.value;
    const isKelas = program === 'kelas';
    document.querySelectorAll('.course-class-picker').forEach(wrapper => {
        wrapper.style.display = isKelas ? '' : 'none';
    });
    document.querySelectorAll('.existing-class-select').forEach(selectEl => {
        selectEl.disabled = !isKelas;
    });
}

function pwToggleRoomColumn() {
    const system = document.getElementById('systemSelect')?.value;
    const program = document.getElementById('programSelect')?.value;
    const tempat = document.getElementById('tempatBelajarInput')?.value || 'kantor';
    const isOnlineLearningPlace = tempat === 'online';
    const shouldHideRoom = system === 'online' || (program === 'privat' && (tempat === 'rumah' || isOnlineLearningPlace));
    const shouldShowMeeting = system === 'online' || isOnlineLearningPlace;

    document.querySelectorAll('.room-column-header').forEach(el => {
        el.style.display = shouldHideRoom && !shouldShowMeeting ? 'none' : '';
    });
    document.querySelectorAll('.room-column-cell').forEach(cell => {
        const roomGroup = cell.querySelector('.room-field');
        const meetingGroup = cell.querySelector('.meeting-field');
        if (roomGroup) roomGroup.style.display = shouldShowMeeting ? 'none' : '';
        if (meetingGroup) meetingGroup.style.display = shouldShowMeeting ? '' : 'none';
        cell.style.display = shouldHideRoom && !shouldShowMeeting ? 'none' : '';
    });
    document.querySelectorAll('.room-select').forEach(el => {
        const visible = el.offsetParent !== null;
        el.disabled = shouldHideRoom && !shouldShowMeeting;
        el.required = !el.disabled && visible;
    });
    document.querySelectorAll('.meeting-input').forEach(el => {
        const visible = el.offsetParent !== null;
        el.disabled = !shouldShowMeeting;
        el.required = !el.disabled && visible;
    });

    const privateAddressWrapper = document.getElementById('privateAddressWrapper');
    if (privateAddressWrapper) {
        privateAddressWrapper.style.display = tempat === 'rumah' ? '' : 'none';
        document.getElementById('privateAddressInput').required = tempat === 'rumah';
    }
}

function validateStep2() {
    const program = document.getElementById('programSelect')?.value;
    const system = document.getElementById('systemSelect')?.value;
    const tempat = document.getElementById('tempatBelajarInput')?.value || 'kantor';
    if (program === 'privat' && tempat === 'rumah') {
        const address = document.getElementById('privateAddressInput')?.value.trim();
        if (!address) {
            showToast('Alamat Belajar wajib diisi untuk Privat ke Rumah.', 'error');
            return false;
        }
    }

    const roomSelects = Array.from(document.querySelectorAll('.room-select')).filter(el => el.offsetParent !== null && !el.disabled);
    const meetingInputs = Array.from(document.querySelectorAll('.meeting-input')).filter(el => el.offsetParent !== null && !el.disabled);

    if (roomSelects.length > 0) {
        const missingRoom = roomSelects.some(el => !el.value);
        if (missingRoom) {
            showToast('Pilih Ruangan untuk setiap jadwal kelas Offline/Kantor.', 'error');
            return false;
        }
    }

    if (meetingInputs.length > 0) {
        const missingMeeting = meetingInputs.some(el => !el.value.trim());
        if (missingMeeting) {
            showToast('Masukkan Link Meeting untuk setiap jadwal kelas Online.', 'error');
            return false;
        }
    }

    return true;
}

document.addEventListener('DOMContentLoaded', function () {
    pwToggleLearningLogistics();
    pwToggleCourseClassSelectors();
    pwToggleRoomColumn();
    const systemSelect = document.getElementById('systemSelect');
    const programSelect = document.getElementById('programSelect');
    const educationLevelSelect = document.querySelector('[name="education_level"]');
    if (systemSelect) {
        systemSelect.addEventListener('change', pwToggleRoomColumn);
    }
    if (programSelect) {
        programSelect.addEventListener('change', function () {
            pwToggleLearningLogistics();
            pwToggleCourseClassSelectors();
            pwToggleRoomColumn();
        });
    }

    if (educationLevelSelect) {
        educationLevelSelect.addEventListener('change', pwToggleSchoolData);
    }
    pwToggleSchoolData();
    document.querySelectorAll('.existing-class-select').forEach(selectEl => syncExistingClassDetail(selectEl));
    setKelolaKelasMode('new');

function pwToggleSchoolData() {
    const educationLevel = document.querySelector('[name="education_level"]')?.value;
    const schoolSection = document.getElementById('schoolDataSection');
    const gradeField = document.getElementById('schoolDataGrade');
    if (!schoolSection) return;

    const showSchoolData = ['Pra Sekolah (PAUD/TK)','Sekolah Dasar (SD)','Sekolah Menengah Pertama (SMP)','Sekolah Menengah Atas/Kejuruan (SMA/SMK)','Mahasiswa'].includes(educationLevel);
    const showGrade = educationLevel && educationLevel !== 'Mahasiswa';
    const showSemester = educationLevel === 'Mahasiswa';
    const semesterField = document.getElementById('schoolDataSemester');
    const schoolNameInput = document.querySelector('[name="school_name"]');
    const gradeInput = document.querySelector('[name="grade"]');
    const semesterInput = document.querySelector('[name="semester"]');
    const schoolNameLabel = document.getElementById('schoolNameLabel');

    schoolSection.style.display = showSchoolData ? '' : 'none';
    schoolSection.querySelectorAll('input, select, textarea').forEach(el => el.disabled = !showSchoolData);
    if (schoolNameInput) schoolNameInput.required = showSchoolData;
    if (gradeInput) gradeInput.required = showGrade;
    if (semesterInput) semesterInput.required = showSemester;
    if (gradeField) {
        gradeField.style.display = showGrade ? '' : 'none';
    }
    if (semesterField) {
        semesterField.style.display = showSemester ? '' : 'none';
    }
    if (schoolNameLabel) {
        schoolNameLabel.textContent = educationLevel === 'Mahasiswa' ? 'Nama Perguruan Tinggi' : 'Nama Sekolah';
    }
    if (schoolNameInput) {
        schoolNameInput.placeholder = educationLevel === 'Mahasiswa' ? 'Nama perguruan tinggi' : 'Nama sekolah';
    }
}
});

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
const totalBiayaEl = document.getElementById('totalBiaya');
if (totalBiayaEl) {
    totalBiayaEl.addEventListener('input', function() {
        updateLunasDisplay();
        if (document.getElementById('cicilanPanel').style.display !== 'none') {
            updateCicilanTotal();
        }
    });
}

// Auto-initialise payment method to prabayar on page load
if (document.querySelectorAll('.pm-card').length > 0) {
    recalcTotal();
    selectPaymentMethod('prabayar');
}

function handleCheckAllGuruClick(event) {
    const btn = event.currentTarget || document.getElementById('btnCekSemuaGuru');
    if (!btn) return;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengecek…';
    runAllConflictChecks();
    renderActiveClassInsights();
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
}

// Conflict check button — add loading state + toast summary
if (document.getElementById('btnCekSemuaGuru')) {
    document.getElementById('btnCekSemuaGuru').addEventListener('click', handleCheckAllGuruClick);
}

// ── end STEP 4 JS ──

document.getElementById('processForm').addEventListener('submit', function(e) {
    e.preventDefault();
    if (document.getElementById('registrationTypeInput').value === 'lama' && !document.getElementById('existingStudentIdInput').value) {
        showToast('Pilih siswa lama terlebih dahulu, atau ganti ke "Daftar Siswa Baru".', 'error');
        showStep(1);
        return;
    }
    const checkedCourses = document.querySelectorAll('.course-check:checked').length;
    if (checkedCourses === 0) {
        showToast('Pilih minimal satu mata pelajaran sebelum melanjutkan.', 'error');
        showStep(2);
        return;
    }
    const educationLevel = document.querySelector('[name="education_level"]')?.value;
    const schoolName = document.querySelector('[name="school_name"]')?.value.trim();
    const grade = document.querySelector('[name="grade"]')?.value.trim();
    const semester = document.querySelector('[name="semester"]')?.value.trim();
    const schoolRequiredLevels = ['Pra Sekolah (PAUD/TK)', 'Sekolah Dasar (SD)', 'Sekolah Menengah Pertama (SMP)', 'Sekolah Menengah Atas/Kejuruan (SMA/SMK)', 'Mahasiswa'];
    if (schoolRequiredLevels.includes(educationLevel) && !schoolName) {
        const schoolLabel = educationLevel === 'Mahasiswa' ? 'Nama perguruan tinggi' : 'Nama sekolah';
        showToast(schoolLabel + ' wajib diisi untuk kategori ' + educationLevel + '.', 'error');
        showStep(1);
        return;
    }
    if (educationLevel === 'Mahasiswa' && !semester) {
        showToast('Semester wajib diisi untuk kategori Mahasiswa.', 'error');
        showStep(1);
        return;
    }
    if (['Pra Sekolah (PAUD/TK)', 'Sekolah Dasar (SD)', 'Sekolah Menengah Pertama (SMP)', 'Sekolah Menengah Atas/Kejuruan (SMA/SMK)'].includes(educationLevel) && !grade) {
        showToast('Kelas wajib diisi untuk peserta sekolah.', 'error');
        showStep(1);
        return;
    }
    if (packageMode === 'request') {
        if (checkedCourses !== 1) {
            showToast('Paket Request hanya boleh untuk 1 mata pelajaran.', 'error');
            showStep(2);
            return;
        }
        const selectedRow = document.querySelector('.course-check:checked')?.closest('.pw-course-row');
        const selectedSessions = parseInt(selectedRow?.querySelector('input[name^="course_sessions"]')?.value || 0, 10);
        if (selectedSessions !== 1) {
            showToast('Paket Request harus berupa 1 sesi saja.', 'error');
            showStep(2);
            return;
        }
    }
    if (packageMode === 'standard') {
        const selectedOption = packageDropdown?.selectedOptions[0];
        const packageMaxSessions = parseInt(selectedOption?.dataset.jumlah || 0, 10);
        if (packageMaxSessions > 0) {
            const totalSesiSelected = Array.from(document.querySelectorAll('.course-check:checked')).reduce((sum, chk) => {
                const row = chk.closest('.pw-course-row');
                const sesi = parseInt(row?.querySelector('input[name^="course_sessions"]')?.value || 0, 10);
                return sum + (isNaN(sesi) ? 0 : sesi);
            }, 0);
            if (totalSesiSelected > packageMaxSessions) {
                showToast('Total sesi melebihi jumlah pertemuan paket standar.', 'error');
                showStep(2);
                return;
            }
        }
    }

    const isOnline = document.getElementById('systemSelect')?.value === 'online';
    if (isOnline) {
        const missingMeeting = Array.from(document.querySelectorAll('.course-check:checked')).some(chk => {
            const courseId = chk.value;
            const hari = document.querySelector(`[name="schedule_hari[${courseId}]"]`)?.value;
            const meeting = document.querySelector(`[name="schedule_link_meeting[${courseId}]"]`)?.value.trim();
            return hari && hari !== '' && !meeting;
        });
        if (missingMeeting) {
            showToast('Untuk kelas online, semua mapel yang dijadwalkan harus memiliki link meeting.', 'error');
            showStep(2);
            return;
        }
    }

    if (isCustomPkg) {
        const cpName = document.querySelector('[name="custom_package_name"]')?.value.trim();
        const cpJenis = document.querySelector('[name="custom_jenis"]')?.value;
        const packageLabel = packageMode === 'request' ? 'Paket Request' : 'Paket Custom';
        if (!cpName || !cpJenis) {
            showToast(`Lengkapi Nama Paket & Jenis Paket pada ${packageLabel}.`, 'error');
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
            const passwordRow = document.getElementById('cred-password-row');
            if (d.is_existing) {
                document.getElementById('successTitle').textContent = 'Siswa Lama Didaftarkan ke Kelas Baru';
                document.getElementById('successSubtitle').textContent = 'Akun sudah ada sebelumnya — cukup infokan siswa tentang program/kelas barunya, tidak perlu kirim password baru.';
                passwordRow.style.display = 'none';
            } else {
                document.getElementById('successTitle').textContent = 'Akun Siswa Berhasil Dibuat';
                document.getElementById('successSubtitle').textContent = 'Kirim informasi akun ini ke WhatsApp siswa agar bisa langsung login.';
                document.getElementById('cred-password').textContent = d.password || '–';
                passwordRow.style.display = '';
            }
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
        _credData.is_existing
            ? (
                'Halo ' + (_credData.name || 'Siswa') + ',\n\n' +
                'Pendaftaran Anda untuk program/kelas baru di Smart Center Indonesia telah *diverifikasi*.\n\n' +
                'Akun login Anda tetap sama seperti sebelumnya, silakan login untuk melihat kelas & jadwal terbaru:\n' + loginUrl + '\n\n' +
                'Terima kasih & selamat belajar!'
            )
            : (
                'Halo ' + (_credData.name || 'Siswa') + ',\n\n' +
                'Selamat datang di Smart Center Indonesia!\n\n' +
                'Pendaftaran Anda telah *diverifikasi*. Berikut data akun login Anda:\n\n' +
                '*Email:* ' + (_credData.email || '-') + '\n' +
                '*Password:* ' + (_credData.password || '-') + '\n' +
                '*No. Registrasi:* ' + (_credData.no_reg || '-') + '\n\n' +
                '*Link Login:*\n' + loginUrl + '\n\n' +
                'Segera login dan lengkapi profil Anda. Jangan bagikan password kepada siapapun.\n\n' +
                'Terima kasih & selamat belajar!'
            )
    );
    window.open('https://wa.me/' + wa + '?text=' + msg, '_blank');
}

function getPreviewContact() {
    const name = document.querySelector('[name="name"]')?.value || 'Siswa';
    const phone = (document.querySelector('[name="phone"]')?.value || '').replace(/\D/g, '');
    const wa = phone.startsWith('0') ? '62' + phone.slice(1) : phone;
    return { name, phone, wa };
}

function getPreviewData() {
    const tempMap = {kantor:'Di Kantor', rumah:'Guru ke Rumah', online:'Belajar Online'};
    const educationLevel = document.querySelector('[name="education_level"]')?.value || '–';
    const program = document.getElementById('programSelect')?.value || '–';
    const programText = document.getElementById('programSelect')?.selectedOptions[0]?.text || '–';
    const school = document.querySelector('[name="school_name"]')?.value || '–';
    const grade = document.querySelector('[name="grade"]')?.value || '–';
    const semester = document.querySelector('[name="semester"]')?.value || '–';
    const tempat = tempMap[document.getElementById('tempatBelajarInput')?.value] || '–';
    const hariBelajar = Array.from(document.querySelectorAll('input[name="hari_belajar[]"]:checked')).map(c => c.value);
    const jamDetail = Array.from(document.querySelectorAll('[name^="jam_detail["]')).map(i => i.value).filter(Boolean);
    const courseRows = [];
    let totalFee = 0;
    let totalHonor = 0;

    document.querySelectorAll('.course-check:checked').forEach(chk => {
        const row = chk.closest('.pw-course-row');
        const courseId = row.dataset.courseRow;
        const teacherText = row.querySelector('select')?.selectedOptions[0]?.text || '–';
        const sesiRaw = parseInt(row.querySelector('input[name^="course_sessions"]')?.value || 0, 10);
        const financeRow = document.querySelector(`#financeCourseRows .finance-row[data-course-id="${courseId}"]`);
        const fee = parseFloat(financeRow?.querySelector('.fee-input')?.value || 0);
        const honorPerSession = parseFloat(financeRow?.querySelector('.honor-input')?.value || 0);
        const honor = honorPerSession * (sesiRaw || 0);
        const margin = fee - honor;
        const pct = fee > 0 ? Math.round((margin / fee) * 100) : 0;
        const slots = [];
        const schedRow = document.querySelector(`.pw-sched-tr[data-schedule-row="${courseId}"]`);
        if (schedRow) {
            schedRow.querySelectorAll('.schedule-slot-row').forEach(slot => {
                const hari = slot.querySelector('.hari-select')?.selectedOptions[0]?.text || '';
                const mulai = slot.querySelector('.jam-mulai-input')?.value || '';
                const selesai = slot.querySelector('.jam-selesai-input')?.value || '';
                const room = slot.querySelector('.room-select')?.selectedOptions[0]?.text || '';
                const meeting = slot.querySelector('.meeting-input')?.value || '';
                if (hari || mulai || selesai || room || meeting) {
                    slots.push({ hari, mulai, selesai, room, meeting });
                }
            });
        }
        const name = row.querySelector('.form-check-label')?.textContent.trim() || '–';
        totalFee += fee;
        totalHonor += honor;
        courseRows.push({ name, teacher: teacherText, sesi: sesiRaw || '–', fee, honor, honorPerSession, margin, pct, scheduleSlots: slots });
    });

    const totalMargin = totalFee - totalHonor;
    const method = document.getElementById('paymentMethodInput')?.value || 'prabayar';
    const status = document.getElementById('paymentStatusInput')?.value || 'belum_bayar';
    const prabayarType = document.getElementById('prabayarTypeInput')?.value || 'lunas';
    let methodText = '–';
    if (method === 'prabayar') {
        methodText = prabayarType === 'cicilan'
            ? `Prabayar — Cicilan (${document.getElementById('jumlahCicilan')?.value || '?'}x)`
            : `Prabayar — ${status === 'lunas' ? 'Lunas Sekarang' : 'Invoice Dikirim'}`;
    } else if (method === 'pascabayar') {
        methodText = 'Pascabayar (Per Sesi)';
    }

    let packageName = 'Tanpa Paket';
    if (isCustomPkg) {
        const cpName = document.querySelector('[name="custom_package_name"]')?.value;
        packageName = cpName ? cpName + (packageMode === 'request' ? ' (Request)' : ' (Custom)') : `— (${packageMode === 'request' ? 'Request' : 'Custom'})`;
    } else {
        const sel = packageDropdown.selectedOptions[0];
        packageName = packageDropdown.value ? sel.text.split(' — ')[0] : 'Tanpa Paket';
    }

    return {
        student: {
            name: document.querySelector('[name="name"]')?.value || '–',
            phone: document.querySelector('[name="phone"]')?.value || '–',
            education_level: educationLevel,
            program: programText,
            schoolDetail: educationLevel === 'Mahasiswa' ? school : `${school}${grade && school !== '–' ? ' · ' + grade : grade !== '–' ? grade : ''}`,
            classLabel: educationLevel === 'Mahasiswa' ? semester : grade,
            learningPlace: tempat,
            generalSchedule: hariBelajar.length ? hariBelajar.join(' · ') : '–',
            detailSchedule: jamDetail.length ? jamDetail.join(', ') : '–',
        },
        packageName,
        payment: { method, status, methodText },
        courses: courseRows,
        totals: {
            totalFee,
            totalHonor,
            totalMargin,
            totalPct: totalFee > 0 ? Math.round((totalMargin / totalFee) * 100) : 0,
            totalMarginColor: totalMargin >= 0 ? '#10b981' : '#dc2626',
        },
    };
}

function renderScheduleSummary(data) {
    if (!data.courses.length) {
        return `<div class="col-12"><span class="text-muted" style="font-size:.83rem">Jadwal tidak tersedia</span></div>`;
    }
    const lines = data.courses.map(course => {
        if (!course.scheduleSlots.length) {
            return `<div><strong>${course.name}</strong>: Belum diset jadwal</div>`;
        }
        const slots = course.scheduleSlots.map(slot => `${slot.hari} ${slot.mulai}-${slot.selesai}${slot.room ? ' / ' + slot.room : ''}${slot.meeting ? ' (' + slot.meeting + ')' : ''}`);
        return `<div><strong>${course.name}</strong>: ${slots.join(' · ')}</div>`;
    });
    return `<div class="col-12"><span class="text-muted" style="font-size:.83rem">Jadwal per mapel:</span> <div style="font-size:.82rem">${lines.join('<br>')}</div></div>`;
}

function buildPrintHtml(title, contentHtml) {
    const style = `
        <style>
        body { font-family: Arial, sans-serif; color: #111; margin: 0; padding: 24px; }
        .header { margin-bottom: 18px; }
        .header h1 { font-size: 22px; margin: 0 0 6px; }
        .header p { margin: 0; color: #555; font-size: 13px; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 14px; font-weight: 700; margin-bottom: 8px; color: #333; }
        .details { width: 100%; border-collapse: collapse; font-size: 12px; }
        .details th, .details td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .details th { background: #f7f7f7; }
        .summary-card { border: 1px solid #ddd; border-radius: 8px; padding: 12px; margin-bottom: 12px; background: #fafafa; }
        .summary-card strong { display: block; font-size: 16px; }
        .text-right { text-align: right; }
        </style>`;
    return `<!doctype html><html><head><title>${title}</title>${style}</head><body><div class="header"><h1>${title}</h1><p>Dicetak dari halaman admin pendaftaran.</p></div>${contentHtml}</body></html>`;
}

function printFormulir() {
    const data = getPreviewData();
    const fmt = v => 'Rp' + Number(v).toLocaleString('id-ID');
    const studentHtml = `
        <div class="section">
            <div class="section-title">Data Peserta</div>
            <table class="details">
                <tr><th>Nama</th><td>${data.student.name}</td></tr>
                <tr><th>No. HP</th><td>${data.student.phone}</td></tr>
                <tr><th>Kategori</th><td>${data.student.education_level}</td></tr>
                <tr><th>Program</th><td>${data.student.program}</td></tr>
                <tr><th>Paket</th><td>${data.packageName}</td></tr>
                <tr><th>Sekolah / Perguruan Tinggi</th><td>${data.student.schoolDetail}</td></tr>
                <tr><th>Kelas / Semester</th><td>${data.student.classLabel}</td></tr>
                <tr><th>Tempat Belajar</th><td>${data.student.learningPlace}</td></tr>
            </table>
        </div>`;
    const courseRowsHtml = data.courses.length
        ? data.courses.map(r => `
                <tr>
                    <td>${r.name}</td>
                    <td>${r.teacher}</td>
                    <td>${r.sesi}</td>
                    <td class="text-right">${fmt(r.fee)}</td>
                    <td class="text-right">${fmt(r.honor)}</td>
                </tr>`).join('')
        : '<tr><td colspan="5" class="text-center">Tidak ada mapel dipilih</td></tr>';
    const courseHtml = `
        <div class="section">
            <div class="section-title">Rincian Mata Pelajaran</div>
            <table class="details">
                <thead><tr><th>Mapel</th><th>Guru</th><th>Sesi</th><th class="text-right">Biaya</th><th class="text-right">Honor</th></tr></thead>
                <tbody>${courseRowsHtml}</tbody>
            </table>
        </div>`;
    const scheduleHtml = `
        <div class="section">
            <div class="section-title">Jadwal</div>
            <div>${renderScheduleSummary(data)}</div>
        </div>`;
    const paymentHtml = `
        <div class="section">
            <div class="section-title">Pembayaran</div>
            <div class="summary-card">
                <strong>Metode</strong>${data.payment.methodText}
                <strong>Total Tagihan</strong>${fmt(data.totals.totalFee)}
            </div>
        </div>`;
    const html = studentHtml + courseHtml + scheduleHtml + paymentHtml;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(buildPrintHtml('Formulir Pendaftaran', html));
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => printWindow.print(), 300);
}

function printInvoice() {
    const data = getPreviewData();
    const fmt = v => 'Rp' + Number(v).toLocaleString('id-ID');
    const invoiceRowsHtml = data.courses.length
        ? data.courses.map(r => `
                <tr>
                    <td>${r.name}</td>
                    <td>${r.teacher}</td>
                    <td class="text-right">${fmt(r.fee)}</td>
                </tr>`).join('')
        : '<tr><td colspan="3" class="text-center">Tidak ada mapel dipilih</td></tr>';
    const html = `
        <div class="section">
            <div class="section-title">Kepada</div>
            <table class="details">
                <tr><th>Nama</th><td>${data.student.name}</td></tr>
                <tr><th>No. HP</th><td>${data.student.phone}</td></tr>
                <tr><th>Paket</th><td>${data.packageName}</td></tr>
                <tr><th>Sekolah / Perguruan Tinggi</th><td>${data.student.schoolDetail}</td></tr>
                <tr><th>Metode Pembayaran</th><td>${data.payment.methodText}</td></tr>
            </table>
        </div>
        <div class="section">
            <div class="section-title">Rincian Tagihan</div>
            <table class="details">
                <thead><tr><th>Mapel</th><th>Guru</th><th class="text-right">Jumlah</th></tr></thead>
                <tbody>${invoiceRowsHtml}</tbody>
                <tfoot>
                    <tr><th colspan="2">Total</th><th class="text-right">${fmt(data.totals.totalFee)}</th></tr>
                </tfoot>
            </table>
        </div>
        <div class="section">
            <div class="section-title">Metode Pembayaran</div>
            <div class="summary-card">${data.payment.methodText}</div>
        </div>`;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(buildPrintHtml('Draft Invoice', html));
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => printWindow.print(), 300);
}

function printKartuSiswa() {
    const data = getPreviewData();
    const html = `
        <div class="section">
            <div class="section-title">Kartu Siswa</div>
            <div class="summary-card">
                <strong>${data.student.name}</strong>
                <div>Kategori: ${data.student.education_level}</div>
                <div>Sekolah / PT: ${data.student.schoolDetail}</div>
                <div>Kelas / Semester: ${data.student.classLabel}</div>
                <div>Program: ${data.student.program}</div>
                <div>No. HP: ${data.student.phone}</div>
            </div>
        </div>
        <div class="section">
            <div class="section-title">Mapel Aktif</div>
            <table class="details">
                <thead><tr><th>Mapel</th><th>Guru</th><th class="text-right">Sesi</th></tr></thead>
                <tbody>${data.courses.map(r => `<tr><td>${r.name}</td><td>${r.teacher}</td><td class="text-right">${r.sesi}</td></tr>`).join('') || '<tr><td colspan="3" class="text-center">Belum ada mapel</td></tr>'}</tbody>
            </table>
        </div>`;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(buildPrintHtml('Kartu Siswa', html));
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => printWindow.print(), 300);
}

</script>
@endpush
