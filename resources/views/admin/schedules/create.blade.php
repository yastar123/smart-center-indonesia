@extends('layouts.app')
@section('title','Tambah Jadwal Sesi')
@section('page-title','Tambah Jadwal Sesi')

@section('content')
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.schedules.index') }}">Jadwal Sesi</a></li>
        <li class="breadcrumb-item active">Tambah Jadwal</li>
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
                <h5 class="fw-bold mb-0" style="color:white">Tambah Jadwal Sesi</h5>
                <div style="font-size:12px;opacity:.8">Buat jadwal sesi baru untuk paket belajar</div>
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

<form action="{{ route('admin.schedules.store') }}" method="POST" id="scheduleForm">
@csrf
@if($errors->any())
<div class="alert alert-danger mb-4">
    <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

{{-- SECTION 1: PAKET --}}
<div class="dashboard-card mb-4">
    <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#c84ddf,#461256);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
            <i class="bi bi-box-seam"></i>
        </div>
        <div>
            <div class="fw-bold" style="font-size:15px">Paket Belajar</div>
            <div class="text-muted" style="font-size:12px">Pilih paket untuk sesi ini</div>
        </div>
    </div>

    <div class="row g-3">
        {{-- PAKET BELAJAR --}}
        <div class="col-lg-8">
            <label class="form-label fw-semibold">Paket Belajar <span class="text-danger">*</span></label>
            <select name="paket_id" id="paket_id" class="form-select" required onchange="onPaketChange(this.value)">
                <option value="">— Pilih Paket —</option>
                @foreach($pakets as $p)
                <option value="{{ $p->id }}" {{ old('paket_id') == $p->id ? 'selected' : '' }}>
                    {{ $p->nama . ($p->guru ? ' – '.$p->guru->name : '') . ($p->cabang ? ' ('.$p->cabang->name.')' : '') }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- SESI KE --}}
        <div class="col-lg-4">
            <label class="form-label fw-semibold">Sesi Ke- <span class="text-danger">*</span></label>
            <select name="pertemuan_ke" id="pertemuan_ke" class="form-select" required>
                <option value="">— Pilih paket dulu —</option>
            </select>
        </div>

        {{-- DETAIL PAKET BOX --}}
        <div class="col-12" id="paketDetailBox" style="display:none">
            <div class="p-3 rounded-3" style="background:var(--soft-primary-bg);border:1.5px solid var(--soft-primary-border)">
                <div class="fw-semibold mb-2" style="color:var(--soft-primary-text);font-size:13px"><i class="bi bi-info-circle me-2"></i>Detail Paket Terpilih</div>
                <div class="row g-2" id="paketDetailContent" style="font-size:12px"></div>
            </div>
        </div>

        {{-- SISWA PAKET BOX --}}
        <div class="col-12" id="daftarSiswaPaketBox" style="display:none">
            <div class="p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="fw-semibold" style="font-size:13px">
                        <i class="bi bi-people-fill text-primary me-2"></i>
                        Siswa Terdaftar di Paket Ini
                        <span id="siswaPaketCount" class="badge ms-1" style="background:var(--soft-primary);color:#461256;font-size:11px"></span>
                    </div>
                </div>
                <div id="daftarSiswaPaketContent" class="d-flex flex-wrap gap-2">
                    <span class="text-muted" style="font-size:12px">Memuat siswa...</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SECTION 2: GURU PENGAJAR --}}
<div class="dashboard-card mb-4">
    <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#10b981,#047857);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
            <i class="bi bi-person-badge"></i>
        </div>
        <div>
            <div class="fw-bold" style="font-size:15px">Guru Pengajar</div>
            <div class="text-muted" style="font-size:12px">Guru yang mengajar sesi ini (otomatis dari paket)</div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-lg-6">
            <label class="form-label fw-semibold">Guru Pengajar <span class="text-danger">*</span></label>
            <select name="guru_id" id="guru_id" class="form-select" required onchange="onGuruChange(this.value)">
                <option value="">— Pilih Guru —</option>
                @foreach($teachers as $t)
                <option value="{{ $t->id }}" {{ old('guru_id') == $t->id ? 'selected' : '' }}>
                    {{ $t->name }}{{ $t->branch ? ' ('.$t->branch->name.')' : '' }}
                </option>
                @endforeach
            </select>
            <div class="form-text">Pilih guru secara manual, atau akan terisi otomatis dari paket.</div>
        </div>
        <div class="col-lg-6">
            <div class="p-3 rounded-3 h-100" style="background:var(--input-bg);border:1px solid var(--card-border)" id="guruInfoBox">
                <div class="text-muted" style="font-size:12px">Pilih guru untuk melihat informasinya</div>
            </div>
        </div>
    </div>
