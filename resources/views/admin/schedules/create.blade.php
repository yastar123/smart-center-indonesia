@extends('layouts.app')
@section('title','Buat Jadwal Kelas')
@section('page-title','Buat Jadwal Kelas')

@php
$conflictCheckUrl      = route('admin.schedules.conflict-check');
$subjectStudentsBase   = '/admin/schedules/subject';
$teacherStatsBase      = '/admin/schedules/teacher';
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
                <div style="font-size:12px;opacity:.8">Pilih mata pelajaran → tentukan siswa, waktu & guru → simpan</div>
            </div>
        </div>
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-sm"
           style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.3)">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form action="{{ route('admin.schedules.store') }}" method="POST" id="scheduleForm">
@csrf
@if($errors->any())
<div class="alert alert-danger mb-4">
    <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

{{-- ═══════════════════════════════════════════════════════ --}}
{{-- BAGIAN 1: MATA PELAJARAN & SISWA                        --}}
{{-- ═══════════════════════════════════════════════════════ --}}
<div class="dashboard-card mb-4">
    <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#c84ddf,#461256);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
            <i class="bi bi-book"></i>
        </div>
        <div>
            <div class="fw-bold" style="font-size:15px">Langkah 1 — Mata Pelajaran &amp; Siswa</div>
            <div class="text-muted" style="font-size:12px">Pilih mata pelajaran lalu tentukan siswa yang ikut sesi ini</div>
        </div>
    </div>

    {{-- PILIH MATA PELAJARAN --}}
    <div class="mb-4">
        <label class="form-label fw-semibold">Mata Pelajaran <span class="text-danger">*</span></label>
        <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-select" required onchange="onCourseChange(this.value)">
            <option value="">— Pilih Mata Pelajaran —</option>
            @foreach($courses as $c)
            <option value="{{ $c->id }}" data-kategori="{{ $c->kategori ?? '' }}" {{ old('mata_pelajaran_id') == $c->id ? 'selected' : '' }}>
                {{ $c->nama }}{{ $c->kategori ? ' ('.$c->kategori.')' : '' }}
            </option>
            @endforeach
        </select>
        <div class="form-text">Data mata pelajaran dikelola di halaman <a href="{{ route('owner.subject.index') }}" target="_blank">Master Mata Pelajaran</a></div>
    </div>

    {{-- DAFTAR SISWA (muncul setelah pilih mapel) --}}
    <div id="siswaBox" style="display:none">
        <div class="p-3 rounded-3" style="border:1px solid var(--card-border);background:var(--input-bg)">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="fw-semibold" style="font-size:13px">
                    <i class="bi bi-people-fill text-primary me-2"></i>
                    Siswa yang Mengambil Mata Pelajaran Ini
                    <span id="siswaCount" class="badge ms-2" style="background:var(--soft-primary);color:#461256;font-size:11px"></span>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="toggleAllSiswa(true)">
                        <i class="bi bi-check2-all me-1"></i>Pilih Semua
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleAllSiswa(false)">
                        <i class="bi bi-x-lg me-1"></i>Hapus Semua
                    </button>
                </div>
            </div>
            <div id="siswaLoading" class="text-muted py-2" style="font-size:13px">
                <span class="spinner-border spinner-border-sm me-2"></span>Memuat daftar siswa...
            </div>
            <div id="siswaTable" style="display:none">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0" style="font-size:13px">
                        <thead>
                            <tr style="background:var(--input-bg)">
                                <th style="width:40px;color:var(--text-muted)"><input type="checkbox" id="checkAll" class="form-check-input" onchange="toggleAllSiswa(this.checked)"></th>
                                <th style="color:var(--text-muted)">Nama Siswa</th>
                                <th style="color:var(--text-muted)">NIS</th>
                                <th style="color:var(--text-muted);text-align:center">Sesi Dipakai</th>
                                <th style="color:var(--text-muted);text-align:center">Sisa Sesi</th>
                                <th style="color:var(--text-muted);text-align:center">Status</th>
                            </tr>
                        </thead>
                        <tbody id="siswaTbody"></tbody>
                    </table>
                </div>
                <div id="siswaEmpty" class="text-muted text-center py-3" style="font-size:13px;display:none">
                    <i class="bi bi-inbox me-2"></i>Belum ada siswa aktif yang mengambil mata pelajaran ini.
                </div>
            </div>
        </div>
        <div class="mt-2 text-muted" style="font-size:12px">
            <i class="bi bi-info-circle me-1"></i>
            Centang siswa yang <strong>ikut</strong> sesi ini. Siswa tanpa sisa sesi masih bisa dipilih (untuk monitoring).
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════ --}}
{{-- BAGIAN 2: PROGRAM & WAKTU                               --}}
{{-- ═══════════════════════════════════════════════════════ --}}
<div class="dashboard-card mb-4">
    <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#0ea5e9,#0369a1);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
            <i class="bi bi-clock"></i>
        </div>
        <div>
            <div class="fw-bold" style="font-size:15px">Langkah 2 — Program &amp; Waktu</div>
            <div class="text-muted" style="font-size:12px">Pilih program belajar, sistem belajar, tanggal, jam, dan lokasi</div>
        </div>
    </div>

    {{-- PROGRAM BELAJAR --}}
    <div class="mb-4">
        <label class="form-label fw-semibold">Program Belajar <span class="text-danger">*</span></label>
        <div class="row g-3" id="programPicker">
            <div class="col-md-6">
                <div class="program-card p-3 rounded-3 d-flex align-items-center gap-3" data-value="kelas"
                     style="border:2px solid var(--card-border);cursor:pointer;transition:.25s;background:var(--input-bg)"
                     onclick="selectProgram('kelas')">
                    <div style="font-size:28px;flex-shrink:0"><i class="bi bi-people-fill text-primary"></i></div>
                    <div>
                        <div class="fw-semibold" style="font-size:14px">Kelas</div>
                        <div class="text-muted" style="font-size:11px">Belajar bersama dalam kelompok</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="program-card p-3 rounded-3 d-flex align-items-center gap-3" data-value="private"
                     style="border:2px solid var(--card-border);cursor:pointer;transition:.25s;background:var(--input-bg)"
                     onclick="selectProgram('private')">
                    <div style="font-size:28px;flex-shrink:0"><i class="bi bi-person-fill text-warning"></i></div>
                    <div>
                        <div class="fw-semibold" style="font-size:14px">Private</div>
                        <div class="text-muted" style="font-size:11px">Belajar 1-on-1 dengan guru</div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="program_belajar" id="program_belajar" value="{{ old('program_belajar','kelas') }}" required>
    </div>

    {{-- SISTEM BELAJAR --}}
    <div class="mb-4">
        <label class="form-label fw-semibold">Sistem Belajar <span class="text-danger">*</span></label>
        <div class="row g-3" id="sistemPicker">
            <div class="col-md-6">
                <div class="sistem-card p-3 rounded-3 d-flex align-items-center gap-3" data-value="offline"
                     style="border:2px solid var(--card-border);cursor:pointer;transition:.25s;background:var(--input-bg)"
                     onclick="selectSistem('offline')">
                    <div style="font-size:28px;flex-shrink:0"><i class="bi bi-building"></i></div>
                    <div>
                        <div class="fw-semibold" style="font-size:14px">Offline</div>
                        <div class="text-muted" style="font-size:11px">Tatap muka di ruang kelas</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="sistem-card p-3 rounded-3 d-flex align-items-center gap-3" data-value="online"
                     style="border:2px solid var(--card-border);cursor:pointer;transition:.25s;background:var(--input-bg)"
                     onclick="selectSistem('online')">
                    <div style="font-size:28px;flex-shrink:0"><i class="bi bi-camera-video"></i></div>
                    <div>
                        <div class="fw-semibold" style="font-size:14px">Online</div>
                        <div class="text-muted" style="font-size:11px">Via Zoom / Google Meet</div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="jenis" id="jenis" value="{{ old('jenis','offline') }}" required>
    </div>

    {{-- TANGGAL & JAM --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
            <input type="date" name="tanggal" id="tanggal" class="form-control"
                   value="{{ old('tanggal', date('Y-m-d')) }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
            <input type="time" name="jam_mulai" id="jam_mulai" class="form-control"
                   value="{{ old('jam_mulai') }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
            <input type="time" name="jam_selesai" id="jam_selesai" class="form-control"
                   value="{{ old('jam_selesai') }}" required>
        </div>
    </div>

    {{-- LOKASI --}}
    <div class="mb-4 p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
        <div class="fw-semibold mb-3" style="font-size:13px" id="lokasiTitle">
            <i class="bi bi-geo-alt me-2"></i>Lokasi Kelas
        </div>

        <div id="lokasiOffline">
            <label class="form-label fw-semibold">Nama Ruangan <span class="text-muted fw-normal">(opsional)</span></label>
            <div class="input-group" style="max-width:400px">
                <span class="input-group-text" style="background:var(--input-bg);border-color:var(--card-border)"><i class="bi bi-door-open text-muted"></i></span>
                <input type="text" name="ruangan" id="ruangan" class="form-control"
                       value="{{ old('ruangan') }}" placeholder="cth: Ruang A1, Ruang B2...">
            </div>
        </div>
        <div id="lokasiOnline" style="display:none">
            <label class="form-label fw-semibold">Link Zoom / Google Meet</label>
            <div class="input-group" style="max-width:500px">
                <span class="input-group-text" style="background:var(--input-bg);border-color:var(--card-border)"><i class="bi bi-camera-video text-muted"></i></span>
                <input type="url" name="link_meeting" id="link_meeting" class="form-control"
                       value="{{ old('link_meeting') }}" placeholder="https://zoom.us/j/...">
            </div>
        </div>
    </div>

    {{-- TOPIK & CATATAN --}}
    <div class="row g-3">
        <div class="col-lg-6">
            <label class="form-label fw-semibold">Topik / Materi <span class="text-muted fw-normal">(opsional)</span></label>
            <input type="text" name="topik" class="form-control" value="{{ old('topik') }}"
                   placeholder="cth: Persamaan Kuadrat, Present Tense...">
        </div>
        <div class="col-lg-6">
            <label class="form-label fw-semibold">Catatan <span class="text-muted fw-normal">(opsional)</span></label>
            <input type="text" name="catatan" class="form-control" value="{{ old('catatan') }}"
                   placeholder="Catatan tambahan untuk sesi ini">
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════ --}}
{{-- BAGIAN 3: CEK KONFLIK (opsional)                        --}}
{{-- ═══════════════════════════════════════════════════════ --}}
<div class="dashboard-card mb-4">
    <div class="d-flex align-items-center gap-2 mb-3 pb-3 border-bottom">
        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#8b5cf6,#6d28d9);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
            <i class="bi bi-shield-check"></i>
        </div>
        <div>
            <div class="fw-bold" style="font-size:15px">Langkah 3 — Cek Konflik <span class="text-muted fw-normal" style="font-size:12px">(opsional)</span></div>
            <div class="text-muted" style="font-size:12px">Periksa ketersediaan ruangan dan guru di waktu yang dipilih</div>
        </div>
    </div>

    <button type="button" class="btn btn-outline-primary btn-sm px-4" onclick="runConflictCheck()" id="btnCekKonflik">
        <i class="bi bi-shield-check me-2"></i>Cek Konflik Sekarang
    </button>
    <div id="konflikResults" class="mt-3"></div>
