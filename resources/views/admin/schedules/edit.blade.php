@extends('layouts.app')
@section('title','Edit Jadwal Sesi')
@section('page-title','Edit Jadwal Sesi')

@php
$paketsJson = $pakets->map(fn($p) => [
    'id'                => $p->id,
    'nama'              => $p->nama,
    'jenis'             => $p->jenis,
    'tipe_kelas'        => $p->tipe_kelas,
    'jumlah_pertemuan'  => $p->jumlah_pertemuan,
    'harga'             => $p->harga,
    'cabang'            => $p->cabang?->name,
    'guru_id'           => $p->guru_id,
    'guru_name'         => $p->guru?->name,
    'mata_pelajaran'    => $p->mataPelajaran->map(fn($m) => ['id'=>$m->id,'nama'=>$m->nama])->values(),
]);

$teachersJson = $teachers->map(fn($t) => [
    'id'         => $t->id,
    'name'       => $t->name,
    'nig'        => $t->nig,
    'branch'     => $t->branch?->name,
    'course_ids' => $t->courses->pluck('id')->values(),
]);

$classesJson = $classes->map(fn($c) => [
    'id'         => $c->id,
    'nama_kelas' => $c->nama_kelas,
    'cabang'     => $c->cabang?->name,
    'guru'       => $c->guru?->name,
    'mapel_ids'  => $c->mataPelajaran?->pluck('id')->values() ?? [],
]);

$modulesJson = $modules->map(fn($m) => [
    'id'         => $m->id,
    'judul'      => $m->judul,
    'mapel_id'   => $m->mata_pelajaran_id,
    'mapel_name' => $m->mataPelajaran?->nama,
]);
@endphp

@section('content')
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.schedules.index') }}">Jadwal Sesi</a></li>
        <li class="breadcrumb-item active">Edit Jadwal #{{ $schedule->id }}</li>
    </ol>
</nav>

{{-- HEADER BANNER --}}
<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-20px;top:-20px;width:140px;height:140px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-3">
            <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                <i class="bi bi-pencil-square"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0" style="color:white">Edit Jadwal Sesi #{{ $schedule->id }}</h5>
                <div style="font-size:12px;opacity:.8">
                    {{ $schedule->paket?->nama ?? '—' }}
                </div>
            </div>
        </div>
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-sm fw-semibold"
           style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger mb-3">
    <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<form action="{{ route('admin.schedules.update', $schedule) }}" method="POST" id="editForm">
    @csrf
    @method('PUT')

