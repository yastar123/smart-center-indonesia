@extends('layouts.app')
@section('title','Buat Jadwal Kelas')
@section('page-title','Buat Jadwal Kelas')

@php
$conflictCheckUrl = route('admin.schedules.conflict-check');
$packageStudentsBaseUrl = '/admin/schedules/package';
@endphp

@section('content')
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.schedules.index') }}">Jadwal Sesi</a></li>
        <li class="breadcrumb-item active">Buat Jadwal Kelas</li>
    </ol>
</nav>

{{-- HEADER --}}
<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-20px;top:-20px;width:160px;height:160px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between" style="position:relative">
        <div class="d-flex align-items-center gap-3">
            <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                <i class="bi bi-calendar-plus"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0" style="color:white">Buat Jadwal Kelas</h5>
                <div style="font-size:12px;opacity:.8">Booking ruang, guru, dan sesi belajar secara terintegrasi</div>
            </div>
        </div>
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-sm"
           style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.3)">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

{{-- STEP INDICATOR --}}
<div class="dashboard-card mb-4 py-3">
    <div class="d-flex align-items-center justify-content-center gap-0">
        @php
        $steps = [
            ['num'=>1,'label'=>'Paket & Siswa','icon'=>'bi-box-seam'],
            ['num'=>2,'label'=>'Waktu & Lokasi','icon'=>'bi-clock'],
            ['num'=>3,'label'=>'Cek Konflik','icon'=>'bi-shield-check'],
            ['num'=>4,'label'=>'Guru & Honor','icon'=>'bi-person-badge'],
        ];
        @endphp
        @foreach($steps as $step)
        <div class="d-flex align-items-center">
            <div class="d-flex flex-column align-items-center" style="min-width:80px">
                <div style="width:36px;height:36px;border-radius:50%;background:var(--input-bg);border:2px solid var(--card-border);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:var(--text-muted);transition:.3s" id="step-circle-{{ $step['num'] }}">
                    {{ $step['num'] }}
                </div>
                <div style="font-size:11px;color:var(--text-muted);margin-top:4px;text-align:center;font-weight:500" id="step-label-{{ $step['num'] }}">{{ $step['label'] }}</div>
            </div>
            @if(!$loop->last)
            <div style="width:40px;height:2px;background:var(--card-border);margin-bottom:20px;flex-shrink:0" id="step-line-{{ $step['num'] }}"></div>
            @endif
        </div>
        @endforeach
    </div>
</div>