</div>

{{-- SECTION 3: WAKTU & JADWAL --}}
<div class="dashboard-card mb-4">
    <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#0ea5e9,#0369a1);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
            <i class="bi bi-clock"></i>
        </div>
        <div>
            <div class="fw-bold" style="font-size:15px">Waktu & Jadwal</div>
            <div class="text-muted" style="font-size:12px">Tanggal dan jam pelaksanaan sesi</div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label fw-semibold">Metode Kelas <span class="text-danger">*</span></label>
            <select name="jenis" id="jenis" class="form-select" required>
                <option value="offline" {{ old('jenis','offline') == 'offline' ? 'selected' : '' }}>🏫 Offline (Tatap Muka)</option>
                <option value="online"  {{ old('jenis') == 'online'  ? 'selected' : '' }}>💻 Online</option>
                <option value="private" {{ old('jenis') == 'private' ? 'selected' : '' }}>👤 Private</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal') }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
            <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" value="{{ old('jam_mulai') }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
            <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" value="{{ old('jam_selesai') }}" required>
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold">Topik / Materi <span class="text-muted fw-normal">(opsional)</span></label>
            <input type="text" name="topik" class="form-control" value="{{ old('topik') }}" placeholder="Contoh: Persamaan Kuadrat, Past Tense, dll">
        </div>
    </div>
</div>

{{-- SECTION 4: MODUL --}}
<div class="dashboard-card mb-4">
    <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#f6af23,#d97706);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
            <i class="bi bi-journals"></i>
        </div>
        <div>
            <div class="fw-bold" style="font-size:15px">Modul Belajar <span class="badge text-muted fw-normal ms-1" style="font-size:11px;background:var(--input-bg)">Opsional</span></div>
            <div class="text-muted" style="font-size:12px">Pilih modul materi untuk sesi ini</div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-lg-8">
            <label class="form-label fw-semibold">Modul <span class="text-muted fw-normal">(opsional)</span></label>
            <select name="module_id" id="module_id" class="form-select" onchange="onModuleChange(this.value)">
                <option value="">— Tidak ada modul (opsional) —</option>
                @foreach($modules as $m)
                <option value="{{ $m->id }}" {{ old('module_id') == $m->id ? 'selected' : '' }}>
                    {{ $m->judul }}@if($m->mataPelajaran) – {{ $m->mataPelajaran->nama }}@endif
                    @if($m->kode_modul) [{{ $m->kode_modul }}]@endif
                </option>
                @endforeach
            </select>
            <div class="form-text">Modul dari halaman <a href="{{ route('admin.module.index') }}" target="_blank">Admin &gt; Modul</a></div>
        </div>
        <div class="col-lg-4">
            <div class="p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border);min-height:60px" id="moduleInfoBox">
                <div class="text-muted" style="font-size:12px">Tidak ada modul dipilih</div>
            </div>
        </div>
    </div>
</div>

{{-- SECTION 5: LOKASI & LINK --}}
<div class="dashboard-card mb-4">
    <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#8b5cf6,#6d28d9);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
            <i class="bi bi-geo-alt"></i>
        </div>
        <div>
            <div class="fw-bold" style="font-size:15px">Lokasi & Link Meeting <span class="badge text-muted fw-normal ms-1" style="font-size:11px;background:var(--input-bg)">Opsional</span></div>
            <div class="text-muted" style="font-size:12px">Lokasi fisik atau tautan kelas online</div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Ruangan <span class="text-muted fw-normal">(opsional)</span></label>
            <div class="input-group">
                <span class="input-group-text" style="background:var(--input-bg);border-color:var(--card-border)"><i class="bi bi-door-open text-muted"></i></span>
                <input type="text" name="ruangan" class="form-control" value="{{ old('ruangan') }}" placeholder="misal: Ruang A1, Lab Komputer">
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Link Meeting <span class="text-muted fw-normal">(opsional)</span></label>
            <div class="input-group">
                <span class="input-group-text" style="background:var(--input-bg);border-color:var(--card-border)"><i class="bi bi-camera-video text-muted"></i></span>
                <input type="url" name="link_meeting" class="form-control" value="{{ old('link_meeting') }}" placeholder="https://zoom.us/...">
            </div>
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold">Catatan <span class="text-muted fw-normal">(opsional)</span></label>
            <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan tambahan untuk sesi ini">{{ old('catatan') }}</textarea>
        </div>
    </div>
</div>

{{-- SUBMIT --}}
<div class="dashboard-card">
    <div class="d-flex justify-content-between align-items-center">
        <div class="text-muted" style="font-size:13px"><i class="bi bi-info-circle me-1"></i>Field bertanda <span class="text-danger">*</span> wajib diisi</div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
            <button type="submit" class="btn btn-primary px-5 fw-semibold">
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
        'metode_absensi'   => $p->metode_absensi,
        'tipe_kelas'       => $p->tipe_kelas,
        'harga'            => $p->harga,
        'deskripsi'        => $p->deskripsi,
        'status'           => $p->status,
        'cabang'           => $p->cabang?->name,
        'cabang_id'        => $p->cabang_id,
        'guru_id'          => $p->guru_id,
        'guru_name'        => $p->guru?->name,
        'guru_email'       => $p->guru?->email,
        'guru_nig'         => $p->guru?->nig,
        'tipe_kelas'       => $p->tipe_kelas,
        'mata_pelajaran'   => $p->mataPelajaran->pluck('nama'),
    ];
});
$modulesJson = $modules->map(function ($m) {
    return [
        'id'           => $m->id,
        'judul'        => $m->judul,
        'jenis'        => $m->jenis,
        'kode_modul'   => $m->kode_modul,
        'deskripsi'    => $m->deskripsi,
        'mata_pelajaran' => $m->mataPelajaran?->nama,
    ];
});
@endphp