</div>

{{-- ═══════════════════════════════════════════════════════ --}}
{{-- BAGIAN 4: GURU & HONOR                                  --}}
{{-- ═══════════════════════════════════════════════════════ --}}
<div class="dashboard-card mb-4">
    <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#10b981,#047857);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
            <i class="bi bi-person-badge"></i>
        </div>
        <div>
            <div class="fw-bold" style="font-size:15px">Langkah 4 — Guru &amp; Honor</div>
            <div class="text-muted" style="font-size:12px">Pilih guru pengajar dan kunci nominal honor per sesi</div>
        </div>
    </div>

    {{-- MAPEL SUMMARY --}}
    <div class="mb-3 p-3 rounded-3" style="background:var(--soft-primary-bg,rgba(200,77,223,.08));border:1.5px solid var(--soft-primary-border,rgba(200,77,223,.2))">
        <div class="d-flex align-items-center gap-2" style="font-size:13px">
            <i class="bi bi-book text-primary"></i>
            <span class="fw-semibold">Mata Pelajaran:</span>
            <span id="mapelSummaryLabel" class="badge" style="background:var(--soft-primary);color:#461256;font-size:12px">—</span>
            <span class="text-muted" style="font-size:11px">| Guru sesuai mata pelajaran ditampilkan terlebih dahulu.</span>
        </div>
    </div>

    {{-- TEACHER LIST --}}
    <div class="mb-4">
        <label class="form-label fw-semibold">Pilih Guru Pengajar <span class="text-danger">*</span></label>
        <div id="guruList" class="row g-2">
            <div class="col-12 text-muted" style="font-size:13px">
                <i class="bi bi-info-circle me-1"></i>Pilih mata pelajaran terlebih dahulu untuk melihat daftar guru.
            </div>
        </div>
        <input type="hidden" name="guru_id" id="guru_id" required>
    </div>

    {{-- GURU STATS (muncul setelah pilih guru) --}}
    <div id="guruStatsBox" style="display:none" class="mb-4 p-3 rounded-3" style="border:1px solid var(--card-border);background:var(--input-bg)">
        <div class="fw-semibold mb-3" style="font-size:13px"><i class="bi bi-bar-chart-line me-2 text-primary"></i>Statistik Guru Terpilih</div>
        <div class="row g-3" id="guruStatsContent"></div>
    </div>

    {{-- HONOR PER SESI --}}
    <div class="p-3 rounded-3 mb-4" style="background:var(--input-bg);border:1px solid var(--card-border)">
        <div class="fw-semibold mb-1" style="font-size:13px">
            <i class="bi bi-cash-coin me-2" style="color:#f6af23"></i>Honor Guru per Sesi
        </div>
        <div class="text-muted mb-3" style="font-size:11px">Honor ini akan menjadi dasar penggajian guru untuk setiap sesi yang terlaksana.</div>
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
                <div id="honorDisplay" class="text-muted" style="font-size:12px">
                    <i class="bi bi-info-circle me-1"></i>Masukkan nominal honor per sesi yang disepakati.
                </div>
            </div>
        </div>
    </div>

    {{-- SUBMIT --}}
    <div class="pt-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="text-muted" style="font-size:12px">
            <i class="bi bi-info-circle me-1"></i>Menyimpan jadwal akan otomatis menyiapkan draft absensi untuk siswa yang dipilih.
        </div>
        <button type="submit" id="submitBtn" class="btn btn-primary px-5 fw-semibold" disabled>
            <i class="bi bi-calendar-check me-2"></i>Simpan Jadwal
        </button>
    </div>