<div class="row g-4">

    {{-- LEFT COLUMN --}}
    <div class="col-lg-8">

        {{-- SECTION 1: PAKET --}}
        <div class="dashboard-card mb-4">
            <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
                <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#c84ddf,#461256);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:14px">Paket Belajar</div>
                    <div class="text-muted" style="font-size:11px">Pilih paket yang ingin dijadwalkan</div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Paket Belajar <span class="text-danger">*</span></label>
                <select name="paket_id" id="paket_id" class="form-select" required onchange="onPaketChange(this.value)">
                    <option value="">— Pilih Paket —</option>
                    @foreach($pakets as $p)
                    <option value="{{ $p->id }}" {{ old('paket_id', $schedule->paket_id) == $p->id ? 'selected' : '' }}>
                        {{ $p->nama }}
                        @if($p->cabang) ({{ $p->cabang->name }})@endif
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Paket info box --}}
            <div id="paketInfoBox" style="{{ $schedule->paket ? '' : 'display:none' }}">
                <div class="p-3 rounded-3" style="background:var(--soft-primary-bg);border:1.5px solid var(--soft-primary-border)">
                    <div class="fw-semibold mb-2" style="color:var(--soft-primary-text);font-size:13px"><i class="bi bi-info-circle me-1"></i>Info Paket</div>
                    <div class="row g-2 small" id="paketInfoContent">
                        @if($schedule->paket)
                        <div class="col-6"><span class="text-muted">Jenis:</span> <strong>{{ ucfirst($schedule->paket->jenis ?? '—') }}</strong></div>
                        <div class="col-6"><span class="text-muted">Cabang:</span> <strong>{{ $schedule->paket->cabang?->name ?? 'Pusat' }}</strong></div>
                        <div class="col-6"><span class="text-muted">Harga:</span> <strong>Rp {{ number_format($schedule->paket->harga ?? 0,0,',','.') }}</strong></div>
                        <div class="col-12"><span class="text-muted">Mapel Paket:</span> <strong>{{ $schedule->paket->mataPelajaran->pluck('nama')->join(', ') ?: '—' }}</strong></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 2: MATA PELAJARAN --}}
        <div class="dashboard-card mb-4" id="mapelSection" style="{{ $schedule->paket ? '' : 'display:none' }}">
            <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
                <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#0ea5e9,#0369a1);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:14px">Mata Pelajaran Sesi</div>
                    <div class="text-muted" style="font-size:11px">Pilih satu mata pelajaran untuk sesi ini</div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Mata Pelajaran <span class="text-danger">*</span></label>
                <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-select" required onchange="onMapelChange(this.value)">
                    <option value="">— Pilih Mata Pelajaran —</option>
                    @if($schedule->paket)
                        @foreach($schedule->paket->mataPelajaran as $m)
                        <option value="{{ $m->id }}" {{ old('mata_pelajaran_id', $schedule->mata_pelajaran_id) == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>

        {{-- SECTION 3: GURU --}}
        <div class="dashboard-card mb-4" id="guruSection" style="{{ $schedule->mata_pelajaran_id ? '' : 'display:none' }}">
            <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
                <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#10b981,#047857);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:14px">Guru Pengajar</div>
                    <div class="text-muted" style="font-size:11px">Pilih guru yang mengajar sesi ini</div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Guru <span class="text-danger">*</span></label>
                <select name="guru_id" id="guru_id" class="form-select" required>
                    <option value="">— Pilih Guru —</option>
                    {{-- populated by JS, pre-seeded below --}}
                </select>
                <div class="form-text" id="guruHint"></div>
            </div>
        </div>

        {{-- SECTION 4: WAKTU & DETAIL --}}
        <div class="dashboard-card mb-4" id="detailSection" style="{{ $schedule->guru_id ? '' : 'display:none' }}">
            <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
                <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#f6af23,#e09000);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
                    <i class="bi bi-clock-fill"></i>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:14px">Waktu &amp; Detail Sesi</div>
                    <div class="text-muted" style="font-size:11px">Atur waktu, topik, dan informasi tambahan</div>
                </div>
            </div>

            <div class="row g-3">
                {{-- TANGGAL --}}
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control"
                           value="{{ old('tanggal', $schedule->tanggal?->format('Y-m-d')) }}" required>
                </div>

                {{-- STATUS --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        @foreach(['dijadwalkan'=>'Dijadwalkan','berlangsung'=>'Berlangsung','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan'] as $val=>$lbl)
                        <option value="{{ $val }}" {{ old('status',$schedule->status)==$val?'selected':'' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- JAM --}}
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
                    <input type="time" name="jam_mulai" class="form-control"
                           value="{{ old('jam_mulai', substr($schedule->jam_mulai ?? '', 0, 5)) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
                    <input type="time" name="jam_selesai" class="form-control"
                           value="{{ old('jam_selesai', substr($schedule->jam_selesai ?? '', 0, 5)) }}" required>
                </div>

                {{-- JENIS --}}
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                    <select name="jenis" class="form-select" required>
                        @foreach(['offline'=>'Offline','online'=>'Online','private'=>'Private'] as $val=>$lbl)
                        <option value="{{ $val }}" {{ old('jenis',$schedule->jenis??$schedule->paket?->jenis)==$val?'selected':'' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- KELAS --}}
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Kelas <span class="text-muted small">(opsional)</span></label>
                    <select name="kelas_id" id="kelas_id" class="form-select">
                        <option value="">— Tanpa Kelas —</option>
                        @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ old('kelas_id',$schedule->kelas_id)==$c->id?'selected':'' }}>{{ $c->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- MODUL --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Modul <span class="text-muted small">(opsional)</span></label>
                    <select name="module_id" id="module_id" class="form-select">
                        <option value="">— Tanpa Modul —</option>
                        @foreach($modules as $m)
                        <option value="{{ $m->id }}" {{ old('module_id',$schedule->module_id)==$m->id?'selected':'' }}>{{ $m->judul }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- TOPIK --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Topik / Materi</label>
                    <input type="text" name="topik" class="form-control"
                           value="{{ old('topik', $schedule->topik) }}" placeholder="Contoh: Persamaan Kuadrat, Past Tense...">
                </div>

                {{-- RUANGAN --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Ruangan</label>
                    <input type="text" name="ruangan" class="form-control"
                           value="{{ old('ruangan', $schedule->ruangan) }}" placeholder="Opsional">
                </div>

                {{-- LINK MEETING --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Link Meeting</label>
                    <input type="url" name="link_meeting" class="form-control"
                           value="{{ old('link_meeting', $schedule->link_meeting) }}" placeholder="https://zoom.us/...">
                </div>

                {{-- CATATAN --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan tambahan untuk sesi ini...">{{ old('catatan', $schedule->catatan) }}</textarea>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 pt-2" id="submitBar" style="{{ $schedule->guru_id ? '' : 'display:none' }}">
            <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
            <button type="submit" class="btn btn-primary fw-semibold px-5">
                <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
            </button>
        </div>

    </div>

    {{-- RIGHT SIDEBAR --}}
    <div class="col-lg-4">
        <div class="dashboard-card sticky-top" style="top:80px">
            <div class="d-flex align-items-center gap-2 mb-3 pb-3 border-bottom">
                <div style="width:28px;height:28px;border-radius:7px;background:linear-gradient(135deg,#c84ddf,#461256);display:flex;align-items:center;justify-content:center;color:white;font-size:13px">
                    <i class="bi bi-calendar3"></i>
                </div>
                <div class="fw-bold" style="font-size:13px">Ringkasan Jadwal</div>
            </div>

            <div id="sidebarSummary">
                <div id="sb-paket" class="mb-2 p-2 rounded" style="background:var(--input-bg)">
                    <div class="text-muted" style="font-size:11px">Paket</div>
                    <div class="fw-semibold" style="font-size:13px" id="sb-paket-val">{{ $schedule->paket?->nama ?? '—' }}</div>
                </div>
                <div id="sb-mapel" class="mb-2 p-2 rounded" style="background:var(--input-bg)">
                    <div class="text-muted" style="font-size:11px">Mata Pelajaran Sesi</div>
                    <div class="fw-semibold" style="font-size:13px" id="sb-mapel-val">{{ $schedule->mataPelajaran?->nama ?? '—' }}</div>
                </div>
                <div id="sb-guru" class="mb-2 p-2 rounded" style="background:var(--input-bg)">
                    <div class="text-muted" style="font-size:11px">Guru</div>
                    <div class="fw-semibold" style="font-size:13px" id="sb-guru-val">{{ $schedule->guru?->name ?? '—' }}</div>
                </div>
                <div class="mb-2 p-2 rounded" style="background:var(--input-bg)">
                    <div class="text-muted" style="font-size:11px">Cabang</div>
                    <div class="fw-semibold" style="font-size:13px">{{ $schedule->paket?->cabang?->name ?? $schedule->cabang?->name ?? '—' }}</div>
                </div>
                <div class="mb-2 p-2 rounded" style="background:var(--input-bg)">
                    <div class="text-muted" style="font-size:11px">ID Jadwal</div>
                    <div class="fw-bold" style="font-size:15px;color:var(--soft-primary-text)">#{{ $schedule->id }}</div>
                </div>
                <div class="mb-2 p-2 rounded" style="background:var(--input-bg)">
                    <div class="text-muted" style="font-size:11px">Dibuat</div>
                    <div class="fw-semibold" style="font-size:12px">{{ $schedule->created_at->format('d M Y H:i') }}</div>
                </div>
            </div>

            <div class="mt-3 pt-3 border-top">
                <a href="{{ route('admin.schedules.show', $schedule) }}" class="btn btn-outline-primary w-100 mb-2" style="border-radius:10px;font-size:13px">
                    <i class="bi bi-eye me-1"></i>Lihat Detail Lengkap
                </a>
                <a href="{{ route('admin.attendance.index', ['jadwal_id' => $schedule->id]) }}" class="btn btn-outline-secondary w-100" style="border-radius:10px;font-size:13px">
                    <i class="bi bi-clipboard-check me-1"></i>Lihat Absensi Sesi Ini
                </a>
            </div>
        </div>
    </div>

</div>
</form>

</div>
@endsection

@push('scripts')
<script>
const pakets   = @json($paketsJson);
const teachers = @json($teachersJson);
const modules  = @json($modulesJson);

const CURRENT_PAKET_ID   = {{ $schedule->paket_id ?? 'null' }};
const CURRENT_MAPEL_ID   = {{ $schedule->mata_pelajaran_id ?? 'null' }};
const CURRENT_GURU_ID    = {{ $schedule->guru_id ?? 'null' }};

/* ── Paket changed ─────────────────────────────────────── */
function onPaketChange(paketId, keepMapelId, keepGuruId) {
    const pkg = pakets.find(p => p.id == paketId);
    const infoBox  = document.getElementById('paketInfoBox');
    const infoContent = document.getElementById('paketInfoContent');
    const mapelSec = document.getElementById('mapelSection');
    const guruSec  = document.getElementById('guruSection');
    const detSec   = document.getElementById('detailSection');
    const submitBar = document.getElementById('submitBar');
    const mapelSel = document.getElementById('mata_pelajaran_id');
    const guruSel  = document.getElementById('guru_id');

    if (!pkg) {
        infoBox.style.display  = 'none';
        mapelSec.style.display = 'none';
        guruSec.style.display  = 'none';
        detSec.style.display   = 'none';
        submitBar.style.display= 'none';
        return;
    }

    // update sidebar
    document.getElementById('sb-paket-val').textContent = pkg.nama;

    // info box
    infoBox.style.display = '';
    infoContent.innerHTML = `
        <div class="col-6"><span class="text-muted">Jenis:</span> <strong>${pkg.jenis||'—'}</strong></div>
        <div class="col-6"><span class="text-muted">Cabang:</span> <strong>${pkg.cabang||'Pusat'}</strong></div>
        <div class="col-6"><span class="text-muted">Harga:</span> <strong>Rp ${pkg.harga ? parseInt(pkg.harga).toLocaleString('id-ID') : '—'}</strong></div>
        <div class="col-12"><span class="text-muted">Mapel Paket:</span> <strong>${pkg.mata_pelajaran.map(m=>m.nama).join(', ')||'—'}</strong></div>
    `;

    // mata pelajaran dropdown
    mapelSel.innerHTML = '<option value="">— Pilih Mata Pelajaran —</option>';
    pkg.mata_pelajaran.forEach(m => {
        const sel = (keepMapelId ?? CURRENT_MAPEL_ID) == m.id ? 'selected' : '';
        mapelSel.innerHTML += `<option value="${m.id}" ${sel}>${m.nama}</option>`;
    });
    mapelSec.style.display = '';

    // if only 1 mapel, auto-select
    if (pkg.mata_pelajaran.length === 1) {
        mapelSel.value = pkg.mata_pelajaran[0].id;
    }

    // trigger guru filter if mapel already selected
    if (mapelSel.value) {
        onMapelChange(mapelSel.value, keepGuruId);
    } else {
        guruSec.style.display  = 'none';
        detSec.style.display   = 'none';
        submitBar.style.display= 'none';
    }
}

/* ── Mata Pelajaran changed ────────────────────────────── */
function onMapelChange(mapelId, keepGuruId) {
    const guruSec   = document.getElementById('guruSection');
    const detSec    = document.getElementById('detailSection');
    const submitBar = document.getElementById('submitBar');
    const guruSel   = document.getElementById('guru_id');
    const guruHint  = document.getElementById('guruHint');

    document.getElementById('sb-mapel-val').textContent =
        document.getElementById('mata_pelajaran_id').selectedOptions[0]?.text || '—';

    if (!mapelId) {
        guruSec.style.display  = 'none';
        detSec.style.display   = 'none';
        submitBar.style.display= 'none';
        return;
    }

    const mapelInt = parseInt(mapelId);
    const matching = teachers.filter(t => t.course_ids.includes(mapelInt));
    const others   = teachers.filter(t => !t.course_ids.includes(mapelInt));
    const want     = keepGuruId ?? CURRENT_GURU_ID;

    let html = '<option value="">— Pilih Guru —</option>';
    if (matching.length) {
        html += `<optgroup label="✅ Mengajar mata pelajaran ini">`;
        matching.forEach(t => {
            html += `<option value="${t.id}" ${t.id==want?'selected':''}>${t.name}${t.branch?' ('+t.branch+')':''}</option>`;
        });
        html += '</optgroup>';
    }
    if (others.length) {
        html += `<optgroup label="— Guru lainnya">`;
        others.forEach(t => {
            html += `<option value="${t.id}" ${t.id==want?'selected':''}>${t.name}${t.branch?' ('+t.branch+')':''}</option>`;
        });
        html += '</optgroup>';
    }
    guruSel.innerHTML = html;
    guruHint.textContent = matching.length
        ? `${matching.length} guru tersedia untuk mata pelajaran ini`
        : 'Belum ada guru yang terdaftar untuk mata pelajaran ini';

    guruSec.style.display = '';

    if (guruSel.value) {
        onGuruChange(guruSel.value);
    } else if (matching.length === 1) {
        guruSel.value = matching[0].id;
        onGuruChange(matching[0].id);
    } else {
        detSec.style.display   = 'none';
        submitBar.style.display= 'none';
    }

    // filter modules by mapel
    filterModules(mapelInt);
}

/* ── Guru changed ──────────────────────────────────────── */
function onGuruChange(guruId) {
    const detSec    = document.getElementById('detailSection');
    const submitBar = document.getElementById('submitBar');
    const guruSel   = document.getElementById('guru_id');
    document.getElementById('sb-guru-val').textContent =
        guruSel.selectedOptions[0]?.text.replace(/\s*\(.*\)/, '') || '—';

    if (guruId) {
        detSec.style.display    = '';
        submitBar.style.display = '';
    } else {
        detSec.style.display    = 'none';
        submitBar.style.display = 'none';
    }
}

/* ── Filter modules by mapel ───────────────────────────── */
function filterModules(mapelId) {
    const sel = document.getElementById('module_id');
    const currentModuleId = {{ $schedule->module_id ?? 'null' }};
    let html = '<option value="">— Tanpa Modul —</option>';
    modules.forEach(m => {
        if (!m.mapel_id || m.mapel_id == mapelId) {
            html += `<option value="${m.id}" ${m.id==currentModuleId?'selected':''}>${m.judul}${m.mapel_name?' ('+m.mapel_name+')':''}</option>`;
        }
    });
    sel.innerHTML = html;
}

/* ── Guru select onchange listener ────────────────────── */
document.getElementById('guru_id').addEventListener('change', function() {
    onGuruChange(this.value);
});

/* ── Init on page load ─────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function() {
    const paketId = document.getElementById('paket_id').value;
    if (paketId) {
        onPaketChange(paketId, CURRENT_MAPEL_ID, CURRENT_GURU_ID);
    }
});
</script>
@endpush