@push('scripts')
<script>
const pakets  = @json($paketsJson);
const modules = @json($modulesJson);
const packageStudentsBaseUrl = '{{ url("/admin/schedules/package") }}';

function onPaketChange(paketId) {
    const detailBox     = document.getElementById('paketDetailBox');
    const detailContent = document.getElementById('paketDetailContent');
    const sesiSelect    = document.getElementById('pertemuan_ke');
    const guruSelect    = document.getElementById('guru_id');
    const guruInfoBox   = document.getElementById('guruInfoBox');
    const siswaBox      = document.getElementById('daftarSiswaPaketBox');

    if (!paketId) {
        detailBox.style.display = 'none';
        siswaBox.style.display  = 'none';
        sesiSelect.innerHTML = '<option value="">— Pilih paket dulu —</option>';
        guruSelect.value = '';
        guruInfoBox.innerHTML = '<div class="text-muted" style="font-size:12px">Pilih paket untuk melihat info guru pengajar</div>';
        return;
    }

    const pkg = pakets.find(p => p.id == paketId);
    if (!pkg) return;

    // Package detail box
    detailBox.style.display = 'block';
    detailContent.innerHTML = `
        <div class="col-md-3 col-6"><strong>Jenis:</strong> ${pkg.jenis || '—'}</div>
        <div class="col-md-3 col-6"><strong>Total Sesi:</strong> ${pkg.jumlah_pertemuan || '—'}</div>
        <div class="col-md-3 col-6"><strong>Tipe Kelas:</strong> ${pkg.tipe_kelas || '—'}</div>
        <div class="col-md-3 col-6"><strong>Harga:</strong> Rp ${pkg.harga ? parseInt(pkg.harga).toLocaleString('id-ID') : '—'}</div>
        <div class="col-md-6 col-12"><strong>Mata Pelajaran:</strong> ${pkg.mata_pelajaran?.join(', ') || '—'}</div>
        <div class="col-md-6 col-12"><strong>Cabang:</strong> ${pkg.cabang || 'Pusat'}</div>
        ${pkg.deskripsi ? `<div class="col-12"><strong>Deskripsi:</strong> ${pkg.deskripsi}</div>` : ''}
    `;

    // Session options
    let sesiOptions = '<option value="">— Pilih Sesi —</option>';
    const totalSesi = Number(pkg.jumlah_pertemuan || 0);
    for (let i = 1; i <= totalSesi; i++) {
        sesiOptions += `<option value="${i}">Sesi ke-${i}</option>`;
    }
    sesiSelect.innerHTML = sesiOptions;

    // Auto-set jenis (delivery method) from package tipe_kelas
    const jenisSelect = document.getElementById('jenis');
    if (jenisSelect && !jenisSelect.dataset.manuallyChanged) {
        const validJenis = ['online', 'offline', 'private'];
        const autoJenis  = validJenis.includes(pkg.tipe_kelas) ? pkg.tipe_kelas : 'offline';
        jenisSelect.value = autoJenis;
    }

    // Guru info from package
    if (pkg.guru_id) {
        guruSelect.value = pkg.guru_id;
        guruInfoBox.innerHTML = `
            <div class="text-muted mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:.5px">Guru dari Paket</div>
            <div class="fw-semibold">${pkg.guru_name || '—'}</div>
            ${pkg.guru_nig ? `<div class="text-muted" style="font-size:12px">NIG: ${pkg.guru_nig}</div>` : ''}
            ${pkg.guru_email ? `<div class="text-muted" style="font-size:12px">${pkg.guru_email}</div>` : ''}
        `;
    } else {
        guruSelect.value = '';
        guruInfoBox.innerHTML = '<div class="text-muted" style="font-size:12px">Paket ini belum memiliki guru pengajar</div>';
    }

    // Load students enrolled in this package
    loadStudentsByPackage(paketId);
}