</div>

</form>
</div>
@endsection

@php
$teachersJson = $teachers->map(function ($t) {
    return [
        'id'         => $t->id,
        'name'       => $t->name,
        'branch'     => $t->branch?->name,
        'branch_id'  => $t->branch_id,
        'nig'        => $t->nig,
        'email'      => $t->email,
        'jenis_guru' => $t->jenis_guru,
        'course_ids' => $t->courses->pluck('id')->values()->toArray(),
    ];
});

$coursesJson = $courses->map(fn($c) => [
    'id'       => $c->id,
    'nama'     => $c->nama,
    'kategori' => $c->kategori ?? '',
]);
@endphp

@push('scripts')
<script>
const teachers             = @json($teachersJson);
const courses              = @json($coursesJson);
const csrf                 = document.querySelector('meta[name="csrf-token"]').content;
const conflictCheckUrl     = @json($conflictCheckUrl);
const subjectStudentsBase  = @json($subjectStudentsBase);
const teacherStatsBase     = @json($teacherStatsBase);

let currentCourseId  = null;
let busyTeacherIds   = [];
let selectedGuruId   = null;

// ─── Helpers ───────────────────────────────────────────────────────────────

function showEl(id) { const el = document.getElementById(id); if (el) el.style.display = ''; }
function hideEl(id) { const el = document.getElementById(id); if (el) el.style.display = 'none'; }