<form action="{{ route('admin.schedules.store') }}" method="POST" id="scheduleForm">
@csrf
@if($errors->any())
<div class="alert alert-danger mb-4">
    <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- STEP 1: PAKET & SISWA                                       --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div class="dashboard-card mb-4" id="section-paket">
    <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#c84ddf,#461256);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
            <i class="bi bi-box-seam"></i>
        </div>
        <div>
            <div class="fw-bold" style="font-size:15px">1. Pilih Paket Belajar & Siswa <span class="text-danger">*</span></div>
            <div class="text-muted" style="font-size:12px">Paket menentukan mata pelajaran, total sesi, dan siswa yang ikut</div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12">
            <label class="form-label fw-semibold">Paket Belajar <span class="text-danger">*</span></label>
            <select name="paket_id" id="paket_id" class="form-select" required onchange="onPaketChange(this.value)">
                <option value="">— Pilih Paket —</option>
                @foreach($pakets as $p)
                <option value="{{ $p->id }}" {{ old('paket_id') == $p->id ? 'selected' : '' }}>
                    {{ $p->nama }}{{ $p->cabang ? ' ('.$p->cabang->name.')' : '' }}
                    — {{ $p->jumlah_pertemuan }} sesi
                </option>
                @endforeach
            </select>
        </div>

        {{-- MATA PELAJARAN (auto-locked jika 1 mapel, dropdown jika banyak) --}}
        <div class="col-12" id="mapelBox" style="display:none">
            <div class="p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                <div class="fw-semibold mb-2" style="font-size:13px"><i class="bi bi-book me-2 text-primary"></i>Mata Pelajaran Sesi Ini <span class="text-danger">*</span></div>
                <div id="mapelSingleLock" style="display:none">
                    <div class="d-flex align-items-center gap-2">
                        <span id="mapelSingleBadge" style="background:var(--soft-primary);color:#461256;padding:5px 14px;border-radius:20px;font-size:13px;font-weight:600"></span>
                        <span class="text-muted" style="font-size:11px"><i class="bi bi-lock-fill me-1"></i>Otomatis dari paket</span>
                    </div>
                    <input type="hidden" name="mata_pelajaran_id" id="mata_pelajaran_id_hidden">
                </div>
                <div id="mapelMultiPick" style="display:none">
                    <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-select form-select-sm" style="max-width:400px" required>
                        <option value="">— Pilih mata pelajaran —</option>
                    </select>
                    <div class="form-text">Paket ini memiliki beberapa mata pelajaran — pilih satu untuk sesi ini.</div>
                </div>
            </div>
        </div>

        {{-- SISWA TERDAFTAR --}}
        <div class="col-12" id="siswaPaketBox" style="display:none">
            <div class="p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="fw-semibold" style="font-size:13px">
                        <i class="bi bi-people-fill text-primary me-2"></i>Siswa Terdaftar di Paket Ini
                        <span id="siswaPaketCount" class="badge ms-1" style="background:var(--soft-primary);color:#461256;font-size:11px"></span>
                    </div>
                </div>
                <div id="siswaPaketContent" class="d-flex flex-wrap gap-2">
                    <span class="text-muted" style="font-size:12px">Memuat siswa...</span>
                </div>
            </div>
        </div>

        {{-- TOMBOL LANJUT STEP 1 --}}
        <div class="col-12" id="step1NextBtn" style="display:none">
            <button type="button" class="btn btn-primary px-4" onclick="goToStep2()">
                Lanjut: Tentukan Waktu & Lokasi <i class="bi bi-arrow-right ms-2"></i>
            </button>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- STEP 2: WAKTU & LOKASI                                      --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div class="dashboard-card mb-4" id="section-waktu" style="display:none">
    <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#0ea5e9,#0369a1);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
            <i class="bi bi-clock"></i>
        </div>
        <div>
            <div class="fw-bold" style="font-size:15px">2. Tentukan Waktu & Lokasi <span class="text-danger">*</span></div>
            <div class="text-muted" style="font-size:12px">Tanggal, jam, metode kelas, dan lokasi pertemuan</div>
        </div>
    </div>

    {{-- METODE KELAS — 3 card pilihan --}}
    <div class="mb-4">
        <label class="form-label fw-semibold">Metode Kelas <span class="text-danger">*</span></label>
        <div class="row g-3" id="metodePicker">
            @php
            $metodes = [
                ['value'=>'offline','icon'=>'bi-building','label'=>'Offline','sub'=>'Tatap muka di ruang kelas'],
                ['value'=>'online', 'icon'=>'bi-camera-video','label'=>'Online','sub'=>'Via Zoom / Google Meet'],
                ['value'=>'private','icon'=>'bi-house-heart','label'=>'Home Visit','sub'=>'Kunjungan ke rumah siswa'],
            ];
            @endphp
            @foreach($metodes as $m)
            <div class="col-md-4">
                <div class="metode-card p-3 rounded-3 text-center" data-value="{{ $m['value'] }}"
                     style="border:2px solid var(--card-border);cursor:pointer;transition:.25s;background:var(--input-bg)"
                     onclick="selectMetode('{{ $m['value'] }}')">
                    <div style="font-size:28px;margin-bottom:6px"><i class="bi {{ $m['icon'] }}"></i></div>
                    <div class="fw-semibold" style="font-size:14px">{{ $m['label'] }}</div>
                    <div class="text-muted" style="font-size:11px">{{ $m['sub'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
        <input type="hidden" name="jenis" id="jenis" value="{{ old('jenis','offline') }}" required>
    </div>

    {{-- TANGGAL & JAM --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required onchange="onWaktuChange()">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
            <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" value="{{ old('jam_mulai') }}" required onchange="onWaktuChange()">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
            <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" value="{{ old('jam_selesai') }}" required onchange="onWaktuChange()">
        </div>
    </div>

    {{-- LOKASI — dinamis sesuai metode --}}
    <div class="mb-4 p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
        <div class="fw-semibold mb-3" style="font-size:13px" id="lokasiTitle"><i class="bi bi-geo-alt me-2"></i>Lokasi Kelas</div>

        {{-- Offline: Ruangan --}}
        <div id="lokasiOffline">
            <label class="form-label fw-semibold">Nama Ruangan <span class="text-muted fw-normal">(opsional)</span></label>
            <div class="input-group" style="max-width:400px">
                <span class="input-group-text" style="background:var(--input-bg);border-color:var(--card-border)"><i class="bi bi-door-open text-muted"></i></span>
                <input type="text" name="ruangan" id="ruangan" class="form-control" value="{{ old('ruangan') }}" placeholder="cth: Ruang A1, Ruang B2..." onchange="onWaktuChange()">
            </div>
            <div class="form-text">Mengunci ruangan ini dari kelas lain di jam yang sama (dideteksi saat Cek Konflik).</div>
        </div>

        {{-- Online: Link Meeting --}}
        <div id="lokasiOnline" style="display:none">
            <label class="form-label fw-semibold">Link Zoom / Google Meet <span class="text-danger">*</span></label>
            <div class="input-group" style="max-width:500px">
                <span class="input-group-text" style="background:var(--input-bg);border-color:var(--card-border)"><i class="bi bi-camera-video text-muted"></i></span>
                <input type="url" name="link_meeting" id="link_meeting" class="form-control" value="{{ old('link_meeting') }}" placeholder="https://zoom.us/j/...">
            </div>
        </div>

        {{-- Home Visit: Alamat --}}
        <div id="lokasiHomeVisit" style="display:none">
            <label class="form-label fw-semibold">Alamat Kunjungan <span class="text-danger">*</span></label>
            <textarea name="alamat_kunjungan" id="alamat_kunjungan" class="form-control" rows="2" style="max-width:500px" placeholder="Masukkan alamat lengkap rumah siswa...">{{ old('alamat_kunjungan') }}</textarea>
        </div>
    </div>

    {{-- TOPIK & OPSIONAL --}}
    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <label class="form-label fw-semibold">Topik / Materi <span class="text-muted fw-normal">(opsional)</span></label>
            <input type="text" name="topik" class="form-control" value="{{ old('topik') }}" placeholder="cth: Persamaan Kuadrat, Present Tense...">
        </div>
        <div class="col-lg-6">
            <label class="form-label fw-semibold">Kelas <span class="text-muted fw-normal">(opsional)</span></label>
            <select name="kelas_id" id="kelas_id" class="form-select">
                <option value="">— Tidak terhubung ke kelas —</option>
                @foreach($classes as $c)
                <option value="{{ $c->id }}" {{ old('kelas_id') == $c->id ? 'selected' : '' }}>
                    {{ $c->nama_kelas }}{{ $c->mataPelajaran ? ' — '.$c->mataPelajaran->nama : '' }}{{ $c->cabang ? ' ('.$c->cabang->name.')' : '' }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-6">
            <label class="form-label fw-semibold">Modul Belajar <span class="text-muted fw-normal">(opsional)</span></label>
            <select name="module_id" id="module_id" class="form-select" onchange="onModuleChange(this.value)">
                <option value="">— Tidak ada modul —</option>
                @foreach($modules as $m)
                <option value="{{ $m->id }}" {{ old('module_id') == $m->id ? 'selected' : '' }}>
                    {{ $m->judul }}@if($m->mataPelajaran) – {{ $m->mataPelajaran->nama }}@endif
                    @if($m->kode_modul) [{{ $m->kode_modul }}]@endif
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-6">
            <label class="form-label fw-semibold">Catatan <span class="text-muted fw-normal">(opsional)</span></label>
            <input type="text" name="catatan" class="form-control" value="{{ old('catatan') }}" placeholder="Catatan tambahan untuk sesi ini">
        </div>
    </div>

    {{-- CTA CEK KONFLIK --}}
    <div class="pt-3 border-top" id="cekKonflikCTA" style="display:none">
        <button type="button" class="btn btn-primary px-4" onclick="runConflictCheck()" id="btnCekKonflik">
            <i class="bi bi-shield-check me-2"></i>Cek Konflik & Lanjut Penugasan Guru
        </button>
        <div class="text-muted mt-1" style="font-size:11px">Sistem akan memeriksa ketersediaan ruangan dan guru di waktu yang dipilih.</div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- STEP 3: HASIL CEK KONFLIK                                   --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div class="dashboard-card mb-4" id="section-konflik" style="display:none">
    <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#8b5cf6,#6d28d9);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
            <i class="bi bi-shield-check"></i>
        </div>
        <div>
            <div class="fw-bold" style="font-size:15px">3. Hasil Pemeriksaan Konflik</div>
            <div class="text-muted" style="font-size:12px">Ketersediaan ruangan dan guru di waktu yang dipilih</div>
        </div>
    </div>

    <div id="konflikResults"></div>

    <div class="mt-3 d-flex gap-2" id="konflikActions" style="display:none!important">
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="backToStep2()">
            <i class="bi bi-arrow-left me-1"></i>Ubah Waktu
        </button>
        <button type="button" class="btn btn-primary px-4" id="btnLanjutGuru" onclick="goToStep4()">
            Lanjut: Tugaskan Guru & Kunci Honor <i class="bi bi-arrow-right ms-2"></i>
        </button>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- STEP 4: GURU & HONOR                                        --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div class="dashboard-card mb-4" id="section-guru" style="display:none">
    <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#10b981,#047857);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
            <i class="bi bi-person-badge"></i>
        </div>
        <div>
            <div class="fw-bold" style="font-size:15px">4. Tugaskan Guru & Kunci Honor</div>
            <div class="text-muted" style="font-size:12px">Pilih guru pengajar dan kunci nominal honor per sesi untuk jadwal ini</div>
        </div>
    </div>

    {{-- MATA PELAJARAN (summary info) --}}
    <div class="mb-4 p-3 rounded-3" style="background:var(--soft-primary-bg);border:1.5px solid var(--soft-primary-border)">
        <div class="d-flex align-items-center gap-2" style="font-size:13px">
            <i class="bi bi-book text-primary"></i>
            <span class="fw-semibold">Mata Pelajaran:</span>
            <span id="mapelSummaryLabel" class="badge" style="background:var(--soft-primary);color:#461256;font-size:12px">—</span>
            <span class="text-muted" style="font-size:11px">| Guru tersedia untuk mata pelajaran ini ditampilkan terlebih dahulu.</span>
        </div>
    </div>

    {{-- TEACHER LIST --}}
    <div class="mb-4">
        <label class="form-label fw-semibold">Pilih Guru Pengajar <span class="text-danger">*</span></label>
        <div id="guruList" class="row g-2">
            <div class="col-12 text-muted" style="font-size:13px"><i class="bi bi-hourglass-split me-1"></i>Memuat daftar guru...</div>
        </div>
        <input type="hidden" name="guru_id" id="guru_id" required>
        <div id="guruNote" class="form-text mt-2"></div>
    </div>

    {{-- HONOR PER SESI --}}
    <div class="p-3 rounded-3 mb-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
        <div class="fw-semibold mb-2" style="font-size:13px">
            <i class="bi bi-cash-coin me-2" style="color:#f6af23"></i>Honor Guru per Sesi
            <span class="text-muted fw-normal" style="font-size:11px">(opsional — dikunci saat jadwal dibuat)</span>
        </div>
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text" style="background:var(--input-bg);border-color:var(--card-border);font-weight:600">Rp</span>
                    <input type="number" name="honor_per_sesi" id="honor_per_sesi" class="form-control"
                           value="{{ old('honor_per_sesi') }}" min="0" step="1000" placeholder="cth: 150000">
                    <span class="input-group-text" style="background:var(--input-bg);border-color:var(--card-border)">/sesi</span>
                </div>
            </div>
            <div class="col-md-7">
                <div id="honorInfo" class="text-muted" style="font-size:12px">
                    <i class="bi bi-info-circle me-1"></i>Honor ini akan menjadi dasar penggajian guru untuk setiap sesi yang terlaksana.
                </div>
            </div>
        </div>
    </div>

    {{-- SUBMIT --}}
    <div class="pt-3 border-top d-flex justify-content-between align-items-center">
        <div class="text-muted" style="font-size:12px"><i class="bi bi-info-circle me-1"></i>Menyimpan jadwal akan otomatis: booking ruangan, booking waktu guru, menyiapkan draft absensi.</div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary" onclick="backToStep3()">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </button>
            <button type="submit" id="submitBtn" class="btn btn-primary px-5 fw-semibold" disabled>
                <i class="bi bi-calendar-check me-2"></i>Simpan Jadwal
            </button>
        </div>
    </div>
</div>

</form>
</div>
@endsection

@php
$paketsJson = $pakets->map(function ($p) {
    return [
        'id'               => $p->id,
        'nama'             => $p->nama,
        'jenis'            => $p->jenis,
        'jumlah_pertemuan' => $p->jumlah_pertemuan,
        'tipe_kelas'       => $p->tipe_kelas,
        'harga'            => $p->harga,
        'deskripsi'        => $p->deskripsi,
        'cabang'           => $p->cabang?->name,
        'cabang_id'        => $p->cabang_id,
        'guru_id'          => $p->guru_id,
        'guru_name'        => $p->guru?->name,
        'mata_pelajaran'   => $p->mataPelajaran->map(fn($m) => ['id' => $m->id, 'nama' => $m->nama, 'kategori' => $m->kategori ?? '']),
        'course_teachers'  => $p->courseTeachers->groupBy('course_id')->map(fn($ct) => $ct->pluck('teacher_id')->values()),
    ];
});

$teachersJson = $teachers->map(function ($t) {
    return [
        'id'         => $t->id,
        'name'       => $t->name,
        'branch'     => $t->branch?->name,
        'branch_id'  => $t->branch_id,
        'nig'        => $t->nig,
        'email'      => $t->email,
        'course_ids' => $t->courses->pluck('id')->values()->toArray(),
    ];
});

$modulesJson = $modules->map(function ($m) {
    return [
        'id'             => $m->id,
        'judul'          => $m->judul,
        'kode_modul'     => $m->kode_modul,
        'mata_pelajaran' => $m->mataPelajaran?->nama,
    ];
});
@endphp

@push('scripts')
<script>
const pakets   = @json($paketsJson);
const teachers = @json($teachersJson);
const modules  = @json($modulesJson);
const csrf     = document.querySelector('meta[name="csrf-token"]').content;
const conflictCheckUrl       = @json($conflictCheckUrl);
const packageStudentsBaseUrl = @json($packageStudentsBaseUrl);

let currentPaket    = null;
let currentMapelId  = null;
let busyTeacherIds  = [];
let selectedGuruId  = null;

// ─── Helpers ───────────────────────────────────────────────────────────────

function activateStep(n) {
    for (let i = 1; i <= 4; i++) {
        const circle = document.getElementById('step-circle-' + i);
        const label  = document.getElementById('step-label-' + i);
        const line   = document.getElementById('step-line-' + i);
        if (!circle) continue;
        if (i < n) {
            circle.style.cssText = 'width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#10b981,#047857);color:white;border:2px solid #10b981;display:flex;align-items:center;justify-content:center;font-size:16px;transition:.3s';
            circle.innerHTML = '<i class="bi bi-check-lg"></i>';
            label.style.color = '#10b981';
        } else if (i === n) {
            circle.style.cssText = 'width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#c84ddf,#461256);color:white;border:2px solid #c84ddf;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;transition:.3s';
            circle.innerHTML = i;
            label.style.color = '#c84ddf';
        } else {
            circle.style.cssText = 'width:36px;height:36px;border-radius:50%;background:var(--input-bg);color:var(--text-muted);border:2px solid var(--card-border);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;transition:.3s';
            circle.innerHTML = i;
            label.style.color = 'var(--text-muted)';
        }
        if (line) line.style.background = i < n ? '#10b981' : 'var(--card-border)';
    }
}

function showSection(id) { const el = document.getElementById(id); if (el) el.style.display = ''; }
function hideSection(id) { const el = document.getElementById(id); if (el) el.style.display = 'none'; }
function scrollTo(id)    { const el = document.getElementById(id); if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' }); }

// ─── STEP 1: Paket ────────────────────────────────────────────────────────

function onPaketChange(paketId) {
    currentPaket   = null;
    currentMapelId = null;
    busyTeacherIds = [];

    // Reset downstream
    hideSection('section-waktu');
    hideSection('section-konflik');
    hideSection('section-guru');
    hideSection('mapelBox');
    hideSection('siswaPaketBox');
    hideSection('step1NextBtn');
    activateStep(1);

    if (!paketId) return;

    const pkg = pakets.find(p => p.id == paketId);
    if (!pkg) return;
    currentPaket = pkg;

    // Mata pelajaran: single lock vs multi pick
    showSection('mapelBox');
    if (pkg.mata_pelajaran.length === 1) {
        const mp = pkg.mata_pelajaran[0];
        document.getElementById('mapelSingleBadge').textContent = mp.nama;
        document.getElementById('mata_pelajaran_id_hidden').value = mp.id;
        currentMapelId = mp.id;
        showSection('mapelSingleLock');
        hideSection('mapelMultiPick');
    } else {
        let opts = '<option value="">— Pilih mata pelajaran —</option>';
        pkg.mata_pelajaran.forEach(m => {
            opts += `<option value="${m.id}">${m.nama}${m.kategori ? ' ('+m.kategori+')' : ''}</option>`;
        });
        document.getElementById('mata_pelajaran_id').innerHTML = opts;
        document.getElementById('mata_pelajaran_id').onchange = function() {
            currentMapelId = this.value ? parseInt(this.value) : null;
            checkStep1Complete();
        };
        hideSection('mapelSingleLock');
        showSection('mapelMultiPick');
    }

    // Students
    showSection('siswaPaketBox');
    loadStudentsByPackage(paketId);

    // Auto-detect metode from package
    const validJenis = ['online', 'offline', 'private'];
    const autoJenis  = validJenis.includes(pkg.tipe_kelas) ? pkg.tipe_kelas : 'offline';
    selectMetode(autoJenis);

    checkStep1Complete();
}

function checkStep1Complete() {
    const paketId = document.getElementById('paket_id').value;
    const mapelOk = currentPaket && (currentPaket.mata_pelajaran.length === 1 ? true : !!currentMapelId);

    if (paketId && mapelOk) {
        showSection('step1NextBtn');
    } else {
        hideSection('step1NextBtn');
    }
}

function goToStep2() {
    showSection('section-waktu');
    activateStep(2);
    scrollTo('section-waktu');
    onWaktuChange();
}

// ─── STEP 2: Waktu & Lokasi ───────────────────────────────────────────────

function selectMetode(val) {
    document.getElementById('jenis').value = val;
    document.querySelectorAll('.metode-card').forEach(card => {
        const isActive = card.dataset.value === val;
        card.style.border         = isActive ? '2px solid #c84ddf' : '2px solid var(--card-border)';
        card.style.background     = isActive ? 'var(--soft-primary-bg)' : 'var(--input-bg)';
        card.style.color          = isActive ? '#461256' : 'inherit';
        card.querySelector('.fw-semibold').style.color = isActive ? '#461256' : 'inherit';
    });

    // Show/hide location fields
    const isOffline   = val === 'offline';
    const isOnline    = val === 'online';
    const isHomeVisit = val === 'private';
    document.getElementById('lokasiOffline').style.display   = isOffline   ? '' : 'none';
    document.getElementById('lokasiOnline').style.display    = isOnline    ? '' : 'none';
    document.getElementById('lokasiHomeVisit').style.display = isHomeVisit ? '' : 'none';

    const titles = { offline: '🏫 Lokasi Kelas — Ruang Fisik', online: '💻 Lokasi Kelas — Online', private: '🏠 Lokasi Kelas — Kunjungan Rumah' };
    document.getElementById('lokasiTitle').textContent = titles[val] || 'Lokasi Kelas';
}

function onWaktuChange() {
    const tanggal   = document.getElementById('tanggal').value;
    const jamMulai  = document.getElementById('jam_mulai').value;
    const jamSelesai= document.getElementById('jam_selesai').value;

    if (tanggal && jamMulai && jamSelesai) {
        showSection('cekKonflikCTA');
    } else {
        hideSection('cekKonflikCTA');
    }

    // Reset downstream steps if time changed
    hideSection('section-konflik');
    hideSection('section-guru');
    activateStep(2);
}

function backToStep2() {
    hideSection('section-konflik');
    hideSection('section-guru');
    activateStep(2);
    scrollTo('section-waktu');
}

// ─── STEP 3: Cek Konflik ─────────────────────────────────────────────────

async function runConflictCheck() {
    const btn = document.getElementById('btnCekKonflik');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memeriksa konflik...';

    const jenis    = document.getElementById('jenis').value;
    const ruangan  = jenis === 'offline' ? (document.getElementById('ruangan').value || '') : '';
    const cabangId = currentPaket?.cabang_id || '';

    const payload = new FormData();
    payload.append('_token', csrf);
    payload.append('tanggal',    document.getElementById('tanggal').value);
    payload.append('jam_mulai',  document.getElementById('jam_mulai').value);
    payload.append('jam_selesai',document.getElementById('jam_selesai').value);
    payload.append('ruangan',    ruangan);
    payload.append('cabang_id',  cabangId);

    try {
        const res  = await fetch(conflictCheckUrl, { method: 'POST', body: payload });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const json = await res.json();
        const data = json.data || {};

        busyTeacherIds = data.busy_teacher_ids || [];
        renderKonflikResults(data, jenis, ruangan);

        showSection('section-konflik');
        const konflikActions = document.getElementById('konflikActions');
        konflikActions.style.removeProperty('display');
        activateStep(3);
        scrollTo('section-konflik');
    } catch (err) {
        showToast('Gagal menghubungi server. Coba lagi.', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-shield-check me-2"></i>Cek Konflik & Lanjut Penugasan Guru';
    }
}

function renderKonflikResults(data, jenis, ruangan) {
    let html = '<div class="row g-3">';

    // Room result
    if (jenis === 'offline' && ruangan) {
        const r = data.room;
        if (r) {
            const ok = !r.conflict;
            html += `
            <div class="col-md-6">
                <div class="p-3 rounded-3 d-flex align-items-start gap-3" style="background:${ok ? 'var(--soft-success-bg,#d1fae5)' : '#fef2f2'};border:1.5px solid ${ok ? '#10b981' : '#ef4444'}">
                    <div style="font-size:22px;line-height:1">${ok ? '✅' : '❌'}</div>
                    <div>
                        <div class="fw-semibold" style="font-size:13px;color:${ok ? '#047857' : '#b91c1c'}">
                            ${ok ? 'Ruangan Tersedia' : 'Ruangan Bentrok!'}
                        </div>
                        <div style="font-size:12px;color:var(--text-muted)">${r.detail}</div>
                    </div>
                </div>
            </div>`;
        }
    } else if (jenis === 'online') {
        html += `
        <div class="col-md-6">
            <div class="p-3 rounded-3 d-flex align-items-start gap-3" style="background:var(--soft-info-bg,#eff6ff);border:1.5px solid #3b82f6">
                <div style="font-size:22px;line-height:1">💻</div>
                <div>
                    <div class="fw-semibold" style="font-size:13px;color:#1d4ed8">Kelas Online</div>
                    <div style="font-size:12px;color:var(--text-muted)">Tidak ada pengecekan ruang fisik untuk kelas online.</div>
                </div>
            </div>
        </div>`;
    } else {
        html += `
        <div class="col-md-6">
            <div class="p-3 rounded-3 d-flex align-items-start gap-3" style="background:#fffbeb;border:1.5px solid #f59e0b">
                <div style="font-size:22px;line-height:1">🏠</div>
                <div>
                    <div class="fw-semibold" style="font-size:13px;color:#92400e">Home Visit</div>
                    <div style="font-size:12px;color:var(--text-muted)">Kunjungan ke alamat siswa — tidak ada pengecekan ruang.</div>
                </div>
            </div>
        </div>`;
    }

    // Teacher availability summary
    const busyCount  = busyTeacherIds.length;
    const totalCount = teachers.length;
    const freeCount  = totalCount - busyCount;
    html += `
    <div class="col-md-6">
        <div class="p-3 rounded-3 d-flex align-items-start gap-3" style="background:${freeCount > 0 ? 'var(--soft-success-bg,#d1fae5)' : '#fef2f2'};border:1.5px solid ${freeCount > 0 ? '#10b981' : '#ef4444'}">
            <div style="font-size:22px;line-height:1">${freeCount > 0 ? '👨‍🏫' : '⚠️'}</div>
            <div>
                <div class="fw-semibold" style="font-size:13px;color:${freeCount > 0 ? '#047857' : '#b91c1c'}">
                    ${freeCount} Guru Tersedia di Waktu Ini
                </div>
                <div style="font-size:12px;color:var(--text-muted)">${busyCount} guru sedang mengajar kelas lain di jam ini.</div>
            </div>
        </div>
    </div>`;

    html += '</div>';

    // Room conflict hard-block warning
    if (data.room?.conflict) {
        html += `
        <div class="alert alert-warning mt-3 mb-0">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Ruangan sudah terpakai.</strong> Silakan ganti ruangan atau ubah waktu sebelum melanjutkan.
        </div>`;
        document.getElementById('btnLanjutGuru').disabled = true;
    } else {
        document.getElementById('btnLanjutGuru').disabled = false;
    }

    document.getElementById('konflikResults').innerHTML = html;
}

function goToStep4() {
    showSection('section-guru');
    activateStep(4);
    renderGuruList();
    scrollTo('section-guru');
    // Update mata pelajaran summary label
    if (currentPaket) {
        const mp = currentPaket.mata_pelajaran.find(m => m.id == currentMapelId) || currentPaket.mata_pelajaran[0];
        document.getElementById('mapelSummaryLabel').textContent = mp ? mp.nama : '—';
    }
}

function backToStep3() {
    hideSection('section-guru');
    activateStep(3);
    scrollTo('section-konflik');
}

// ─── STEP 4: Guru & Honor ────────────────────────────────────────────────

function renderGuruList() {
    if (!currentPaket) return;
    const mapelId = currentMapelId ? parseInt(currentMapelId) : null;

    // Determine preferred teachers (from package-course-teacher assignments)
    let pkgTeacherIds = [];
    if (mapelId && currentPaket.course_teachers) {
        const ct = currentPaket.course_teachers[mapelId];
        if (ct && ct.length > 0) pkgTeacherIds = ct.map(Number);
    }

    // Categorize: preferred + available / preferred + busy / other available / other busy
    let preferred = [], others = [];
    teachers.forEach(t => {
        const isPref = pkgTeacherIds.length > 0
            ? pkgTeacherIds.includes(t.id)
            : (mapelId ? t.course_ids.includes(mapelId) : false);
        if (isPref) preferred.push(t); else others.push(t);
    });

    let html = '';
    if (preferred.length > 0) {
        html += `<div class="col-12 mb-1"><div class="text-muted fw-semibold" style="font-size:11px;text-transform:uppercase;letter-spacing:.5px">✅ Guru sesuai mata pelajaran (${preferred.length})</div></div>`;
        preferred.forEach(t => { html += renderGuruCard(t, true); });
    }
    if (others.length > 0) {
        html += `<div class="col-12 mt-2 mb-1"><div class="text-muted fw-semibold" style="font-size:11px;text-transform:uppercase;letter-spacing:.5px">— Guru lainnya (${others.length})</div></div>`;
        others.forEach(t => { html += renderGuruCard(t, false); });
    }
    if (!html) html = '<div class="col-12 text-muted" style="font-size:13px">Belum ada guru aktif.</div>';

    document.getElementById('guruList').innerHTML = html;
    document.getElementById('guruNote').textContent = pkgTeacherIds.length > 0
        ? `${preferred.length} guru ditugaskan untuk mapel ini dalam paket — tampil di atas.`
        : (preferred.length > 0 ? `${preferred.length} guru terdaftar mengajar mapel ini.` : 'Belum ada guru yang ditetapkan untuk mapel ini.');

    // Re-select if previously chosen
    if (selectedGuruId) selectGuru(selectedGuruId);
}

function renderGuruCard(t, isPreferred) {
    const isBusy = busyTeacherIds.includes(t.id);
    const badgeBg    = isBusy ? '#fef2f2'    : (isPreferred ? 'var(--soft-success-bg,#d1fae5)' : 'var(--input-bg)');
    const badgeBorder= isBusy ? '#ef4444'    : (isPreferred ? '#10b981' : 'var(--card-border)');
    const statusBadge= isBusy
        ? `<span style="background:#fee2e2;color:#b91c1c;padding:2px 8px;border-radius:10px;font-size:10px;font-weight:600">⚠️ Konflik Jadwal</span>`
        : `<span style="background:#d1fae5;color:#047857;padding:2px 8px;border-radius:10px;font-size:10px;font-weight:600">✅ Tersedia</span>`;

    return `
    <div class="col-md-4 col-sm-6">
        <div class="guru-card p-3 rounded-3" data-id="${t.id}" data-name="${t.name}"
             style="border:2px solid ${badgeBorder};background:${badgeBg};cursor:${isBusy ? 'not-allowed' : 'pointer'};transition:.2s;opacity:${isBusy ? '.6' : '1'}"
             ${isBusy ? '' : `onclick="selectGuru(${t.id})"`}>
            <div class="d-flex align-items-center gap-2 mb-2">
                <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;font-weight:700;flex-shrink:0">
                    ${t.name.charAt(0).toUpperCase()}
                </div>
                <div>
                    <div class="fw-semibold" style="font-size:13px;line-height:1.3">${t.name}</div>
                    ${t.branch ? `<div class="text-muted" style="font-size:10px">${t.branch}</div>` : ''}
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between">
                ${statusBadge}
                ${t.nig ? `<span class="text-muted" style="font-size:10px">NIG: ${t.nig}</span>` : ''}
            </div>
        </div>
    </div>`;
}

function selectGuru(guruId) {
    selectedGuruId = guruId;
    document.getElementById('guru_id').value = guruId;

    document.querySelectorAll('.guru-card').forEach(card => {
        const isSelected = parseInt(card.dataset.id) === guruId;
        const isBusy     = busyTeacherIds.includes(parseInt(card.dataset.id));
        if (!isBusy) {
            card.style.border = isSelected ? '2px solid #c84ddf' : '2px solid var(--card-border)';
            card.style.boxShadow = isSelected ? '0 0 0 3px rgba(200,77,223,.15)' : '';
        }
    });

    const t = teachers.find(x => x.id == guruId);
    if (t) {
        const honorInfo = document.getElementById('honorInfo');
        honorInfo.innerHTML = `<i class="bi bi-person-check me-1 text-success"></i><strong>${t.name}</strong> dipilih sebagai guru pengajar. Masukkan honor per sesi jika ada kesepakatan.`;
    }

    document.getElementById('submitBtn').disabled = false;
}

// ─── Sessions / progress bar ─────────────────────────────────────────────

function buildSesiOptions(paketId, pkg) {
    // kept as stub for legacy references; no-op now
}

function loadUsedSessions(paketId, pkg) {
    return Promise.resolve();
}


// ─── Students box ────────────────────────────────────────────────────────

function loadStudentsByPackage(paketId) {
    const content = document.getElementById('siswaPaketContent');
    const counter = document.getElementById('siswaPaketCount');
    content.innerHTML = '<span class="text-muted" style="font-size:12px"><i class="bi bi-hourglass-split me-1"></i>Memuat siswa...</span>';
    counter.textContent = '';
    fetch(`${packageStudentsBaseUrl}/${paketId}/students`, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf }
    })
    .then(r => { if (!r.ok) throw new Error(); return r.json(); })
    .then(data => {
        const students = data.students || [];
        counter.textContent = students.length + ' siswa';
        content.innerHTML = students.length
            ? students.map(s => `<span style="background:var(--soft-primary);color:#461256;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:500;display:inline-flex;align-items:center;gap:4px"><i class="bi bi-person-fill"></i>${s.name}${s.nis ? `<span style="opacity:.65;font-size:10px">#${s.nis}</span>` : ''}</span>`).join('')
            : '<span class="text-muted" style="font-size:12px"><i class="bi bi-info-circle me-1"></i>Belum ada siswa terdaftar di paket ini.</span>';
    })
    .catch(() => {
        content.innerHTML = '<span class="text-muted" style="font-size:12px"><i class="bi bi-exclamation-circle me-1"></i>Gagal memuat daftar siswa.</span>';
    });
}

// ─── Module info ─────────────────────────────────────────────────────────

function onModuleChange(moduleId) { /* no visual display needed */ }

// ─── Init ────────────────────────────────────────────────────────────────

activateStep(1);
// Pre-select metode card
selectMetode(document.getElementById('jenis').value || 'offline');

const initPaket = document.getElementById('paket_id').value;
if (initPaket) {
    onPaketChange(initPaket);
}
</script>
@endpush