function loadStudentsByPackage(paketId) {
    if (!paketId) return;
    const box     = document.getElementById('daftarSiswaPaketBox');
    const content = document.getElementById('daftarSiswaPaketContent');
    const counter = document.getElementById('siswaPaketCount');

    box.style.display = 'block';
    content.innerHTML = '<span class="text-muted" style="font-size:12px"><i class="bi bi-hourglass-split me-1"></i>Memuat daftar siswa...</span>';
    counter.textContent = '';

    fetch(`${packageStudentsBaseUrl}/${paketId}/students`, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
    .then(data => {
        const students = data.students || [];
        const count    = data.count || 0;

        counter.textContent = count + ' siswa';

        if (!students.length) {
            content.innerHTML = '<span class="text-muted" style="font-size:12px"><i class="bi bi-info-circle me-1"></i>Belum ada siswa yang terdaftar di paket ini.</span>';
            return;
        }

        content.innerHTML = students.map(s =>
            `<span style="background:var(--soft-primary);color:#461256;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:500;display:inline-flex;align-items:center;gap:4px">
                <i class="bi bi-person-fill"></i>${s.name}${s.nis ? `<span style="opacity:.65;font-size:10px">#${s.nis}</span>` : ''}
            </span>`
        ).join('');
    })
    .catch(() => {
        content.innerHTML = '<span class="text-muted" style="font-size:12px"><i class="bi bi-exclamation-circle me-1"></i>Gagal memuat daftar siswa.</span>';
    });
}

function onModuleChange(moduleId) {
    const box = document.getElementById('moduleInfoBox');
    if (!moduleId) {
        box.innerHTML = '<div class="text-muted" style="font-size:12px">Tidak ada modul dipilih</div>';
        return;
    }
    const m = modules.find(x => x.id == moduleId);
    if (!m) return;
    box.innerHTML = `
        <div class="text-muted mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:.5px">Modul Dipilih</div>
        <div class="fw-semibold" style="font-size:13px">${m.judul}</div>
        ${m.kode_modul ? `<div class="text-muted" style="font-size:11px">Kode: ${m.kode_modul}</div>` : ''}
        ${m.mata_pelajaran ? `<div class="text-muted" style="font-size:11px">Mapel: ${m.mata_pelajaran}</div>` : ''}
        ${m.jenis ? `<div class="text-muted" style="font-size:11px">Jenis: ${m.jenis}</div>` : ''}
        ${m.deskripsi ? `<div class="text-muted mt-1" style="font-size:11px">${m.deskripsi}</div>` : ''}
    `;
}

function onGuruChange(guruId) {
    const guruInfoBox = document.getElementById('guruInfoBox');
    if (!guruId) {
        guruInfoBox.innerHTML = '<div class="text-muted" style="font-size:12px">Pilih guru untuk melihat informasinya</div>';
        return;
    }
    const sel  = document.getElementById('guru_id');
    const name = sel.options[sel.selectedIndex]?.text || '—';
    guruInfoBox.innerHTML = `<div class="fw-semibold">${name}</div>`;
}

// Init on validation error (re-populate if form was submitted with errors)
const initPaket = document.getElementById('paket_id').value;
if (initPaket) onPaketChange(initPaket);

const initModule = document.getElementById('module_id').value;
if (initModule) onModuleChange(initModule);
</script>
@endpush