function fmt(n) {
    return new Intl.NumberFormat('id-ID').format(n);
}

// ─── Step 1: Course change ─────────────────────────────────────────────────

function onCourseChange(courseId) {
    currentCourseId  = courseId ? parseInt(courseId) : null;
    selectedGuruId   = null;
    busyTeacherIds   = [];

    document.getElementById('guru_id').value = '';
    document.getElementById('submitBtn').disabled = true;
    document.getElementById('konflikResults').innerHTML = '';
    hideEl('guruStatsBox');
    document.getElementById('guruList').innerHTML =
        '<div class="col-12 text-muted" style="font-size:13px"><i class="bi bi-info-circle me-1"></i>Pilih mata pelajaran terlebih dahulu untuk melihat daftar guru.</div>';

    const course = courses.find(c => c.id == courseId);
    const label  = course ? course.nama : '—';
    document.getElementById('mapelSummaryLabel').textContent = label;

    if (!courseId) {
        hideEl('siswaBox');
        return;
    }

    // Show students section and load data
    showEl('siswaBox');
    loadStudentsByCourse(courseId);

    // Render guru list filtered by this course
    renderGuruList();
}

// ─── Load students by course ───────────────────────────────────────────────

function loadStudentsByCourse(courseId) {
    const loading = document.getElementById('siswaLoading');
    const table   = document.getElementById('siswaTable');
    const empty   = document.getElementById('siswaEmpty');
    const count   = document.getElementById('siswaCount');

    loading.style.display = '';
    table.style.display   = 'none';
    empty.style.display   = 'none';
    count.textContent     = '';

    fetch(`${subjectStudentsBase}/${courseId}/students`, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf }
    })
    .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
    .then(data => {
        loading.style.display = 'none';
        const students = data.students || [];
        count.textContent = students.length + ' siswa';

        if (students.length === 0) {
            table.style.display  = '';
            empty.style.display  = '';
            document.getElementById('siswaTbody').innerHTML = '';
            return;
        }

        table.style.display = '';
        empty.style.display = 'none';

        let rows = '';
        students.forEach(s => {
            const sisaClass  = s.sisa_sesi <= 0 ? 'color:#b91c1c;font-weight:700' : (s.sisa_sesi <= 3 ? 'color:#d97706;font-weight:600' : 'color:#047857;font-weight:600');
            const statusBadge = s.sisa_sesi <= 0
                ? '<span style="background:#fee2e2;color:#b91c1c;padding:2px 8px;border-radius:10px;font-size:10px">Habis</span>'
                : (s.sisa_sesi <= 3
                    ? '<span style="background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:10px;font-size:10px">Hampir Habis</span>'
                    : '<span style="background:#d1fae5;color:#047857;padding:2px 8px;border-radius:10px;font-size:10px">Aktif</span>');

            rows += `<tr>
                <td class="align-middle">
                    <input type="checkbox" name="siswa_ids[]" value="${s.id}"
                           class="form-check-input siswa-check" checked
                           onchange="updateSiswaCount()">
                </td>
                <td class="align-middle fw-semibold">${s.name}</td>
                <td class="align-middle text-muted" style="font-size:12px">${s.nis || '—'}</td>
                <td class="align-middle text-center">${s.sesi_terpakai} / ${s.sesi_paket}</td>
                <td class="align-middle text-center" style="${sisaClass}">${s.sisa_sesi} sesi</td>
                <td class="align-middle text-center">${statusBadge}</td>
            </tr>`;
        });
        document.getElementById('siswaTbody').innerHTML = rows;
        updateSiswaCount();
    })
    .catch(() => {
        loading.style.display = 'none';
        table.style.display   = '';
        document.getElementById('siswaTbody').innerHTML =
            '<tr><td colspan="6" class="text-center text-muted py-3"><i class="bi bi-exclamation-circle me-2"></i>Gagal memuat daftar siswa. Coba lagi.</td></tr>';
    });
}

function updateSiswaCount() {
    const total    = document.querySelectorAll('.siswa-check').length;
    const checked  = document.querySelectorAll('.siswa-check:checked').length;
    document.getElementById('checkAll').checked        = checked === total && total > 0;
    document.getElementById('checkAll').indeterminate  = checked > 0 && checked < total;
}

function toggleAllSiswa(state) {
    document.querySelectorAll('.siswa-check').forEach(c => c.checked = state);
    const checkAll = document.getElementById('checkAll');
    if (checkAll) checkAll.checked = state;
    updateSiswaCount();
}

// ─── Step 2: Program Belajar & Sistem Belajar ──────────────────────────────

function selectProgram(val) {
    document.getElementById('program_belajar').value = val;
    document.querySelectorAll('.program-card').forEach(card => {
        const active = card.dataset.value === val;
        card.style.border     = active ? '2px solid #c84ddf' : '2px solid var(--card-border)';
        card.style.background = active ? 'var(--soft-primary-bg,rgba(200,77,223,.08))' : 'var(--input-bg)';
    });
}

function selectSistem(val) {
    document.getElementById('jenis').value = val;
    document.querySelectorAll('.sistem-card').forEach(card => {
        const active = card.dataset.value === val;
        card.style.border     = active ? '2px solid #0ea5e9' : '2px solid var(--card-border)';
        card.style.background = active ? 'rgba(14,165,233,.08)' : 'var(--input-bg)';
    });
    const lokasiTitles = { offline: '🏫 Lokasi Kelas — Ruang Fisik', online: '💻 Lokasi Kelas — Online' };
    document.getElementById('lokasiTitle').innerHTML = '<i class="bi bi-geo-alt me-2"></i>' + (lokasiTitles[val] || 'Lokasi Kelas');
    document.getElementById('lokasiOffline').style.display = val === 'offline' ? '' : 'none';
    document.getElementById('lokasiOnline').style.display  = val === 'online'  ? '' : 'none';
}

// ─── Step 3: Cek Konflik ───────────────────────────────────────────────────

async function runConflictCheck() {
    const btn = document.getElementById('btnCekKonflik');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memeriksa...';

    const jenis    = document.getElementById('jenis').value;
    const ruangan  = jenis === 'offline' ? (document.getElementById('ruangan').value || '') : '';

    const payload = new FormData();
    payload.append('_token',     csrf);
    payload.append('tanggal',    document.getElementById('tanggal').value);
    payload.append('jam_mulai',  document.getElementById('jam_mulai').value);
    payload.append('jam_selesai',document.getElementById('jam_selesai').value);
    payload.append('ruangan',    ruangan);
    if (selectedGuruId) payload.append('guru_id', selectedGuruId);

    try {
        const res  = await fetch(conflictCheckUrl, { method: 'POST', body: payload });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const json = await res.json();
        const data = json.data || {};
        busyTeacherIds = data.busy_teacher_ids || [];
        renderKonflikResults(data, jenis, ruangan);
        if (currentCourseId) renderGuruList();
    } catch {
        showToast('Gagal menghubungi server. Coba lagi.', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-shield-check me-2"></i>Cek Konflik Sekarang';
    }
}

function renderKonflikResults(data, jenis, ruangan) {
    let html = '<div class="row g-3">';

    if (jenis === 'offline' && ruangan && data.room) {
        const r  = data.room;
        const ok = !r.conflict;
        html += `<div class="col-md-6">
            <div class="p-3 rounded-3 d-flex align-items-start gap-3" style="background:${ok ? 'var(--soft-success-bg,#d1fae5)' : '#fef2f2'};border:1.5px solid ${ok ? '#10b981' : '#ef4444'}">
                <div style="font-size:22px;line-height:1">${ok ? '✅' : '❌'}</div>
                <div>
                    <div class="fw-semibold" style="font-size:13px;color:${ok ? '#047857' : '#b91c1c'}">${ok ? 'Ruangan Tersedia' : 'Ruangan Bentrok!'}</div>
                    <div style="font-size:12px;color:var(--text-muted)">${r.detail}</div>
                </div>
            </div>
        </div>`;
    } else if (jenis === 'online') {
        html += `<div class="col-md-6">
            <div class="p-3 rounded-3 d-flex align-items-start gap-3" style="background:rgba(59,130,246,.08);border:1.5px solid #3b82f6">
                <div style="font-size:22px;line-height:1">💻</div>
                <div><div class="fw-semibold" style="font-size:13px;color:#1d4ed8">Kelas Online</div>
                <div style="font-size:12px;color:var(--text-muted)">Tidak ada pengecekan ruang fisik untuk kelas online.</div></div>
            </div>
        </div>`;
    }

    if (data.teacher) {
        const t  = data.teacher;
        const ok = !t.conflict;
        html += `<div class="col-md-6">
            <div class="p-3 rounded-3 d-flex align-items-start gap-3" style="background:${ok ? 'var(--soft-success-bg,#d1fae5)' : '#fef2f2'};border:1.5px solid ${ok ? '#10b981' : '#ef4444'}">
                <div style="font-size:22px;line-height:1">${ok ? '👨‍🏫' : '⚠️'}</div>
                <div>
                    <div class="fw-semibold" style="font-size:13px;color:${ok ? '#047857' : '#b91c1c'}">${ok ? 'Guru Tersedia' : 'Guru Sedang Mengajar!'}</div>
                    <div style="font-size:12px;color:var(--text-muted)">${t.detail}</div>
                </div>
            </div>
        </div>`;
    } else {
        const busyCount = busyTeacherIds.length;
        const freeCount = teachers.length - busyCount;
        html += `<div class="col-md-6">
            <div class="p-3 rounded-3 d-flex align-items-start gap-3" style="background:${freeCount > 0 ? 'var(--soft-success-bg,#d1fae5)' : '#fef2f2'};border:1.5px solid ${freeCount > 0 ? '#10b981' : '#ef4444'}">
                <div style="font-size:22px;line-height:1">${freeCount > 0 ? '👨‍🏫' : '⚠️'}</div>
                <div>
                    <div class="fw-semibold" style="font-size:13px;color:${freeCount > 0 ? '#047857' : '#b91c1c'}">${freeCount} Guru Tersedia di Waktu Ini</div>
                    <div style="font-size:12px;color:var(--text-muted)">${busyCount} guru sedang mengajar di jam ini.</div>
                </div>
            </div>
        </div>`;
    }

    html += '</div>';
    document.getElementById('konflikResults').innerHTML = html;
}

// ─── Step 4: Guru list & stats ─────────────────────────────────────────────

function renderGuruList() {
    const mapelId = currentCourseId;

    let preferred = [], others = [];
    teachers.forEach(t => {
        const isPref = mapelId ? t.course_ids.includes(mapelId) : false;
        if (isPref) preferred.push(t); else others.push(t);
    });

    let html = '';
    if (preferred.length > 0) {
        html += `<div class="col-12 mb-1"><div class="text-muted fw-semibold" style="font-size:11px;text-transform:uppercase;letter-spacing:.5px">✅ Guru sesuai mata pelajaran (${preferred.length})</div></div>`;
        preferred.forEach(t => { html += renderGuruCard(t, true); });
    }
    if (others.length > 0) {
        if (preferred.length > 0) {
            html += `<div class="col-12 mt-2 mb-1"><div class="text-muted fw-semibold" style="font-size:11px;text-transform:uppercase;letter-spacing:.5px">— Guru lainnya (${others.length})</div></div>`;
        }
        others.forEach(t => { html += renderGuruCard(t, false); });
    }
    if (!html) html = '<div class="col-12 text-muted" style="font-size:13px">Belum ada guru aktif.</div>';

    document.getElementById('guruList').innerHTML = html;
    if (selectedGuruId) {
        const card = document.querySelector(`.guru-card[data-id="${selectedGuruId}"]`);
        if (card) highlightGuruCard(selectedGuruId);
    }
}

function renderGuruCard(t, isPreferred) {
    const isBusy      = busyTeacherIds.includes(t.id);
    const isSelected  = t.id === selectedGuruId;
    const badgeBg     = isBusy ? '#fef2f2' : (isSelected ? 'var(--soft-primary-bg,rgba(200,77,223,.08))' : (isPreferred ? 'var(--soft-success-bg,#d1fae5)' : 'var(--input-bg)'));
    const badgeBorder = isBusy ? '#ef4444' : (isSelected ? '#c84ddf' : (isPreferred ? '#10b981' : 'var(--card-border)'));

    const statusBadge = isBusy
        ? `<span style="background:#fee2e2;color:#b91c1c;padding:2px 8px;border-radius:10px;font-size:10px;font-weight:600">⚠️ Bentrok</span>`
        : `<span style="background:#d1fae5;color:#047857;padding:2px 8px;border-radius:10px;font-size:10px;font-weight:600">✅ Tersedia</span>`;

    const jenisBadge = t.jenis_guru === 'freelance'
        ? `<span style="background:#fef3c7;color:#92400e;padding:2px 6px;border-radius:8px;font-size:10px">Freelance</span>`
        : `<span style="background:#e0f2fe;color:#0369a1;padding:2px 6px;border-radius:8px;font-size:10px">Tetap</span>`;

    return `<div class="col-md-4 col-sm-6">
        <div class="guru-card p-3 rounded-3" data-id="${t.id}" data-name="${t.name}"
             style="border:2px solid ${badgeBorder};background:${badgeBg};cursor:${isBusy ? 'not-allowed' : 'pointer'};transition:.2s;opacity:${isBusy ? '.6' : '1'}"
             ${isBusy ? '' : `onclick="selectGuru(${t.id})"`}>
            <div class="d-flex align-items-center gap-2 mb-2">
                <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;font-weight:700;flex-shrink:0">
                    ${t.name.charAt(0).toUpperCase()}
                </div>
                <div style="min-width:0">
                    <div class="fw-semibold text-truncate" style="font-size:13px;line-height:1.3">${t.name}</div>
                    <div class="text-muted" style="font-size:10px">${t.branch || ''}</div>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-1">
                ${statusBadge}
                ${jenisBadge}
            </div>
        </div>
    </div>`;
}

function highlightGuruCard(guruId) {
    document.querySelectorAll('.guru-card').forEach(card => {
        const isSelected = parseInt(card.dataset.id) === guruId;
        const isBusy     = busyTeacherIds.includes(parseInt(card.dataset.id));
        if (!isBusy) {
            card.style.border    = isSelected ? '2px solid #c84ddf' : '2px solid var(--card-border)';
            card.style.boxShadow = isSelected ? '0 0 0 3px rgba(200,77,223,.15)' : '';
        }
    });
}

async function selectGuru(guruId) {
    selectedGuruId = guruId;
    document.getElementById('guru_id').value = guruId;
    highlightGuruCard(guruId);

    const t = teachers.find(x => x.id == guruId);
    if (t) {
        document.getElementById('honorDisplay').innerHTML =
            `<i class="bi bi-person-check me-1 text-success"></i><strong>${t.name}</strong> dipilih. Masukkan honor per sesi jika ada kesepakatan khusus.`;
    }

    document.getElementById('submitBtn').disabled = false;

    // Load teacher stats
    await loadTeacherStats(guruId);
}

async function loadTeacherStats(guruId) {
    const box = document.getElementById('guruStatsBox');
    const content = document.getElementById('guruStatsContent');
    box.style.display = '';
    content.innerHTML = '<div class="col-12 text-muted" style="font-size:13px"><span class="spinner-border spinner-border-sm me-2"></span>Memuat statistik...</div>';

    try {
        const res  = await fetch(`${teacherStatsBase}/${guruId}/stats`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf }
        });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const d = await res.json();

        let html = '';

        // Subjects stat
        const subjectList = d.subjects && d.subjects.length > 0
            ? d.subjects.map(s => `<span style="background:var(--soft-primary,rgba(200,77,223,.15));color:#461256;padding:2px 8px;border-radius:10px;font-size:11px">${s}</span>`).join(' ')
            : '<span class="text-muted" style="font-size:12px">Belum ada</span>';

        html += `<div class="col-md-4">
            <div class="p-3 rounded-3 text-center" style="background:var(--soft-primary-bg,rgba(200,77,223,.08));border:1px solid var(--soft-primary-border,rgba(200,77,223,.2))">
                <div style="font-size:24px;font-weight:700;color:#461256">${d.subjects_count}</div>
                <div class="text-muted" style="font-size:11px;margin-bottom:6px">Mata Pelajaran Diajarkan</div>
                <div class="d-flex flex-wrap gap-1 justify-content-center">${subjectList}</div>
            </div>
        </div>`;

        html += `<div class="col-md-4">
            <div class="p-3 rounded-3 text-center" style="background:var(--soft-success-bg,#d1fae5);border:1px solid rgba(16,185,129,.2)">
                <div style="font-size:24px;font-weight:700;color:#047857">${d.sesi_selesai}</div>
                <div class="text-muted" style="font-size:11px">Sesi Selesai Diajar</div>
                <div class="text-muted" style="font-size:10px;margin-top:4px">(${d.sesi_total} total dijadwalkan)</div>
            </div>
        </div>`;

        if (d.jenis_guru === 'freelance' && d.earnings !== null) {
            html += `<div class="col-md-4">
                <div class="p-3 rounded-3 text-center" style="background:#fffbeb;border:1px solid rgba(245,158,11,.3)">
                    <div style="font-size:18px;font-weight:700;color:#92400e">Rp ${fmt(d.earnings)}</div>
                    <div class="text-muted" style="font-size:11px">Total Honor Diterima</div>
                    <div style="background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:10px;font-size:10px;margin-top:6px;display:inline-block">Guru Freelance</div>
                </div>
            </div>`;
        } else {
            html += `<div class="col-md-4">
                <div class="p-3 rounded-3 text-center" style="background:#e0f2fe;border:1px solid rgba(3,105,161,.2)">
                    <div class="fw-semibold" style="color:#0369a1;font-size:13px">Guru Tetap</div>
                    <div class="text-muted" style="font-size:11px;margin-top:4px">Honor mengikuti gaji pokok</div>
                    ${d.salary_base ? `<div style="font-size:12px;font-weight:600;color:#0369a1;margin-top:4px">Rp ${fmt(d.salary_base)} /bulan</div>` : ''}
                </div>
            </div>`;
        }

        content.innerHTML = html;
    } catch {
        content.innerHTML = '<div class="col-12 text-muted" style="font-size:13px"><i class="bi bi-exclamation-circle me-1"></i>Gagal memuat statistik guru.</div>';
    }
}

// ─── Init ──────────────────────────────────────────────────────────────────

selectProgram(document.getElementById('program_belajar').value || 'kelas');
selectSistem(document.getElementById('jenis').value || 'offline');

const initCourse = document.getElementById('mata_pelajaran_id').value;
if (initCourse) onCourseChange(initCourse);
</script>
@endpush
